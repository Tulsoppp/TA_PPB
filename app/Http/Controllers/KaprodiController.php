<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentKrs;
use App\Models\KrsItem;
use App\Models\QuotaRequest;
use App\Models\MbkmApplication;
use App\Models\SystemSetting;
use App\Models\BroadcastMessage;
use App\Models\Room;
use App\Models\Lecturer;
use App\Models\ActivityLog;

class KaprodiController extends Controller
{
    // BPMN: Kaprodi Membuka Masa KRS / Sistem Memvalidasi Pembukaan Masa KRS
    public function toggleMasaKrs(Request $request)
    {
        $current = SystemSetting::isKrsOpen();
        $newStatus = !$current;
        SystemSetting::set('krs_open', $newStatus ? '1' : '0');

        $statusText = $newStatus ? 'RESMI DIBUKA' : 'DITUTUP';
        $user = Auth::user();

        // Broadcast otomatis
        if ($newStatus) {
            BroadcastMessage::query()->update(['is_active' => false]);
            BroadcastMessage::create([
                'sender_id' => $user->id,
                'sender_name' => $user->name,
                'sender_role' => 'kaprodi',
                'type' => 'info_krs',
                'message' => "Pengumuman Kaprodi: Masa Pengisian Kartu Rencana Studi (KRS) Semester Gasal 2026/2027 telah RESMI DIBUKA. Mahasiswa dan Dosen Wali dipersilakan memulai proses KRS dan perwalian.",
                'is_active' => true,
            ]);
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'kaprodi',
            'title' => "Masa KRS {$statusText}",
            'detail' => "Kaprodi telah mengubah status masa pengisian KRS menjadi {$statusText} dan sistem telah memvalidasi perubahan ini.",
            'badge_type' => $newStatus ? 'success' : 'danger',
        ]);

        return response()->json([
            'success' => true,
            'is_open' => $newStatus,
            'message' => "Masa pengisian KRS berhasil {$statusText} dan telah divalidasi oleh Sistem SIAKAD."
        ]);
    }

    // BPMN Sub-process: Memasukkan Mata Kuliah (Menambah Kuota & Menambah Jadwal Mata Kuliah)
    public function storeCourse(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:20|unique:courses,id',
            'code' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'kelas' => 'required|string|max:20',
            'day' => 'required|string',
            'time_start' => 'required|string',
            'time_end' => 'required|string',
            'room' => 'required|string',
            'lecturer' => 'required|string',
            'quota' => 'required|integer|min:10|max:100',
            'is_mandatory' => 'required|boolean',
        ]);

        $schedule = "{$request->day}, {$request->time_start} - {$request->time_end} WIB";

        $course = Course::create([
            'id' => $request->id,
            'code' => $request->code,
            'name' => $request->name,
            'sks' => $request->sks,
            'kelas' => $request->kelas,
            'day' => $request->day,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
            'schedule' => $schedule,
            'room' => $request->room,
            'lecturer' => $request->lecturer,
            'quota' => $request->quota,
            'enrolled' => 0,
            'is_mandatory' => $request->is_mandatory,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'portal' => 'kaprodi',
            'title' => 'Input Mata Kuliah Baru',
            'detail' => "Kaprodi menambahkan mata kuliah {$course->code} - {$course->name} (Kelas {$course->kelas}, Kuota {$course->quota}). Sistem menyimpan detail ke basis data.",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Mata kuliah {$course->name} berhasil ditambahkan dan disimpan ke database SIAKAD.",
            'course' => $course
        ]);
    }

    // BPMN To-Be: Kaprodi Mengevaluasi Pengajuan Kuota Penuh (Gateway: Kuota Kelas Ditambah?)
    public function evaluateQuota(Request $request, QuotaRequest $quotaRequest)
    {
        $request->validate([
            'action' => 'required|in:tambah_kuota,buka_paralel,tolak',
            'add_quota' => 'nullable|integer|min:5|max:50',
            'note' => 'nullable|string',
            'parallel_class' => 'nullable|string',
            'parallel_room' => 'nullable|string',
            'parallel_lecturer' => 'nullable|string',
            'parallel_schedule' => 'nullable|string',
        ]);

        $kaprodi = Auth::user();
        $course = $quotaRequest->course;
        $action = $request->action;
        $note = $request->note ?? '';

        DB::transaction(function () use ($quotaRequest, $course, $action, $note, $request, $kaprodi) {
            if ($action === 'tambah_kuota') {
                $addQuota = (int) ($request->add_quota ?? 15);
                $oldQuota = $course->quota;
                $course->quota += $addQuota;
                $course->save();

                $decisionNote = "Kaprodi menyetujui penambahan kuota (+{$addQuota} kursi). Kapasitas bertambah dari {$oldQuota} menjadi {$course->quota} kursi. " . $note;

                // Update semua antrean request pending untuk MK ini
                QuotaRequest::where('course_id', $course->id)
                    ->where('kaprodi_decision', 'pending')
                    ->update([
                        'kaprodi_decision' => 'approved_quota',
                        'action_type' => 'tambah_kuota',
                        'kaprodi_response_note' => $decisionNote,
                        'resolved_at' => now(),
                    ]);

                // Sistem SIAKAD kirim broadcast pengumuman resmi ke seluruh mahasiswa
                BroadcastMessage::query()->update(['is_active' => false]);
                BroadcastMessage::create([
                    'sender_id' => $kaprodi->id,
                    'sender_name' => $kaprodi->name,
                    'sender_role' => 'kaprodi',
                    'type' => 'kuota_dibuka',
                    'message' => "Pengumuman Kaprodi: Kuota mata kuliah {$course->name} ({$course->code}) telah ditambah +{$addQuota} kursi (Total Kuota: {$course->quota}). Mahasiswa yang sebelumnya belum dapat mengambil kelas dipersilakan melakukan pengisian KRS sekarang.",
                    'is_active' => true,
                ]);

                ActivityLog::create([
                    'user_id' => $kaprodi->id,
                    'portal' => 'kaprodi',
                    'title' => 'Penambahan Kuota Disetujui',
                    'detail' => "Kaprodi menambah kapasitas {$course->name} sebanyak +{$addQuota} kursi. Sistem memperbarui data kuota.",
                    'badge_type' => 'success',
                ]);

            } elseif ($action === 'buka_paralel') {
                $newClass = $request->parallel_class ?? 'IF 7C';
                $newQuota = (int) ($request->add_quota ?? 40);
                $newLecturer = $request->parallel_lecturer ?? 'Dr. Budi Dwi Satoto ST., M.Kom.';
                $newRoom = $request->parallel_room ?? 'R. Gedung F-301';
                $newSchedule = $request->parallel_schedule ?? 'Jumat, 13.00 - 15.30 WIB';

                $parallelId = $course->id . '-C';
                Course::create([
                    'id' => $parallelId,
                    'code' => $course->code,
                    'name' => $course->name,
                    'sks' => $course->sks,
                    'kelas' => $newClass,
                    'semester' => $course->semester,
                    'day' => 'Jumat',
                    'time_start' => '13:00',
                    'time_end' => '15:30',
                    'schedule' => $newSchedule,
                    'room' => $newRoom,
                    'lecturer' => $newLecturer,
                    'enrolled' => 0,
                    'quota' => $newQuota,
                    'is_mandatory' => $course->is_mandatory,
                ]);

                $decisionNote = "Kaprodi menyetujui pembukaan kelas paralel baru ({$newClass}) dengan kuota {$newQuota} kursi di {$newRoom}. Pengampu: {$newLecturer}. " . $note;

                QuotaRequest::where('course_id', $course->id)
                    ->where('kaprodi_decision', 'pending')
                    ->update([
                        'kaprodi_decision' => 'approved_parallel',
                        'action_type' => 'buka_paralel',
                        'kaprodi_response_note' => $decisionNote,
                        'resolved_at' => now(),
                    ]);

                BroadcastMessage::query()->update(['is_active' => false]);
                BroadcastMessage::create([
                    'sender_id' => $kaprodi->id,
                    'sender_name' => $kaprodi->name,
                    'sender_role' => 'kaprodi',
                    'type' => 'kuota_dibuka',
                    'message' => "Pengumuman Kaprodi: Kelas Paralel Baru ({$newClass}) untuk mata kuliah {$course->name} resmi dibuka! Kuota: {$newQuota} kursi. Jadwal: {$newSchedule} ({$newRoom}). Mahasiswa dipersilakan mengambil kelas di SIAKAD.",
                    'is_active' => true,
                ]);

                ActivityLog::create([
                    'user_id' => $kaprodi->id,
                    'portal' => 'kaprodi',
                    'title' => 'Pembukaan Kelas Paralel Baru',
                    'detail' => "Kaprodi membuka kelas paralel {$newClass} untuk {$course->name} ({$newQuota} kursi). Sistem memperbarui katalog kelas.",
                    'badge_type' => 'success',
                ]);

            } else {
                // Tolak pengajuan
                $rejectionNote = "Pengajuan belum dapat disetujui: Kapasitas laboratorium dan beban mengajar dosen telah mencapai batas maksimal semester ini. " . $note;

                $quotaRequest->update([
                    'kaprodi_decision' => 'rejected',
                    'action_type' => 'ditolak',
                    'kaprodi_response_note' => $rejectionNote,
                    'resolved_at' => now(),
                ]);

                ActivityLog::create([
                    'user_id' => $kaprodi->id,
                    'portal' => 'kaprodi',
                    'title' => 'Evaluasi Kuota: Ditolak',
                    'detail' => "Permintaan kuota untuk {$course->name} ditolak karena kendala kapasitas ruang/beban dosen.",
                    'badge_type' => 'danger',
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Evaluasi pengajuan kuota berhasil diproses dan disimpan ke Sistem SIAKAD.',
            'course' => $course->fresh()
        ]);
    }

    // BPMN: Kaprodi Memvalidasi Konversi Mata Kuliah MBKM & Menginput ke KRS
    public function validateMbkm(Request $request, MbkmApplication $mbkm)
    {
        $request->validate([
            'action' => 'required|in:setuju,tolak',
            'rejection_note' => 'nullable|string',
        ]);

        $kaprodi = Auth::user();
        $student = $mbkm->user;

        if ($request->action === 'setuju') {
            DB::transaction(function () use ($mbkm, $student, $kaprodi) {
                // Daftar mata kuliah konversi MBKM 20 SKS
                $conversions = [
                    ['code' => 'MBKM501', 'name' => 'Praktik Industri Telekomunikasi & Awan', 'sks' => 4],
                    ['code' => 'MBKM502', 'name' => 'Proyek Rekayasa Perangkat Lunak Industri', 'sks' => 6],
                    ['code' => 'MBKM503', 'name' => 'Analisis dan Arsitektur Sistem Terapan', 'sks' => 4],
                    ['code' => 'MBKM504', 'name' => 'Komunikasi Profesional & Budaya Kerja TI', 'sks' => 3],
                    ['code' => 'MBKM505', 'name' => 'Etika Profesi & Tata Kelola Digital', 'sks' => 3],
                ];

                $mbkm->update([
                    'status' => 'approved',
                    'approved_by' => $kaprodi->id,
                    'approved_at' => now(),
                    'converted_courses' => $conversions,
                ]);

                // Buat/ambil student KRS dan inputkan mata kuliah konversi
                $krs = StudentKrs::firstOrCreate(
                    ['user_id' => $student->id],
                    ['advisor_id' => $student->advisor_id, 'path' => 'mbkm', 'status' => 'draft']
                );

                // Tambahkan atau pastikan course konversi ada di table courses
                foreach ($conversions as $conv) {
                    $c = Course::firstOrCreate(
                        ['id' => $conv['code']],
                        [
                            'code' => $conv['code'],
                            'name' => $conv['name'] . ' (Konversi MBKM)',
                            'sks' => $conv['sks'],
                            'kelas' => 'MBKM',
                            'semester' => 5,
                            'day' => 'Senin - Jumat',
                            'time_start' => '08:00',
                            'time_end' => '17:00',
                            'schedule' => 'Program Industri MSIB (' . $mbkm->mitra . ')',
                            'room' => 'Mitra Industri Eksternal',
                            'lecturer' => 'Mentor Industri & Kaprodi',
                            'enrolled' => 1,
                            'quota' => 50,
                            'is_mandatory' => true,
                        ]
                    );

                    KrsItem::firstOrCreate([
                        'student_krs_id' => $krs->id,
                        'course_id' => $c->id,
                    ], [
                        'status' => 'converted_mbkm',
                    ]);
                }

                ActivityLog::create([
                    'user_id' => $kaprodi->id,
                    'portal' => 'kaprodi',
                    'title' => 'Konversi Mata Kuliah MBKM Disetujui',
                    'detail' => "Kaprodi mengesahkan 20 SKS konversi program {$mbkm->program_name} ({$mbkm->mitra}) untuk mahasiswa {$student->name} ({$student->identifier}). Berkas dimasukkan langsung ke KRS mahasiswa.",
                    'badge_type' => 'success',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => "Pengajuan MBKM {$student->name} berhasil disetujui. Paket 20 SKS konversi mata kuliah telah otomatis diinputkan ke KRS mahasiswa.",
                'mbkm' => $mbkm->fresh()
            ]);
        } else {
            // Tolak MBKM -> Mahasiswa Memperbaiki Pengajuan MBKM
            $note = $request->rejection_note ?? 'Surat LoA atau portofolio penempatan industri belum memenuhi kriteria konversi 20 SKS Prodi S1 Teknik Informatika.';

            $mbkm->update([
                'status' => 'rejected',
                'rejection_note' => $note,
            ]);

            ActivityLog::create([
                'user_id' => $kaprodi->id,
                'portal' => 'kaprodi',
                'title' => 'Pengajuan MBKM Perlu Perbaikan',
                'detail' => "Kaprodi meminta perbaikan berkas MBKM {$student->name}: {$note}",
                'badge_type' => 'warning',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Pengajuan MBKM dikembalikan ke mahasiswa untuk perbaikan berkas.",
                'mbkm' => $mbkm->fresh()
            ]);
        }
    }
}
