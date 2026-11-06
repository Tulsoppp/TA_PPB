<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentKrs;
use App\Models\QuotaRequest;
use App\Models\MbkmApplication;
use App\Models\AdvisingSchedule;
use App\Models\AdvisingProgress;
use App\Models\SystemSetting;
use App\Models\BroadcastMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiakadKrsWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    public function test_login_page_renders_with_bpmn_actors(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SIAKAD KRS Terintegrasi');
        $response->assertSee('Universitas Trunojoyo Madura');
        $response->assertSee('BPMN_SIAKAD_FIX');
        $response->assertSee('Kaprodi');
        $response->assertSee('Dosen Wali');
        $response->assertSee('Mahasiswa');
    }

    public function test_login_validation_invalid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong.email@trunojoyo.ac.id',
            'password' => 'wrongpass',
        ]);
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_mahasiswa_can_login_and_view_dashboard(): void
    {
        $student = User::where('email', '240411100157@student.trunojoyo.ac.id')->first();
        $this->assertNotNull($student);

        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee('240411100157');
        $response->assertSee('IF 7B');
        $response->assertSee('Jadwal Kuliah Mingguan');
    }

    public function test_dosen_wali_can_login_and_view_advisees(): void
    {
        $dosen = User::where('email', 'rika.yunitarini@trunojoyo.ac.id')->first();
        $this->assertNotNull($dosen);

        $response = $this->actingAs($dosen)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dosen Pembimbing Akademik');
        $response->assertSee('Muhammad Ali Murtadho');
        $response->assertSee('Antrean Pengesahan (ACC)');
    }

    public function test_kaprodi_can_login_and_view_dashboard(): void
    {
        $kaprodi = User::where('email', 'kaprodi.informatika@trunojoyo.ac.id')->first();
        $this->assertNotNull($kaprodi);

        $response = $this->actingAs($kaprodi)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Koordinator Program Studi');
        $response->assertSee('Masa Pengisian KRS');
        $response->assertSee('Evaluasi Permintaan Kuota Penuh');
    }

    public function test_kaprodi_can_toggle_krs_and_store_course(): void
    {
        $kaprodi = User::where('email', 'kaprodi.informatika@trunojoyo.ac.id')->first();

        // 1. Kaprodi toggle masa KRS (BPMN: Kaprodi Membuka Masa KRS / Sistem Memvalidasi)
        $toggleRes = $this->actingAs($kaprodi)->postJson('/kaprodi/toggle-krs');
        $toggleRes->assertStatus(200);
        $toggleRes->assertJsonFragment(['success' => true]);

        // Toggle back to open
        if (!SystemSetting::isKrsOpen()) {
            $this->actingAs($kaprodi)->postJson('/kaprodi/toggle-krs');
        }
        $this->assertTrue(SystemSetting::isKrsOpen());

        // 2. Sub-process: Memasukkan Mata Kuliah (Menambah Jadwal & Kuota)
        $storeRes = $this->actingAs($kaprodi)->postJson('/kaprodi/store-course', [
            'id' => 'MK99',
            'code' => 'IF24599',
            'name' => 'Kapita Selekta Informatika',
            'sks' => 3,
            'kelas' => 'IF 7B',
            'day' => 'Kamis',
            'time_start' => '15:30',
            'time_end' => '18:00',
            'room' => 'R. Gedung F-205',
            'lecturer' => 'Dr. Arik Kurniawati, S.Kom., M.T.',
            'quota' => 35,
            'is_mandatory' => 0,
        ]);
        $storeRes->assertStatus(200);
        $this->assertDatabaseHas('courses', ['id' => 'MK99', 'code' => 'IF24599']);
    }

    public function test_dosen_wali_can_input_advising_schedule_and_system_broadcasts(): void
    {
        $dosen = User::where('email', 'rika.yunitarini@trunojoyo.ac.id')->first();

        $scheduleRes = $this->actingAs($dosen)->postJson('/advisor/schedule', [
            'title' => 'Perwalian Akademik KRS Semester Gasal 2026/2027',
            'schedule_date' => 'Kamis - Jumat, 12 - 13 November 2026',
            'time_range' => '09.00 - 15.00 WIB',
            'method' => 'both',
            'location_offline' => 'Ruang Dosen F-203, Gedung Fakultas Teknik UTM',
            'link_online' => 'https://meet.google.com/utm-krs-wali',
            'notes' => 'Harap membawa hard file form bimbingan yang telah diisi.',
        ]);
        $scheduleRes->assertStatus(200);
        $this->assertDatabaseHas('advising_schedules', ['advisor_id' => $dosen->id, 'method' => 'both']);

        // Check broadcast created
        $this->assertDatabaseHas('broadcast_messages', [
            'sender_id' => $dosen->id,
            'type' => 'jadwal_perwalian',
        ]);
    }

    public function test_mbkm_path_application_and_kaprodi_conversion(): void
    {
        $student = User::where('email', '240411100137@student.trunojoyo.ac.id')->first(); // Deo Candra

        // 1. Mahasiswa mengajukan MBKM (BPMN: Mengajukan Program MBKM & Bukti Penerimaan)
        $mbkmRes = $this->actingAs($student)->postJson('/student/submit-mbkm', [
            'program_name' => 'Magang Bersertifikat MSIB Batch 7',
            'mitra' => 'PT Telekomunikasi Indonesia (Persero) Tbk',
            'role_position' => 'Fullstack Cloud Developer',
            'duration' => '1 Semester (20 SKS Konversi)',
        ]);
        $mbkmRes->assertStatus(200);
        $mbkm = MbkmApplication::where('user_id', $student->id)->first();
        $this->assertNotNull($mbkm);
        $this->assertEquals('pending', $mbkm->status);

        // 2. Kaprodi memvalidasi dan menyetujui konversi 20 SKS (BPMN: Kaprodi Memvalidasi Konversi MBKM & Menginput ke KRS)
        $kaprodi = User::where('email', 'kaprodi.informatika@trunojoyo.ac.id')->first();
        $valRes = $this->actingAs($kaprodi)->postJson("/kaprodi/validate-mbkm/{$mbkm->id}", [
            'action' => 'setuju',
        ]);
        $valRes->assertStatus(200);

        $mbkm->refresh();
        $this->assertEquals('approved', $mbkm->status);

        // Check converted courses enrolled in Student KRS
        $studentKrs = StudentKrs::where('user_id', $student->id)->first();
        $this->assertEquals('mbkm', $studentKrs->path);
        $this->assertEquals(20, $studentKrs->courses()->sum('sks'));
    }

    public function test_reguler_krs_error_handling_and_automated_quota_resolution(): void
    {
        $student = User::where('email', '240411100157@student.trunojoyo.ac.id')->first(); // Ahmad
        $fullCourse = Course::find('MK03'); // Keamanan Informasi (Quota 40/40)

        // 1. Error: Mata Kuliah Penuh
        $addFullRes = $this->actingAs($student)->postJson('/student/add-course', [
            'course_id' => $fullCourse->id
        ]);
        $addFullRes->assertStatus(422);
        $addFullRes->assertJsonFragment(['is_full' => true, 'error_type' => 'quota_full']);

        // 2. Error: Belum Memenuhi Prasyarat
        $studentUmam = User::where('email', '240411100209@student.trunojoyo.ac.id')->first(); // Has unmet prerequisite IF24204
        $courseWithPrereq = Course::find('MK08'); // Requires IF24204
        $prereqRes = $this->actingAs($studentUmam)->postJson('/student/add-course', [
            'course_id' => $courseWithPrereq->id
        ]);
        $prereqRes->assertStatus(422);
        $prereqRes->assertJsonFragment(['error_type' => 'prerequisite_not_met']);

        // 3. Automated Quota Request directly to SIAKAD (BPI: Eliminating Himatif!)
        $quotaReqRes = $this->actingAs($student)->postJson('/student/quota-request', [
            'course_id' => $fullCourse->id,
            'urgency' => 'Mata Kuliah Wajib Semester 5 (Kelulusan Tepat Waktu)',
            'notes' => 'Kuota kelas penuh 40/40 kursi, mohon penambahan kuota agar dapat memenuhi hak 24 SKS.',
        ]);
        $quotaReqRes->assertStatus(200);

        $req = QuotaRequest::where('user_id', $student->id)->where('course_id', $fullCourse->id)->first();
        $this->assertNotNull($req);
        $this->assertTrue((bool)$req->system_validation_passed);

        // 4. Kaprodi evaluates and approves (+15 seats)
        $kaprodi = User::where('email', 'kaprodi.informatika@trunojoyo.ac.id')->first();
        $evalRes = $this->actingAs($kaprodi)->postJson("/kaprodi/evaluate-quota/{$req->id}", [
            'action' => 'tambah_kuota',
            'add_quota' => 15,
            'note' => 'Disetujui penambahan 15 kursi di Lab Jaringan Komputer.',
        ]);
        $evalRes->assertStatus(200);

        $fullCourse->refresh();
        $this->assertEquals(55, $fullCourse->quota);

        // 5. Ahmad can now successfully add the course!
        $addSuccessRes = $this->actingAs($student)->postJson('/student/add-course', [
            'course_id' => $fullCourse->id
        ]);
        $addSuccessRes->assertStatus(200);
        $addSuccessRes->assertJsonFragment(['success' => true]);

        // 6. Error: Jadwal Bertabrakan (Bentrok)
        // Student Ahmad already has MK01 on Senin 07:30 - 10:00 WIB in seeder.
        // Create an overlapping course on Senin morning to test schedule clash:
        $clashCourse = Course::create([
            'id' => 'MK_BENTROK_SENIN',
            'code' => 'IF24588',
            'name' => 'Seminar Komputasi Terapan',
            'sks' => 2,
            'kelas' => 'IF 7B',
            'semester' => 5,
            'day' => 'Senin',
            'time_start' => '08:00',
            'time_end' => '10:00',
            'schedule' => 'Senin, 08.00 - 10.00 WIB',
            'room' => 'R. Gedung F-201',
            'lecturer' => 'Dr. RIKA YUNITARINI ST., MT.',
            'enrolled' => 5,
            'quota' => 40,
            'is_mandatory' => false,
        ]);

        $clashRes = $this->actingAs($student)->postJson('/student/add-course', [
            'course_id' => $clashCourse->id
        ]);
        $clashRes->assertStatus(422);
        $clashRes->assertJsonFragment(['error_type' => 'schedule_clash']);
    }

    public function test_perwalian_form_bimbingan_and_final_acc_workflow(): void
    {
        $student = User::where('email', '240411100119@student.trunojoyo.ac.id')->first(); // Ali Murtadho
        $dosen = User::where('email', 'rika.yunitarini@trunojoyo.ac.id')->first();

        // 1. Mahasiswa mengisi Form Bimbingan Digital & Cetak Hard File
        $formRes = $this->actingAs($student)->postJson('/student/form-bimbingan', [
            'study_plan_notes' => 'Fokus semester 5 pada mata kuliah basis data lanjut dan rekayasa perangkat lunak.',
            'obstacles_encountered' => 'Tidak ada kendala akademik yang signifikan.',
            'method' => 'offline',
        ]);
        $formRes->assertStatus(200);

        // 2. Mahasiswa mengajukan KRS ke Dosen Wali
        $submitKrsRes = $this->actingAs($student)->postJson('/student/submit-krs');
        $submitKrsRes->assertStatus(200);

        $studentKrs = StudentKrs::where('user_id', $student->id)->first();
        $this->assertEquals('submitted', $studentKrs->status);

        // 3. Dosen Wali memeriksa Form Bimbingan (BPMN: Dokumen Lengkap? -> Simpan sebagai Arsip)
        $verifyDocRes = $this->actingAs($dosen)->postJson("/advisor/verify-document/{$student->id}", [
            'status' => 'complete',
            'feedback' => 'Dokumen bimbingan lengkap dan telah diarsipkan.',
        ]);
        $verifyDocRes->assertStatus(200);

        $progress = AdvisingProgress::where('user_id', $student->id)->first();
        $this->assertEquals('verified_archived', $progress->document_status);

        // 4. Dosen Wali meninjau KRS, memberi wejangan, dan memberi ACC (End Event: KRS Final Disetujui)
        $approveRes = $this->actingAs($dosen)->postJson("/advisor/approve/{$student->id}", [
            'note' => 'KRS disetujui. Pertahankan IPK dan sukses menempuh semester 5.',
        ]);
        $approveRes->assertStatus(200);

        $studentKrs->refresh();
        $this->assertEquals('approved', $studentKrs->status);
        $this->assertNotNull($studentKrs->validated_at);
        $this->assertEquals('KRS disetujui. Pertahankan IPK dan sukses menempuh semester 5.', $studentKrs->advisor_note);

        // 5. Mahasiswa dapat mencetak dokumen resmi KRS berstempel digital
        $printKrsRes = $this->actingAs($student)->get('/student/print-krs');
        $printKrsRes->assertStatus(200);
        $printKrsRes->assertSee('KARTU RENCANA STUDI');
        $printKrsRes->assertSee('TERVERIFIKASI');

        // 6. Mahasiswa dapat mencetak dokumen resmi Form Progress Bimbingan
        $printFormRes = $this->actingAs($student)->get('/student/print-form-bimbingan');
        $printFormRes->assertStatus(200);
        $printFormRes->assertSee('FORMULIR EVALUASI', false);
        $printFormRes->assertSee('PROGRESS BIMBINGAN PERWALIAN AKADEMIK', false);
    }
}
