<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuotaReport;
use App\Models\HimatifProposal;
use App\Models\BroadcastMessage;
use App\Models\Course;
use App\Models\ActivityLog;

class HimatifController extends Controller
{
    public function verifyReport(QuotaReport $report)
    {
        $himatif = Auth::user();

        $report->update([
            'verified_by_himatif' => true,
            'verified_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => $himatif->id,
            'portal' => 'himatif',
            'title' => 'Verifikasi Keluhan Mahasiswa',
            'detail' => "Keluhan {$report->user->name} untuk mata kuliah {$report->course->code} telah diverifikasi oleh Himatif.",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Laporan keluhan {$report->report_number} berhasil diverifikasi.",
            'report' => $report
        ]);
    }

    public function rejectReport(Request $request, QuotaReport $report)
    {
        $himatif = Auth::user();
        $reason = $request->input('reason', 'Tidak memenuhi kriteria advokasi kuota.');

        $report->update([
            'status' => 'Ditolak',
            'admin_note' => $reason,
        ]);

        ActivityLog::create([
            'user_id' => $himatif->id,
            'portal' => 'himatif',
            'title' => 'Penolakan Laporan Kuota',
            'detail' => "Laporan {$report->report_number} ditolak: {$reason}",
            'badge_type' => 'danger',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Laporan {$report->report_number} ditolak.",
            'report' => $report
        ]);
    }

    public function submitProposal(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'notes' => 'nullable|string',
        ]);

        $himatif = Auth::user();
        $course = Course::findOrFail($request->course_id);

        $reports = QuotaReport::where('course_id', $course->id)
            ->where('verified_by_himatif', true)
            ->where('status', '!=', 'Selesai - Kuota Dibuka')
            ->get();

        if ($reports->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada keluhan yang diverifikasi untuk mata kuliah ini.'
            ], 422);
        }

        // Update all reports
        foreach ($reports as $r) {
            $r->update(['status' => 'Diusulkan ke Admin Prodi']);
        }

        $noteText = $request->input('notes') ?? "Himatif mengusulkan penambahan kuota/kelas untuk {$course->name} berdasarkan {$reports->count()} laporan mahasiswa terdampak.";

        $proposal = HimatifProposal::create([
            'course_id' => $course->id,
            'student_count' => $reports->count(),
            'notes' => $noteText,
            'status' => 'Menunggu Keputusan Prodi',
            'submitted_by' => $himatif->id,
        ]);

        ActivityLog::create([
            'user_id' => $himatif->id,
            'portal' => 'himatif',
            'title' => 'Penyerahan Usulan ke Admin Prodi',
            'detail' => "Usulan untuk mata kuliah {$course->code} - {$course->name} ({$reports->count()} mahasiswa) telah diserahkan ke Admin Prodi.",
            'badge_type' => 'success',
        ]);

        ActivityLog::create([
            'user_id' => $himatif->id,
            'portal' => 'admin',
            'title' => 'Usulan Baru dari Himatif Diterima',
            'detail' => "Himatif menyerahkan usulan kuota untuk {$course->code} - {$course->name} ({$reports->count()} mahasiswa terdampak).",
            'badge_type' => 'proposal',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Rekapitulasi usulan untuk {$course->name} berhasil dikirimkan ke Admin Prodi.",
            'proposal' => $proposal
        ]);
    }

    public function publishBroadcast(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:5',
        ]);

        $himatif = Auth::user();

        // Deactivate old broadcasts
        BroadcastMessage::query()->update(['is_active' => false]);

        $broadcast = BroadcastMessage::create([
            'sender_id' => $himatif->id,
            'sender_name' => $himatif->name,
            'sender_role' => 'himatif',
            'message' => $request->message,
            'is_active' => true,
        ]);

        ActivityLog::create([
            'user_id' => $himatif->id,
            'portal' => 'himatif',
            'title' => 'Broadcast Informasi Diterbitkan',
            'detail' => "Pengumuman disiarkan ke seluruh portal mahasiswa: \"{$request->message}\"",
            'badge_type' => 'info',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil disebarkan ke portal seluruh mahasiswa!',
            'broadcast' => $broadcast
        ]);
    }
}

