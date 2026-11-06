<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. System Settings (Masa KRS Buka/Tutup oleh Kaprodi)
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2. Courses Table
        Schema::create('courses', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // e.g. 'MK01'
            $table->string('code', 20); // e.g. 'IF24501'
            $table->string('name');
            $table->integer('sks');
            $table->string('kelas', 20)->default('IF 7B');
            $table->integer('semester')->default(5);
            $table->string('day')->default('Senin');
            $table->string('time_start')->default('07:30');
            $table->string('time_end')->default('10:00');
            $table->string('schedule');
            $table->string('room');
            $table->string('lecturer');
            $table->integer('enrolled')->default(0);
            $table->integer('quota')->default(40);
            $table->boolean('is_mandatory')->default(true);
            $table->string('prerequisite_course_code')->nullable();
            $table->string('prerequisite_course_name')->nullable();
            $table->timestamps();
        });

        // 3. Student KRS Table (Kartu Rencana Studi)
        Schema::create('student_krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('draft'); // 'draft', 'submitted', 'approved', 'revision'
            $table->string('path')->default('reguler'); // 'reguler', 'mbkm'
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->foreignId('advisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('advisor_note')->nullable(); // Wejangan / Arahan Dosen Wali
            $table->timestamps();
        });

        // 4. KRS Items (Pivot Table with extra attributes)
        Schema::create('krs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_krs_id')->constrained('student_krs')->cascadeOnDelete();
            $table->string('course_id', 20);
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->string('status')->default('enrolled'); // 'enrolled', 'converted_mbkm'
            $table->timestamps();
        });

        // 5. Quota Requests (Permintaan Kuota Penuh Langsung ke SIAKAD - BPMN To-Be Subbab 3.5.1)
        Schema::create('quota_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique(); // REQ-KUOTA-2026-001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('course_id', 20);
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->string('urgency');
            $table->text('notes')->nullable();
            $table->integer('system_peminat_count')->default(1);
            $table->boolean('system_validation_passed')->default(true);
            $table->text('system_validation_note')->nullable();
            $table->string('kaprodi_decision')->default('pending'); // 'pending', 'approved_quota', 'approved_parallel', 'rejected'
            $table->string('action_type')->nullable(); // 'tambah_kuota', 'buka_paralel', 'ditolak'
            $table->text('kaprodi_response_note')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });

        // 6. MBKM Applications (Jalur MBKM Mahasiswa - BPMN To-Be)
        Schema::create('mbkm_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('program_name'); // e.g. "Magang Bersertifikat MSIB - Software Engineering"
            $table->string('mitra'); // e.g. "PT Telkom Indonesia (Persero) Tbk"
            $table->string('role_position')->nullable();
            $table->string('duration')->default('1 Semester (20 SKS Konversi)');
            $table->string('acceptance_letter_path')->nullable(); // SK / LoA Acceptance file
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('rejection_note')->nullable();
            $table->json('converted_courses')->nullable(); // Daftar 20 SKS konversi
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        // 7. Advising Schedules (Penjadwalan Perwalian oleh Dosen Wali - BPMN To-Be Subbab 3.5.2)
        Schema::create('advising_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advisor_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('schedule_date');
            $table->string('time_range');
            $table->string('method')->default('both'); // 'offline', 'online', 'both'
            $table->string('location_offline')->nullable();
            $table->string('link_online')->nullable();
            $table->integer('quota_per_session')->default(25);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Advising Progress (Form Progress Bimbingan Digital & Hardfile - Subbab 3.5.3)
        Schema::create('advising_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('advisor_id')->constrained('users')->cascadeOnDelete();
            $table->integer('semester')->default(5);
            $table->decimal('current_ipk', 3, 2)->nullable();
            $table->integer('sks_completed')->default(88);
            $table->text('study_plan_notes')->nullable();
            $table->text('obstacles_encountered')->nullable();
            $table->boolean('is_hardfile_printed')->default(false);
            $table->string('method')->default('offline'); // 'offline', 'online'
            $table->string('document_status')->default('submitted'); // 'draft', 'submitted', 'incomplete', 'verified_archived'
            $table->text('advisor_feedback')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();
        });

        // 9. Rooms Table
        Schema::create('rooms', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // e.g. 'RM1'
            $table->string('name');
            $table->integer('capacity');
            $table->string('slot');
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });

        // 10. Lecturers Table
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('field');
            $table->string('load')->default('9 SKS');
            $table->string('status')->default('Siap Mengampu');
            $table->timestamps();
        });

        // 11. Broadcast Messages (Notifikasi Pengumuman Resmi SIAKAD)
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sender_name')->nullable();
            $table->string('sender_role')->default('kaprodi');
            $table->string('type')->default('info_krs'); // 'jadwal_perwalian', 'kuota_dibuka', 'info_krs'
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 12. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('portal'); // 'student', 'advisor', 'kaprodi', 'system'
            $table->string('title');
            $table->text('detail');
            $table->string('badge_type')->default('info'); // info, success, warning, danger, proposal
            $table->timestamps();
        });

        // 13. Legacy Tables (for backward compatibility if needed)
        Schema::create('quota_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('course_id', 20);
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->string('urgency');
            $table->text('notes')->nullable();
            $table->string('status')->default('Menunggu Verifikasi Himatif'); 
            $table->boolean('verified_by_himatif')->default(false);
            $table->dateTime('verified_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('himatif_proposals', function (Blueprint $table) {
            $table->id();
            $table->string('course_id', 20);
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->integer('student_count')->default(1);
            $table->text('notes')->nullable();
            $table->string('status')->default('Menunggu Keputusan Prodi'); 
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_response_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('himatif_proposals');
        Schema::dropIfExists('quota_reports');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('broadcast_messages');
        Schema::dropIfExists('lecturers');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('advising_progress');
        Schema::dropIfExists('advising_schedules');
        Schema::dropIfExists('mbkm_applications');
        Schema::dropIfExists('quota_requests');
        Schema::dropIfExists('krs_items');
        Schema::dropIfExists('student_krs');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('system_settings');
    }
};
