<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentKrs;
use App\Models\QuotaRequest;
use App\Models\MbkmApplication;
use App\Models\AdvisingSchedule;
use App\Models\AdvisingProgress;
use App\Models\SystemSetting;
use App\Models\Room;
use App\Models\Lecturer;
use App\Models\BroadcastMessage;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $allUsers = User::orderBy('id')->get();
        $broadcast = BroadcastMessage::where('is_active', true)->latest()->first();
        $isKrsOpen = SystemSetting::isKrsOpen();

        switch ($user->role) {
            case 'mahasiswa':
                return $this->studentDashboard($user, $broadcast, $allUsers, $isKrsOpen);
            case 'dosen_wali':
                return $this->advisorDashboard($user, $broadcast, $allUsers, $isKrsOpen);
            case 'kaprodi':
            case 'admin':
                return $this->kaprodiDashboard($user, $broadcast, $allUsers, $isKrsOpen);
            case 'himatif':
                return $this->himatifDashboard($user, $broadcast, $allUsers, $isKrsOpen);
            default:
                Auth::logout();
                return redirect()->route('login');
        }
    }

    private function studentDashboard(User $user, $broadcast, $allUsers, bool $isKrsOpen)
    {
        $krs = StudentKrs::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 'draft', 'advisor_id' => $user->advisor_id, 'path' => 'reguler']
        );
        $krs->load('courses');

        $courses = Course::orderBy('semester')->orderBy('code')->get();
        $selectedCourseIds = $krs->courses->pluck('id')->toArray();
        $totalSks = $krs->courses->sum('sks');

        $quotaRequests = QuotaRequest::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->get();

        $mbkm = MbkmApplication::where('user_id', $user->id)->first();

        $advisingSchedule = AdvisingSchedule::where('advisor_id', $user->advisor_id)
            ->where('is_active', true)
            ->latest()
            ->first();

        $advisingProgress = AdvisingProgress::firstOrCreate(
            ['user_id' => $user->id],
            ['advisor_id' => $user->advisor_id, 'current_ipk' => $user->ipk]
        );

        $studentLogs = ActivityLog::where('user_id', $user->id)
            ->orWhere('portal', 'student')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.student', compact(
            'user', 'krs', 'courses', 'selectedCourseIds', 'totalSks',
            'quotaRequests', 'mbkm', 'advisingSchedule', 'advisingProgress',
            'studentLogs', 'broadcast', 'allUsers', 'isKrsOpen'
        ));
    }

    private function advisorDashboard(User $user, $broadcast, $allUsers, bool $isKrsOpen)
    {
        $advisees = User::where('role', 'mahasiswa')
            ->where('advisor_id', $user->id)
            ->with(['krs.courses', 'advisingProgress', 'mbkmApplication'])
            ->get();

        // Fallback jika tidak ada yang advisor_id-nya diset spesifik
        if ($advisees->isEmpty()) {
            $advisees = User::where('role', 'mahasiswa')
                ->with(['krs.courses', 'advisingProgress', 'mbkmApplication'])
                ->get();
        }

        $stats = [
            'total' => $advisees->count(),
            'pending' => $advisees->filter(fn($m) => optional($m->krs)->status === 'submitted')->count(),
            'approved' => $advisees->filter(fn($m) => optional($m->krs)->status === 'approved')->count(),
            'revision' => $advisees->filter(fn($m) => optional($m->krs)->status === 'revision')->count(),
            'bimbingan_verified' => $advisees->filter(fn($m) => optional($m->advisingProgress)->document_status === 'verified_archived')->count(),
        ];

        $schedule = AdvisingSchedule::where('advisor_id', $user->id)->where('is_active', true)->latest()->first();
        $courses = Course::orderBy('code')->get();
        $logs = ActivityLog::where('portal', 'advisor')->latest()->take(20)->get();

        return view('dashboard.advisor', compact(
            'user', 'advisees', 'stats', 'schedule', 'courses', 'logs', 'broadcast', 'allUsers', 'isKrsOpen'
        ));
    }

    private function kaprodiDashboard(User $user, $broadcast, $allUsers, bool $isKrsOpen)
    {
        $quotaRequests = QuotaRequest::with(['course', 'user'])->latest()->get();
        $mbkmApplications = MbkmApplication::with('user')->latest()->get();
        $fullCourses = Course::whereRaw('enrolled >= quota')->get();
        $allCourses = Course::orderBy('semester')->orderBy('code')->get();
        $rooms = Room::all();
        $lecturers = Lecturer::all();
        $logs = ActivityLog::whereIn('portal', ['kaprodi', 'system', 'admin'])->latest()->take(25)->get();

        $stats = [
            'is_krs_open' => $isKrsOpen,
            'full_classes' => $fullCourses->count(),
            'pending_quota_requests' => $quotaRequests->where('kaprodi_decision', 'pending')->count(),
            'resolved_quota_requests' => $quotaRequests->where('kaprodi_decision', '!=', 'pending')->count(),
            'pending_mbkm' => $mbkmApplications->where('status', 'pending')->count(),
            'approved_mbkm' => $mbkmApplications->where('status', 'approved')->count(),
            'total_courses' => $allCourses->count(),
        ];

        return view('dashboard.kaprodi', compact(
            'user', 'quotaRequests', 'mbkmApplications', 'fullCourses', 'allCourses',
            'rooms', 'lecturers', 'logs', 'stats', 'broadcast', 'allUsers', 'isKrsOpen'
        ));
    }

    private function himatifDashboard(User $user, $broadcast, $allUsers, bool $isKrsOpen)
    {
        $quotaRequests = QuotaRequest::with(['course', 'user'])->latest()->get();
        return view('dashboard.himatif', compact('user', 'quotaRequests', 'broadcast', 'allUsers', 'isKrsOpen'));
    }
}
