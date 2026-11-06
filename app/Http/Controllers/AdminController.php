<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\HimatifProposal;
use App\Models\QuotaReport;
use App\Models\BroadcastMessage;
use App\Models\Room;
use App\Models\Lecturer;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    public function executeQuotaAdd(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'add_quota' => 'required|integer|min:5|max:50',
            'room_id' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $admin = Auth::user();
        $course = Course::findOrFail($request->course_id);
        $addQuota = (int) $request->add_quota;
        $note = $request->input('note') ?? "Kapasitas kelas telah ditambahkan +{$addQuota} kursi dan disinkronkan dengan laboratorium terkait.";

        DB::transaction(function () use ($course, $addQuota, $note, $admin, $request) {
            $oldQuota = $course->quota;
            $course->quota += $addQuota;
            $course->save();

            // Resolve proposals
            HimatifProposal::where('course_id', $course->id)
                ->where('status', 'Menunggu Keputusan Prodi')
                ->update([
                    'status' => 'Disetujui - Kuota Ditambah',
                    'admin_response_note' => $note,
                ]);

            // Resolve reports
            QuotaReport::where('course_id', $course->id)
                ->whereIn('status', ['Menunggu Verifikasi Himatif', 'Diusulkan ke Admin Prodi'])
                ->update([
                    'status' => 'Selesai - Kuota Dibuka',
                    'admin_note' => $note,
                    'resolved_at' => now(),
                ]);

            // Broadcast message
            BroadcastMessage::query()->update(['is_active' => false]);
            BroadcastMessage::create([
                'sender_id' => $admin->id,
                'sender_name' => $admin->name,
                'sender_role' => 'admin',
                'message' => "Pengumuman Akademik: Kapasitas kuota mata kuliah {$course->name} telah ditambah +{$addQuota} kursi (Kini kuota: {$course->quota}). Mahasiswa dipersilakan melakukan pengisian KRS kembali.",
                'is_active' => true,
            ]);

            ActivityLog::create([
                'user_id' => $admin->id,
                'portal' => 'admin',
                'title' => 'Eksekusi Penambahan Kuota Kelas Berjalan',
                'detail' => "Menambah kuota {$course->name} dari {$oldQuota} menjadi {$course->quota} kursi. {$note}",
                'badge_type' => 'success',
            ]);

            ActivityLog::create([
                'user_id' => $admin->id,
                'portal' => 'himatif',
                'title' => 'Jawaban Admin Prodi: Usulan Kuota Disetujui',
                'detail' => "Admin Prodi telah menyetujui penambahan kuota (+{$addQuota} kursi) untuk {$course->code} - {$course->name}.",
                'badge_type' => 'success',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Kapasitas kuota untuk {$course->name} berhasil ditingkatkan sebesar +{$addQuota} kursi.",
            'course' => $course->fresh()
        ]);
    }

    public function executeOpenParallel(Request $request)
    {
        $request->validate([
            'base_course_id' => 'required|exists:courses,id',
            'new_class_name' => 'required|string',
            'new_class_quota' => 'required|integer|min:10|max:60',
            'new_class_schedule' => 'required|string',
            'new_class_lecturer' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $admin = Auth::user();
        $baseCourse = Course::findOrFail($request->base_course_id);

        $newCourseId = $baseCourse->id . '_' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $request->new_class_name), -1));
        $newCourseCode = $baseCourse->code;
        $note = $request->input('note') ?? "Kelas paralel {$request->new_class_name} telah resmi dibuka dengan jadwal {$request->new_class_schedule}.";

        DB::transaction(function () use ($baseCourse, $newCourseId, $newCourseCode, $request, $note, $admin) {
            $newCourse = Course::create([
                'id' => $newCourseId,
                'code' => $newCourseCode,
                'name' => $baseCourse->name,
                'sks' => $baseCourse->sks,
                'kelas' => $request->new_class_name,
                'schedule' => $request->new_class_schedule,
                'lecturer' => $request->new_class_lecturer,
                'enrolled' => 0,
                'quota' => (int) $request->new_class_quota,
                'is_mandatory' => $baseCourse->is_mandatory,
            ]);

            // Resolve proposals
            HimatifProposal::where('course_id', $baseCourse->id)
                ->where('status', 'Menunggu Keputusan Prodi')
                ->update([
                    'status' => 'Disetujui - Kelas Paralel Dibuka',
                    'admin_response_note' => $note,
                ]);

            // Resolve reports
            QuotaReport::where('course_id', $baseCourse->id)
                ->whereIn('status', ['Menunggu Verifikasi Himatif', 'Diusulkan ke Admin Prodi'])
                ->update([
                    'status' => 'Selesai - Kuota Dibuka',
                    'admin_note' => $note,
                    'resolved_at' => now(),
                ]);

            // Broadcast message
            BroadcastMessage::query()->update(['is_active' => false]);
            BroadcastMessage::create([
                'sender_id' => $admin->id,
                'sender_name' => $admin->name,
                'sender_role' => 'admin',
                'message' => "Pengumuman Akademik: Telah dibuka kelas paralel baru {$request->new_class_name} untuk mata kuliah {$baseCourse->name} (Dosen: {$request->new_class_lecturer}, Kuota: {$request->new_class_quota}). Silakan ambil di KRS.",
                'is_active' => true,
            ]);

            ActivityLog::create([
                'user_id' => $admin->id,
                'portal' => 'admin',
                'title' => 'Eksekusi Pembukaan Kelas Paralel Baru',
                'detail' => "Membuka kelas baru {$request->new_class_name} ({$request->new_class_quota} kursi) untuk {$baseCourse->name}. Jadwal: {$request->new_class_schedule}.",
                'badge_type' => 'success',
            ]);

            ActivityLog::create([
                'user_id' => $admin->id,
                'portal' => 'himatif',
                'title' => 'Jawaban Admin Prodi: Kelas Paralel Baru Telah Dibuka',
                'detail' => "Admin Prodi telah membuka kelas {$request->new_class_name} untuk {$baseCourse->code} - {$baseCourse->name}.",
                'badge_type' => 'success',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => "Kelas paralel baru {$request->new_class_name} untuk {$baseCourse->name} berhasil dibuka dan disosialisasikan.",
        ]);
    }
}

