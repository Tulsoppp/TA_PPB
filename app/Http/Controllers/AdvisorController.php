<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StudentKrs;
use App\Models\AdvisingSchedule;
use App\Models\AdvisingProgress;
use App\Models\BroadcastMessage;
use App\Models\ActivityLog;

class AdvisorController extends Controller
{
    // BPMN: Dosen Wali Menentukan Jadwal & Metode Perwalian -> Menginput ke SIAKAD
    public function saveSchedule(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'schedule_date' => 'required|string|max:255',
            'time_range' => 'required|string|max:100',
            'method' => 'required|in:offline,online,both',
            'location_offline' => 'nullable|string|max:255',
            'link_online' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $advisor = Auth::user();

        // Nonaktifkan jadwal lama jika ada
        AdvisingSchedule::where('advisor_id', $advisor->id)->update(['is_active' => false]);

        $schedule = AdvisingSchedule::create([
            'advisor_id' => $advisor->id,
            'title' => $request->title,
            'schedule_date' => $request->schedule_date,
            'time_range' => $request->time_range,
            'method' => $request->method,
            'location_offline' => $request->location_offline ?? 'Ruang Dosen F-203, Lantai 2 Gedung Fakultas Teknik UTM',
            'link_online' => $request->link_online ?? 'https://meet.google.com/utm-krs-wali',
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        // Sistem SIAKAD otomatis mengirim pengumuman/notifikasi ke mahasiswa bimbingan
        BroadcastMessage::query()->update(['is_active' => false]);
        BroadcastMessage::create([
            'sender_id' => $advisor->id,
            'sender_name' => $advisor->name,
            'sender_role' => 'dosen_wali',
            'type' => 'jadwal_perwalian',
            'message' => "Pengumuman Dosen Wali ({$advisor->name}): Jadwal perwalian akademik KRS telah diumumkan untuk {$schedule->schedule_date} ({$schedule->time_range}). Metode: " . strtoupper($schedule->method) . ". Mahasiswa dipersilakan melengkapi form progress bimbingan dan menyiapkan draf KRS.",
            'is_active' => true,
        ]);

        ActivityLog::create([
            'user_id' => $advisor->id,
            'portal' => 'advisor',
            'title' => 'Input Jadwal Perwalian SIAKAD',
            'detail' => "Dosen Wali menginput jadwal perwalian ({$schedule->schedule_date}). Sistem menyimpan data dan menyiarkan notifikasi resmi ke seluruh mahasiswa bimbingan.",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dan metode perwalian berhasil disimpan ke SIAKAD dan telah disiarkan ke mahasiswa bimbingan.',
            'schedule' => $schedule
        ]);
    }

    // BPMN: Dosen Wali Memeriksa Form (Hard File & Digital) -> Gateway "Dokumen Lengkap?"
    public function verifyBimbinganDocument(Request $request, User $student)
    {
        $request->validate([
            'status' => 'required|in:complete,incomplete',
            'feedback' => 'nullable|string',
        ]);

        $advisor = Auth::user();
        $progress = AdvisingProgress::firstOrCreate(
            ['user_id' => $student->id],
            ['advisor_id' => $advisor->id, 'current_ipk' => $student->ipk]
        );

        if ($request->status === 'complete') {
            $feedback = $request->feedback ?? 'Dokumen progress bimbingan lengkap dan telah diverifikasi. Hard file disimpan sebagai arsip dosen wali.';

            $progress->update([
                'document_status' => 'verified_archived',
                'advisor_feedback' => $feedback,
                'verified_at' => now(),
                'archived_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $advisor->id,
                'portal' => 'advisor',
                'title' => 'Form Bimbingan Lengkap (Diarsipkan)',
                'detail' => "Dosen Wali memvalidasi form bimbingan {$student->name} ({$student->identifier}) dan menyimpannya sebagai arsip resmi.",
                'badge_type' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Form bimbingan mahasiswa {$student->name} dinyatakan LENGKAP dan berhasil diarsipkan.",
                'progress' => $progress
            ]);
        } else {
            $feedback = $request->feedback ?? 'Dokumen bimbingan belum lengkap. Mohon melengkapi isian evaluasi capaian SKS dan rencana topik tugas akhir.';

            $progress->update([
                'document_status' => 'incomplete',
                'advisor_feedback' => $feedback,
            ]);

            ActivityLog::create([
                'user_id' => $advisor->id,
                'portal' => 'advisor',
                'title' => 'Dokumen Bimbingan Belum Lengkap',
                'detail' => "Dosen Wali meminta kelengkapan dokumen bimbingan untuk {$student->name}: {$feedback}",
                'badge_type' => 'warning',
            ]);

            return response()->json([
                'success' => true,
                'message' => "Permintaan kelengkapan dokumen telah dikirim ke mahasiswa {$student->name}.",
                'progress' => $progress
            ]);
        }
    }

    public function getStudentKrs(User $student)
    {
        $krs = StudentKrs::where('user_id', $student->id)->with('courses')->first();
        $progress = AdvisingProgress::where('user_id', $student->id)->first();
        $mbkm = MbkmApplication::where('user_id', $student->id)->first();

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'nim' => $student->identifier,
                'kelas' => $student->kelas,
                'semester' => $student->semester,
                'ips' => $student->ips,
                'ipk' => $student->ipk,
                'max_sks' => $student->max_sks,
                'ukt_status' => $student->ukt_status ?? 'Lunas',
            ],
            'progress' => $progress ? [
                'document_status' => $progress->document_status,
                'study_plan_notes' => $progress->study_plan_notes,
                'obstacles_encountered' => $progress->obstacles_encountered,
                'method' => $progress->method,
                'advisor_feedback' => $progress->advisor_feedback,
                'is_hardfile_printed' => $progress->is_hardfile_printed,
                'archived_at' => $progress->archived_at ? $progress->archived_at->format('d M Y, H:i') . ' WIB' : null,
            ] : null,
            'mbkm' => $mbkm ? [
                'program_name' => $mbkm->program_name,
                'mitra' => $mbkm->mitra,
                'role_position' => $mbkm->role_position,
                'duration' => $mbkm->duration,
                'status' => $mbkm->status,
                'converted_courses' => $mbkm->converted_courses,
            ] : null,
            'krs' => $krs ? [
                'id' => $krs->id,
                'status' => $krs->status,
                'path' => $krs->path ?? 'reguler',
                'submitted_at' => $krs->submitted_at ? $krs->submitted_at->format('d M Y, H:i') . ' WIB' : null,
                'validated_at' => $krs->validated_at ? $krs->validated_at->format('d M Y, H:i') . ' WIB' : null,
                'advisor_note' => $krs->advisor_note,
                'total_sks' => $krs->courses->sum('sks'),
                'courses' => $krs->courses->map(fn($c) => [
                    'id' => $c->id,
                    'code' => $c->code,
                    'name' => $c->name,
                    'sks' => $c->sks,
                    'schedule' => $c->schedule,
                    'room' => $c->room,
                    'lecturer' => $c->lecturer,
                    'is_mandatory' => $c->is_mandatory,
                ]),
            ] : null,
        ]);
    }

    // BPMN: Dosen Wali Meninjau KRS Mahasiswa & Memberi Wejangan -> Menyetujui KRS (KRS di ACC -> End Event)
    public function approveKrs(Request $request, User $student)
    {
        $advisor = Auth::user();
        $krs = StudentKrs::where('user_id', $student->id)->with('courses')->firstOrFail();

        $note = $request->input('note', 'KRS telah ditinjau dan disetujui sesuai rencana studi. Pertahankan prestasi dan fokus pada capaian pembelajaran semester 5.');

        $krs->update([
            'status' => 'approved',
            'validated_at' => now(),
            'advisor_id' => $advisor->id,
            'advisor_note' => $note,
        ]);

        $totalSks = $krs->courses->sum('sks');

        ActivityLog::create([
            'user_id' => $advisor->id,
            'portal' => 'advisor',
            'title' => 'Pengesahan KRS (ACC Berhasil)',
            'detail' => "Dosen Wali menyetujui KRS {$student->name} ({$student->identifier}) sejumlah {$totalSks} SKS dengan arahan: \"{$note}\"",
            'badge_type' => 'success',
        ]);

        ActivityLog::create([
            'user_id' => $student->id,
            'portal' => 'student',
            'title' => 'KRS Telah Di-ACC Dosen Wali',
            'detail' => "KRS Anda resmi disahkan oleh Dr. RIKA YUNITARINI ST., MT. Lembar KRS resmi kini dapat dicetak.",
            'badge_type' => 'success',
        ]);

        return response()->json([
            'success' => true,
            'message' => "KRS Mahasiswa {$student->name} resmi disahkan (ACC). Lembar KRS berstempel digital siap dicetak.",
            'krs' => $krs
        ]);
    }

    // BPMN: Sub-process "Revisi KRS" (Dosen Wali meminta revisi catatan perbaikan)
    public function revisionKrs(Request $request, User $student)
    {
        $request->validate([
            'note' => 'required|string|min:5',
        ]);

        $advisor = Auth::user();
        $krs = StudentKrs::where('user_id', $student->id)->firstOrFail();

        $krs->update([
            'status' => 'revision',
            'advisor_id' => $advisor->id,
            'advisor_note' => $request->note,
        ]);

        ActivityLog::create([
            'user_id' => $advisor->id,
            'portal' => 'advisor',
            'title' => 'Catatan Revisi KRS Dikirim',
            'detail' => "Meminta revisi KRS {$student->name} ({$student->identifier}): {$request->note}",
            'badge_type' => 'warning',
        ]);

        ActivityLog::create([
            'user_id' => $student->id,
            'portal' => 'student',
            'title' => 'Revisi KRS dari Dosen Wali',
            'detail' => "Dosen Wali memberikan arahan perbaikan: {$request->note}. Silakan sesuaikan pilihan mata kuliah Anda.",
            'badge_type' => 'warning',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Catatan revisi berhasil dikirim kepada {$student->name}.",
            'krs' => $krs
        ]);
    }
}
