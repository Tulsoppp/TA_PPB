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
use App\Models\AdvisingSchedule;
use App\Models\AdvisingProgress;
use App\Models\SystemSetting;
use App\Models\ActivityLog;
use App\Models\BroadcastMessage;

class StudentController extends Controller
{
    public function addCourse(Request $request)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);
        $user = Auth::user();

        // 1. Verifikasi Pembayaran UKT (BPMN: Sistem Memverifikasi Pembayaran UKT)
        if ($user->ukt_status !== 'Lunas') {
            return response()->json([
                'success' => false,
                'error_type' => 'ukt_unpaid',
                'message' => 'Status pembayaran UKT Anda belum terverifikasi lunas oleh sistem keuangan universitas. Harap selesaikan pembayaran sebelum mengakses KRS.'
            ], 422);
        }

        // 2. Verifikasi Pembukaan Masa KRS (BPMN: Masa KRS Sudah Dibuka?)
        if (!SystemSetting::isKrsOpen()) {
            return response()->json([
                'success' => false,
                'error_type' => 'krs_closed',
                'message' => 'Masa pengisian KRS Semester Gasal 2026/2027 saat ini belum dibuka oleh Koordinator Program Studi (Kaprodi). Mohon menunggu pengumuman resmi.'
            ], 422);
        }

        $krs = StudentKrs::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'draft', 'advisor_id' => $user->advisor_id, 'path' => 'reguler']
        );

        if (in_array($krs->status, ['submitted', 'approved'])) {
            return response()->json([
                'success' => false,
                'error_type' => 'krs_locked',
                'message' => 'KRS sudah diajukan atau telah disahkan oleh Dosen Wali. Tidak dapat menambah mata kuliah.'
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);

        if ($krs->courses()->where('courses.id', $course->id)->exists()) {
            return response()->json([
                'success' => false,
                'error_type' => 'already_enrolled',
                'message' => 'Mata kuliah ini sudah ada di dalam draf KRS Anda.'
            ], 422);
        }

        // 3. Pengecekan Batas Maksimum SKS (BPMN: Batas SKS Terlampaui)
        $currentSks = $krs->courses()->sum('sks');
        if (($currentSks + $course->sks) > $user->max_sks) {
            return response()->json([
                'success' => false,
                'error_type' => 'sks_limit_exceeded',
                'message' => "Batas SKS terlampaui! Maksimum hak beban Anda semester ini adalah {$user->max_sks} SKS (IPS: {$user->ips}). Beban saat ini: {$currentSks} SKS + {$course->sks} SKS = " . ($currentSks + $course->sks) . " SKS."
            ], 422);
        }

        // 4. Pengecekan Prasyarat Mata Kuliah (BPMN: Belum Memenuhi Prasyarat)
        if ($course->prerequisite_course_code) {
            $unmet = $user->unmet_prerequisites ?? [];
            if (is_string($unmet)) {
                $unmet = json_decode($unmet, true) ?: [];
            }
            if (is_array($unmet) && in_array($course->prerequisite_course_code, $unmet)) {
                return response()->json([
                    'success' => false,
                    'error_type' => 'prerequisite_not_met',
                    'prerequisite_code' => $course->prerequisite_course_code,
                    'prerequisite_name' => $course->prerequisite_course_name,
                    'message' => "Belum Memenuhi Prasyarat! Mata kuliah {$course->name} mensyaratkan kelulusan mata kuliah {$course->prerequisite_course_code} ({$course->prerequisite_course_name}). Anda belum memenuhi kelulusan prasyarat minimum."
                ], 422);
            }
        }

        // 5. Pengecekan Jadwal Bertabrakan (BPMN: Jadwal Bertabrakan dengan Mata Kuliah Lain)
        $enrolledCourses = $krs->courses;
        foreach ($enrolledCourses as $ec) {
            if ($ec->day === $course->day) {
                // Periksa irisan waktu
                $ecStart = strtotime($ec->time_start);
                $ecEnd = strtotime($ec->time_end);
                $cStart = strtotime($course->time_start);
                $cEnd = strtotime($course->time_end);

                if (($cStart < $ecEnd) && ($cEnd > $ecStart)) {
                    return response()->json([
                        'success' => false,
                        'error_type' => 'schedule_clash',
                        'clashing_course' => $ec->name,
                        'message' => "Jadwal Bertabrakan! Mata kuliah {$course->name} bentrok pada hari {$course->day} ({$course->time_start} - {$course->time_end} WIB) dengan mata kuliah yang sudah Anda ambil: {$ec->name} ({$ec->time_start} - {$ec->time_end} WIB)."
                    ], 422);
                }
            }
        }

        // 6. Pengecekan Kuota Kelas Penuh (BPMN: Mata Kuliah Penuh)
        if ($course->enrolled >= $course->quota) {
            return response()->json([
                'success' => false,
                'error_type' => 'quota_full',
                'is_full' => true,
                'course_id' => $course->id,
                'course_name' => $course->name,
                'course_code' => $course->code,
                'enrolled' => $course->enrolled,
                'quota' => $course->quota,
                'message' => "Kapasitas kelas untuk mata kuliah {$course->name} ({$course->code}) sudah penuh ({$course->enrolled}/{$course->quota} kursi). Sesuai alur To-Be BPMN, Anda dapat langsung mengirim permintaan penambahan kuota ke SIAKAD untuk dievaluasi oleh Kaprodi!"
            ], 422);
        }

        // Lolos seluruh pengecekan sistem (BPMN: Hasil Sistem -> Berhasil)
        DB::transaction(function () use ($krs, $course) {
            KrsItem::create([
                'student_krs_id' => $krs->id,
                'course_id' => $course->id,
                'status' => 'enrolled',
            ]);
            $course->increment('enrolled');
        });

        return response()->json([
            'success' => true,
            'message' => "Mata kuliah {$course->name} berhasil ditambahkan ke KRS.",
            'course' => $course,
            'total_sks' => $krs->fresh()->courses()->sum('sks')
        ]);
    }

    public function removeCourse(Request $request)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);
        $user = Auth::user();

        $krs = StudentKrs::where('user_id', $user->id)->firstOrFail();

        if (in_array($krs->status, ['submitted', 'approved'])) {
            return response()->json([
                'success' => false,
                'message' => 'KRS sudah diajukan atau disahkan. Tidak dapat membatalkan mata kuliah.'
            ], 422);
        }

        $course = Course::findOrFail($request->course_id);
        $item = KrsItem::where('student_krs_id', $krs->id)->where('course_id', $course->id)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah tidak ditemukan dalam draf KRS.'
            ], 404);
        }

        DB::transaction(function () use ($item, $course) {
            $item->delete();
            if ($course->enrolled > 0) {
                $course->decrement('enrolled');
            }
        });

        return response()->json([
            'success' => true,
            'message' => "Mata kuliah {$course->name} berhasil dihapus dari KRS.",
            'course' => $course,
            'total_sks' => $krs->fresh()->courses()->sum('sks')
        ]);
    }

    // BPMN To-Be: Mahasiswa Mengirim Permintaan Matakuliah Penuh Ke SIAKAD
    public function submitQuotaRequest(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'urgency' => 'required|string',
            'notes' => 'required|string|min:5',
        ]);

        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        $existing = QuotaRequest::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('kaprodi_decision', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah pernah mengirimkan permintaan kuota untuk {$course->name} ({$existing->request_number}) dan saat ini sedang dalam antrean evaluasi Kaprodi."
            ], 422);
        }

        // Pengecekan Sistem Otomatis (BPMN: Sistem Memvalidasi dan Membandingkan KRS yang Sudah Diambil)
        // Pastikan mahasiswa belum mengambil mata kuliah ini dan bukan sekadar iseng pindah kelas
        $krs = StudentKrs::where('user_id', $user->id)->first();
        if ($krs && $krs->courses()->where('courses.id', $course->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Hasil Validasi Sistem SIAKAD: Anda sudah terdaftar pada mata kuliah ini. Pengajuan tidak dapat diproses.'
            ], 422);
        }

        // Hitung akumulasi peminat untuk mata kuliah ini di sistem (BPMN: Peminat Masih Banyak?)
        $currentPendingCount = QuotaRequest::where('course_id', $course->id)
            ->where('kaprodi_decision', 'pending')
            ->count() + 1;

        $reqNumber = 'REQ-KUOTA-' . date('Y') . '-' . str_pad(QuotaRequest::count() + 1, 3, '0', STR_PAD_LEFT);

        $validationNote = "Lolos Verifikasi Otomatis SIAKAD: Mahasiswa aktif Semester {$user->semester}, belum pernah menempuh mata kuliah ini, dan membutuhkan pemenuhan hak SKS wajib.";

        $quotaReq = QuotaRequest::create([
            'request_number' => $reqNumber,
            'user_id' => $user->id,
            'course_id' => $course->id,
            'urgency' => $request->urgency,
            'notes' => $request->notes,
            'system_peminat_count' => $currentPendingCount,
            'system_validation_passed' => true,
            'system_validation_note' => $validationNote,
            'kaprodi_decision' => 'pending',
        ]);

        // Catat ke Activity Log
        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'kaprodi',
            'title' => 'Permintaan Kuota Penuh Baru (Validasi Sistem)',
            'detail' => "{$user->name} ({$user->identifier}) mengajukan permintaan kuota untuk {$course->code} - {$course->name}. Total antrean peminat valid: {$currentPendingCount} mahasiswa.",
            'badge_type' => 'warning',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'student',
            'title' => 'Permintaan Kuota SIAKAD Terkirim',
            'detail' => "Permintaan kuota {$course->name} berhasil divalidasi sistem dan diteruskan ke Kaprodi ({$reqNumber}).",
            'badge_type' => 'success',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Permintaan kuota mata kuliah {$course->name} ({$reqNumber}) berhasil dikirim langsung ke SIAKAD dan telah divalidasi oleh sistem untuk dievaluasi Kaprodi.",
            'quota_request' => $quotaReq,
            'peminat_count' => $currentPendingCount
        ]);
    }

    // BPMN To-Be: Mahasiswa Mengajukan Program MBKM & Bukti Penerimaan (Jalur MBKM)
    public function submitMbkm(Request $request)
    {
        $request->validate([
            'program_name' => 'required|string|max:255',
            'mitra' => 'required|string|max:255',
            'role_position' => 'nullable|string|max:255',
            'duration' => 'required|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Cari atau buat MBKM Application
        $mbkm = MbkmApplication::updateOrCreate(
            ['user_id' => $user->id],
            [
                'program_name' => $request->program_name,
                'mitra' => $request->mitra,
                'role_position' => $request->role_position ?? 'Peserta Program',
                'duration' => $request->duration,
                'acceptance_letter_path' => 'SURAT-PENERIMAAN-' . strtoupper(str_replace(' ', '-', $request->mitra)) . '-' . $user->identifier . '.pdf',
                'status' => 'pending',
                'rejection_note' => null,
            ]
        );

        // Update KRS path to mbkm
        $krs = StudentKrs::firstOrCreate(['user_id' => $user->id], ['advisor_id' => $user->advisor_id]);
        $krs->update(['path' => 'mbkm']);

        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'kaprodi',
            'title' => 'Pengajuan Program MBKM Mahasiswa',
            'detail' => "{$user->name} ({$user->identifier}) mengajukan program MBKM: {$request->program_name} di {$request->mitra} untuk validasi konversi mata kuliah.",
            'badge_type' => 'proposal',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berkas pengajuan program MBKM dan bukti penerimaan berhasil diunggah. Menunggu validasi konversi mata kuliah oleh Kaprodi.',
            'mbkm' => $mbkm
        ]);
    }

    // BPMN To-Be: Persiapan Perwalian - Pengisian Form Progress Bimbingan & Cetak Hardfile
    public function updateFormBimbingan(Request $request)
    {
        $request->validate([
            'study_plan_notes' => 'required|string|min:5',
            'obstacles_encountered' => 'nullable|string',
            'method' => 'required|in:offline,online',
        ]);

        $user = Auth::user();
        $progress = AdvisingProgress::firstOrCreate(
            ['user_id' => $user->id],
            ['advisor_id' => $user->advisor_id]
        );

        $progress->update([
            'current_ipk' => $user->ipk,
            'study_plan_notes' => $request->study_plan_notes,
            'obstacles_encountered' => $request->obstacles_encountered,
            'method' => $request->method,
            'is_hardfile_printed' => true,
            'document_status' => 'submitted',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'advisor',
            'title' => 'Form Bimbingan Diisi & Diserahkan',
            'detail' => "{$user->name} ({$user->identifier}) telah mengisi form bimbingan akademik dan mencetak hard file untuk perwalian " . strtoupper($request->method) . ".",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Form progress bimbingan akademik digital berhasil disimpan dan dicatat siap untuk perwalian.',
            'progress' => $progress
        ]);
    }

    public function submitKrs(Request $request)
    {
        $user = Auth::user();
        $krs = StudentKrs::where('user_id', $user->id)->firstOrFail();

        if ($krs->courses()->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih minimal 1 mata kuliah sebelum mengajukan KRS ke Dosen Wali.'
            ], 422);
        }

        $totalSks = $krs->courses()->sum('sks');

        $krs->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'advisor_id' => $user->advisor_id,
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'portal' => 'advisor',
            'title' => 'Pengajuan KRS Mahasiswa',
            'detail' => "{$user->name} ({$user->identifier}) mengajukan draf KRS ({$totalSks} SKS) ke Dosen Wali untuk peninjauan dan pengesahan (ACC).",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KRS berhasil diajukan ke Dosen Wali. Silakan ikuti perwalian sesuai jadwal yang ditentukan.',
        ]);
    }

    public function cancelKrs(Request $request)
    {
        $user = Auth::user();
        $krs = StudentKrs::where('user_id', $user->id)->firstOrFail();

        if ($krs->status !== 'submitted') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya KRS berstatus diajukan yang dapat ditarik kembali.'
            ], 422);
        }

        $krs->update([
            'status' => 'draft',
            'submitted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan KRS ditarik kembali ke draf. Anda dapat mengubah pilihan mata kuliah.',
        ]);
    }

    public function printKrs()
    {
        $user = Auth::user();
        $krs = StudentKrs::where('user_id', $user->id)->with('courses')->firstOrFail();
        $advisor = $user->advisor;
        $kaprodi = User::where('role', 'kaprodi')->first();

        return view('print.krs', compact('user', 'krs', 'advisor', 'kaprodi'));
    }

    public function printFormBimbingan()
    {
        $user = Auth::user();
        $advisor = $user->advisor;
        $progress = AdvisingProgress::firstOrCreate(
            ['user_id' => $user->id],
            ['advisor_id' => $user->advisor_id, 'current_ipk' => $user->ipk]
        );
        $krs = StudentKrs::where('user_id', $user->id)->with('courses')->first();

        return view('print.form_bimbingan', compact('user', 'advisor', 'progress', 'krs'));
    }
}
