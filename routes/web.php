<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdvisorController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\SimulationController;

// Authentication Routes
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/quick-login', [AuthController::class, 'quickLogin'])->name('login.quick');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mahasiswa Portal Routes (BPMN: Lane Mahasiswa)
    Route::prefix('student')->name('student.')->group(function () {
        Route::post('/add-course', [StudentController::class, 'addCourse'])->name('add_course');
        Route::post('/remove-course', [StudentController::class, 'removeCourse'])->name('remove_course');
        Route::post('/submit-krs', [StudentController::class, 'submitKrs'])->name('submit_krs');
        Route::post('/cancel-krs', [StudentController::class, 'cancelKrs'])->name('cancel_krs');
        Route::post('/quota-request', [StudentController::class, 'submitQuotaRequest'])->name('quota_request');
        Route::post('/submit-mbkm', [StudentController::class, 'submitMbkm'])->name('submit_mbkm');
        Route::post('/form-bimbingan', [StudentController::class, 'updateFormBimbingan'])->name('form_bimbingan');
        Route::get('/print-krs', [StudentController::class, 'printKrs'])->name('print_krs');
        Route::get('/print-form-bimbingan', [StudentController::class, 'printFormBimbingan'])->name('print_form_bimbingan');
    });

    // Dosen Wali Portal Routes (BPMN: Lane Dosen Wali)
    Route::prefix('advisor')->name('advisor.')->group(function () {
        Route::post('/schedule', [AdvisorController::class, 'saveSchedule'])->name('save_schedule');
        Route::get('/student/{student}', [AdvisorController::class, 'getStudentKrs'])->name('get_student');
        Route::post('/verify-document/{student}', [AdvisorController::class, 'verifyBimbinganDocument'])->name('verify_document');
        Route::post('/approve/{student}', [AdvisorController::class, 'approveKrs'])->name('approve');
        Route::post('/revision/{student}', [AdvisorController::class, 'revisionKrs'])->name('revision');
    });

    // Kaprodi Portal Routes (BPMN: Lane Kaprodi)
    Route::prefix('kaprodi')->name('kaprodi.')->group(function () {
        Route::post('/toggle-krs', [KaprodiController::class, 'toggleMasaKrs'])->name('toggle_krs');
        Route::post('/store-course', [KaprodiController::class, 'storeCourse'])->name('store_course');
        Route::post('/evaluate-quota/{quotaRequest}', [KaprodiController::class, 'evaluateQuota'])->name('evaluate_quota');
        Route::post('/validate-mbkm/{mbkm}', [KaprodiController::class, 'validateMbkm'])->name('validate_mbkm');
    });

    // Alias routes for Admin (backward compatibility)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('/toggle-krs', [KaprodiController::class, 'toggleMasaKrs'])->name('toggle_krs');
        Route::post('/execute-quota', function (\Illuminate\Http\Request $request) {
            $qr = \App\Models\QuotaRequest::where('course_id', $request->course_id)->first();
            if ($qr) {
                return app(KaprodiController::class)->evaluateQuota($request, $qr);
            }
            return response()->json(['success' => false, 'message' => 'Request not found'], 404);
        })->name('execute_quota');
    });

    // Simulation Reset
    Route::post('/simulation/reset', [SimulationController::class, 'reset'])->name('simulation.reset');
});
