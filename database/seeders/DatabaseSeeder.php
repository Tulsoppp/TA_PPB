<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\StudentKrs;
use App\Models\KrsItem;
use App\Models\QuotaRequest;
use App\Models\MbkmApplication;
use App\Models\AdvisingSchedule;
use App\Models\AdvisingProgress;
use App\Models\SystemSetting;
use App\Models\Room;
use App\Models\Lecturer;
use App\Models\BroadcastMessage;
use App\Models\ActivityLog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed System Settings (Masa KRS)
        SystemSetting::set('krs_open', '1', 'Status Masa Pengisian KRS (1=Dibuka, 0=Belum Dibuka)');
        SystemSetting::set('academic_year', '2026/2027 Ganjil', 'Tahun Akademik Berjalan');
        SystemSetting::set('semester_active', '5', 'Semester Reguler');

        // 2. Seed Kaprodi (Kepala Program Studi S1 Teknik Informatika UTM)
        $kaprodi = User::create([
            'email' => 'kaprodi.informatika@trunojoyo.ac.id',
            'name' => 'Dr. Arik Kurniawati, S.Kom., M.T.',
            'password' => Hash::make('kaprodi2026'),
            'role' => 'kaprodi',
            'role_label' => 'Koordinator Program Studi S1 Teknik Informatika',
            'identifier' => '197905142005012002',
            'initials' => 'AK',
        ]);

        // Secondary alias account for Admin / Kaprodi
        $adminAlias = User::create([
            'email' => 'admin.informatika@trunojoyo.ac.id',
            'name' => 'Dr. Arik Kurniawati, S.Kom., M.T. (Kaprodi)',
            'password' => Hash::make('admin2026'),
            'role' => 'kaprodi',
            'role_label' => 'Koordinator Program Studi S1 Teknik Informatika',
            'identifier' => '197905142005012002',
            'initials' => 'KP',
        ]);

        // 3. Seed Dosen Wali / Pembimbing Akademik (Sesuai LAPORAN_TA.pdf)
        $dosen = User::create([
            'email' => 'rika.yunitarini@trunojoyo.ac.id',
            'name' => 'Dr. RIKA YUNITARINI ST., MT.',
            'password' => Hash::make('dosen2026'),
            'role' => 'dosen_wali',
            'role_label' => 'Dosen Pembimbing Akademik (IF 7B)',
            'identifier' => '198306072006042001',
            'initials' => 'RY',
        ]);

        // 4. Seed Himatif (Archival / Informative Reference Account)
        $himatif = User::create([
            'email' => 'advokasi.himatif@trunojoyo.ac.id',
            'name' => 'Divisi Advokasi Himatif UTM (Arsip BPMN As-Is)',
            'password' => Hash::make('himatif2026'),
            'role' => 'himatif',
            'role_label' => 'Pengurus Organisasi Mahasiswa (Himatif)',
            'identifier' => 'HIMATIF-ADV',
            'initials' => 'HM',
        ]);

        // 5. Seed Mahasiswa (Kelompok Mahasiswa Pengusul IF 7B pada LAPORAN_TA.pdf)
        $studentsData = [
            [
                'email' => '240411100157@student.trunojoyo.ac.id',
                'name' => 'Ahmad Maulana Ishaq',
                'identifier' => '240411100157',
                'initials' => 'AM',
                'role' => 'mahasiswa',
                'role_label' => 'Mahasiswa S1 Teknik Informatika',
                'kelas' => 'IF 7B',
                'semester' => 5,
                'ips' => 3.82,
                'ipk' => 3.78,
                'max_sks' => 24,
                'advisor_id' => $dosen->id,
                'ukt_status' => 'Lunas',
                'ukt_paid_at' => now()->subDays(10),
                'password' => Hash::make('utm2026'),
            ],
            [
                'email' => '240411100119@student.trunojoyo.ac.id',
                'name' => 'Muhammad Ali Murtadho',
                'identifier' => '240411100119',
                'initials' => 'MA',
                'role' => 'mahasiswa',
                'role_label' => 'Mahasiswa S1 Teknik Informatika',
                'kelas' => 'IF 7B',
                'semester' => 5,
                'ips' => 3.76,
                'ipk' => 3.72,
                'max_sks' => 24,
                'advisor_id' => $dosen->id,
                'ukt_status' => 'Lunas',
                'ukt_paid_at' => now()->subDays(12),
                'password' => Hash::make('utm2026'),
            ],
            [
                'email' => '240411100131@student.trunojoyo.ac.id',
                'name' => 'Mochammad Zaenal Abidin',
                'identifier' => '240411100131',
                'initials' => 'MZ',
                'role' => 'mahasiswa',
                'role_label' => 'Mahasiswa S1 Teknik Informatika',
                'kelas' => 'IF 7B',
                'semester' => 5,
                'ips' => 3.79,
                'ipk' => 3.75,
                'max_sks' => 24,
                'advisor_id' => $dosen->id,
                'ukt_status' => 'Lunas',
                'ukt_paid_at' => now()->subDays(9),
                'password' => Hash::make('utm2026'),
            ],
            [
                'email' => '240411100137@student.trunojoyo.ac.id',
                'name' => 'Deo Candra Saputra',
                'identifier' => '240411100137',
                'initials' => 'DC',
                'role' => 'mahasiswa',
                'role_label' => 'Mahasiswa S1 Teknik Informatika (MBKM)',
                'kelas' => 'IF 7B',
                'semester' => 5,
                'ips' => 3.70,
                'ipk' => 3.68,
                'max_sks' => 24,
                'advisor_id' => $dosen->id,
                'ukt_status' => 'Lunas',
                'ukt_paid_at' => now()->subDays(11),
                'password' => Hash::make('utm2026'),
            ],
            [
                'email' => '240411100209@student.trunojoyo.ac.id',
                'name' => 'M. Fatihul Umam',
                'identifier' => '240411100209',
                'initials' => 'MF',
                'role' => 'mahasiswa',
                'role_label' => 'Mahasiswa S1 Teknik Informatika',
                'kelas' => 'IF 7B',
                'semester' => 5,
                'ips' => 3.84,
                'ipk' => 3.80,
                'max_sks' => 24,
                'advisor_id' => $dosen->id,
                'ukt_status' => 'Lunas',
                'ukt_paid_at' => now()->subDays(8),
                'unmet_prerequisites' => ['IF24204'], // Belum menempuh Matematika Komputasi (prasyarat Pengolahan Citra Digital - MK08)
                'password' => Hash::make('utm2026'),
            ],
        ];

        $createdStudents = [];
        foreach ($studentsData as $s) {
            $createdStudents[] = User::create($s);
        }

        // 6. Seed Courses (Mata Kuliah Lengkap dengan Ruang, Jadwal, dan Prasyarat)
        $coursesData = [
            [
                'id' => 'MK01',
                'code' => 'IF24501',
                'name' => 'Pemodelan Proses Bisnis',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Senin',
                'time_start' => '07:30',
                'time_end' => '10:00',
                'schedule' => 'Senin, 07.30 - 10.00 WIB',
                'room' => 'R. Gedung F-201',
                'lecturer' => 'Dr. RIKA YUNITARINI ST., MT.',
                'enrolled' => 34,
                'quota' => 40,
                'is_mandatory' => true,
                'prerequisite_course_code' => 'IF24301',
                'prerequisite_course_name' => 'Sistem Informasi Manajemen',
            ],
            [
                'id' => 'MK02',
                'code' => 'IF24502',
                'name' => 'Kecerdasan Buatan (Artificial Intelligence)',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Selasa',
                'time_start' => '09:30',
                'time_end' => '12:00',
                'schedule' => 'Selasa, 09.30 - 12.00 WIB',
                'room' => 'Lab Komputasi Cerdas',
                'lecturer' => 'Dr. Budi Dwi Satoto ST., M.Kom.',
                'enrolled' => 40,
                'quota' => 40, // PENUH
                'is_mandatory' => true,
                'prerequisite_course_code' => 'IF24202',
                'prerequisite_course_name' => 'Logika Informatika & Struktur Diskrit',
            ],
            [
                'id' => 'MK03',
                'code' => 'IF24503',
                'name' => 'Keamanan Informasi & Jaringan',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Rabu',
                'time_start' => '10:00',
                'time_end' => '12:30',
                'schedule' => 'Rabu, 10.00 - 12.30 WIB',
                'room' => 'Lab Jaringan Komputer',
                'lecturer' => 'Dr. Kautsar Sophan ST., MT.',
                'enrolled' => 40,
                'quota' => 40, // PENUH
                'is_mandatory' => true,
                'prerequisite_course_code' => 'IF24304',
                'prerequisite_course_name' => 'Jaringan Komputer Lanjut',
            ],
            [
                'id' => 'MK04',
                'code' => 'IF24504',
                'name' => 'Rekayasa Perangkat Lunak Lanjut',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Kamis',
                'time_start' => '08:00',
                'time_end' => '10:30',
                'schedule' => 'Kamis, 08.00 - 10.30 WIB',
                'room' => 'R. Gedung F-204',
                'lecturer' => 'Fika Hastarita Rachman ST., M.Eng.',
                'enrolled' => 32,
                'quota' => 40,
                'is_mandatory' => true,
                'prerequisite_course_code' => 'IF24401',
                'prerequisite_course_name' => 'Rekayasa Perangkat Lunak',
            ],
            [
                'id' => 'MK05',
                'code' => 'IF24505',
                'name' => 'Manajemen Proyek Teknologi Informasi',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Jumat',
                'time_start' => '08:00',
                'time_end' => '10:30',
                'schedule' => 'Jumat, 08.00 - 10.30 WIB',
                'room' => 'R. Gedung F-105',
                'lecturer' => 'Hermawan ST., M.Kom.',
                'enrolled' => 29,
                'quota' => 40,
                'is_mandatory' => true,
                'prerequisite_course_code' => null,
                'prerequisite_course_name' => null,
            ],
            [
                'id' => 'MK06',
                'code' => 'IF24506',
                'name' => 'Pemrograman Perangkat Bergerak (Mobile)',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Kamis',
                'time_start' => '13:00',
                'time_end' => '15:30',
                'schedule' => 'Kamis, 13.00 - 15.30 WIB',
                'room' => 'Lab Rekayasa Perangkat Lunak',
                'lecturer' => 'Achmad Jauhari ST., M.Kom.',
                'enrolled' => 31,
                'quota' => 40,
                'is_mandatory' => false,
                'prerequisite_course_code' => 'IF24303',
                'prerequisite_course_name' => 'Pemrograman Berorientasi Objek',
            ],
            [
                'id' => 'MK07',
                'code' => 'IF24507',
                'name' => 'Metodologi Penelitian Informatika',
                'sks' => 2,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Senin',
                'time_start' => '13:00',
                'time_end' => '14:40',
                'schedule' => 'Senin, 13.00 - 14.40 WIB',
                'room' => 'R. Gedung F-202',
                'lecturer' => 'Dr. RIKA YUNITARINI ST., MT.',
                'enrolled' => 33,
                'quota' => 40,
                'is_mandatory' => true,
                'prerequisite_course_code' => null,
                'prerequisite_course_name' => null,
            ],
            [
                'id' => 'MK08',
                'code' => 'IF24508',
                'name' => 'Pengolahan Citra Digital (Digital Image Processing)',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Selasa',
                'time_start' => '09:30',
                'time_end' => '12:00', // Jadwal sama dengan MK02 -> Bentrok!
                'schedule' => 'Selasa, 09.30 - 12.00 WIB (Bentrok dengan AI)',
                'room' => 'Lab Komputer Multimedia',
                'lecturer' => 'Dr. Arif Muntasa S.Si., M.T.',
                'enrolled' => 18,
                'quota' => 40,
                'is_mandatory' => false,
                'prerequisite_course_code' => 'IF24204',
                'prerequisite_course_name' => 'Matematika Komputasi',
            ],
            [
                'id' => 'MK09',
                'code' => 'IF24509',
                'name' => 'Cloud Computing & Arsitektur Terdistribusi',
                'sks' => 3,
                'kelas' => 'IF 7B',
                'semester' => 5,
                'day' => 'Rabu',
                'time_start' => '13:00',
                'time_end' => '15:30',
                'schedule' => 'Rabu, 13.00 - 15.30 WIB',
                'room' => 'Lab Komputasi Awan & Jaringan',
                'lecturer' => 'Dr. Kautsar Sophan ST., MT.',
                'enrolled' => 15,
                'quota' => 40,
                'is_mandatory' => false,
                'prerequisite_course_code' => null,
                'prerequisite_course_name' => null,
            ],
        ];

        foreach ($coursesData as $c) {
            Course::create($c);
        }

        // 7. Seed Student KRS & Items
        // Student 0: Ahmad Maulana Ishaq (Draf, mencoba ambil MK02 penuh)
        $krs0 = StudentKrs::create([
            'user_id' => $createdStudents[0]->id,
            'advisor_id' => $dosen->id,
            'status' => 'draft',
            'path' => 'reguler',
            'submitted_at' => null,
        ]);
        foreach (['MK01', 'MK04', 'MK05', 'MK07'] as $cid) {
            KrsItem::create(['student_krs_id' => $krs0->id, 'course_id' => $cid, 'status' => 'enrolled']);
        }

        // Student 1: Muhammad Ali Murtadho (Submitted -> Siap di-ACC Dosen Wali)
        $krs1 = StudentKrs::create([
            'user_id' => $createdStudents[1]->id,
            'advisor_id' => $dosen->id,
            'status' => 'submitted',
            'path' => 'reguler',
            'submitted_at' => now()->subHours(3),
            'advisor_note' => null,
        ]);
        foreach (['MK01', 'MK04', 'MK05', 'MK06', 'MK07'] as $cid) {
            KrsItem::create(['student_krs_id' => $krs1->id, 'course_id' => $cid, 'status' => 'enrolled']);
        }

        // Student 2: Mochammad Zaenal Abidin (Draf)
        $krs2 = StudentKrs::create([
            'user_id' => $createdStudents[2]->id,
            'advisor_id' => $dosen->id,
            'status' => 'draft',
            'path' => 'reguler',
            'submitted_at' => null,
        ]);
        foreach (['MK01', 'MK04', 'MK05', 'MK07'] as $cid) {
            KrsItem::create(['student_krs_id' => $krs2->id, 'course_id' => $cid, 'status' => 'enrolled']);
        }

        // Student 3: Deo Candra Saputra (Jalur MBKM)
        $krs3 = StudentKrs::create([
            'user_id' => $createdStudents[3]->id,
            'advisor_id' => $dosen->id,
            'status' => 'draft',
            'path' => 'mbkm',
            'submitted_at' => null,
        ]);

        // Student 4: M. Fatihul Umam (Draf)
        $krs4 = StudentKrs::create([
            'user_id' => $createdStudents[4]->id,
            'advisor_id' => $dosen->id,
            'status' => 'draft',
            'path' => 'reguler',
            'submitted_at' => null,
        ]);
        foreach (['MK01', 'MK05', 'MK07'] as $cid) {
            KrsItem::create(['student_krs_id' => $krs4->id, 'course_id' => $cid, 'status' => 'enrolled']);
        }

        // 8. Seed Advising Schedule (Dosen Wali menginput jadwal & metode perwalian)
        $schedule = AdvisingSchedule::create([
            'advisor_id' => $dosen->id,
            'title' => 'Perwalian Akademik Pengesahan KRS Semester Gasal 2026/2027',
            'schedule_date' => 'Senin - Rabu, 10 - 12 November 2026',
            'time_range' => '09.00 - 15.00 WIB',
            'method' => 'both',
            'location_offline' => 'Ruang Dosen F-203, Gedung Fakultas Teknik UTM',
            'link_online' => 'https://meet.google.com/utm-krs-wali',
            'quota_per_session' => 25,
            'notes' => 'Harap mahasiswa mengisi Form Progress Bimbingan Akademik digital, mencetak hardfile form bimbingan, dan membawa draf KRS saat perwalian tatap muka atau daring.',
            'is_active' => true,
        ]);

        // 9. Seed Advising Progress (Form Bimbingan Digital & Hardfile)
        foreach ($createdStudents as $idx => $st) {
            AdvisingProgress::create([
                'user_id' => $st->id,
                'advisor_id' => $dosen->id,
                'semester' => 5,
                'current_ipk' => $st->ipk,
                'sks_completed' => 88,
                'study_plan_notes' => 'Rencana mengambil 18 SKS semester 5 dan mulai menyusun topik tugas akhir bidang Artificial Intelligence & Enterprise System.',
                'obstacles_encountered' => 'Kendala pada ketersediaan kuota kelas wajib semester 5.',
                'is_hardfile_printed' => ($idx === 1), // Muhammad Ali Murtadho printed
                'method' => ($idx % 2 === 0) ? 'offline' : 'online',
                'document_status' => ($idx === 1) ? 'verified_archived' : 'submitted',
                'advisor_feedback' => ($idx === 1) ? 'Dokumen bimbingan lengkap. Perwalian tatap muka telah dilakukan dengan baik.' : null,
                'verified_at' => ($idx === 1) ? now()->subHours(2) : null,
                'archived_at' => ($idx === 1) ? now()->subHours(2) : null,
            ]);
        }

        // 10. Seed MBKM Application for Deo Candra Saputra (Jalur MBKM)
        MbkmApplication::create([
            'user_id' => $createdStudents[3]->id,
            'program_name' => 'Magang Bersertifikat Kampus Merdeka (MSIB Batch 7)',
            'mitra' => 'PT Telkom Indonesia (Persero) Tbk',
            'role_position' => 'Software Engineer & Cloud Platform Specialist',
            'duration' => '1 Semester (Agustus 2026 - Januari 2027)',
            'acceptance_letter_path' => 'LOA-TELKOM-MSIB-240411100137.pdf',
            'status' => 'pending',
            'converted_courses' => [
                ['code' => 'MBKM501', 'name' => 'Praktik Industri Telekomunikasi & Awan', 'sks' => 4],
                ['code' => 'MBKM502', 'name' => 'Proyek Rekayasa Perangkat Lunak Industri', 'sks' => 6],
                ['code' => 'MBKM503', 'name' => 'Analisis dan Arsitektur Sistem Terapan', 'sks' => 4],
                ['code' => 'MBKM504', 'name' => 'Komunikasi Profesional & Budaya Kerja TI', 'sks' => 3],
                ['code' => 'MBKM505', 'name' => 'Etika Profesi & Tata Kelola Digital', 'sks' => 3],
            ],
        ]);

        // 11. Seed Quota Requests (Penanganan Kuota Penuh Otomatis SIAKAD - BPMN To-Be)
        QuotaRequest::create([
            'request_number' => 'REQ-KUOTA-2026-001',
            'user_id' => $createdStudents[0]->id, // Ahmad Maulana
            'course_id' => 'MK02', // Kecerdasan Buatan
            'urgency' => 'Wajib Semester Ini (Prioritas Utama)',
            'notes' => 'Mata kuliah wajib semester 5. Kelas IF 7B penuh 40/40. Membutuhkan tambahan kuota.',
            'system_peminat_count' => 2,
            'system_validation_passed' => true,
            'system_validation_note' => 'Validasi Sistem SIAKAD: Mahasiswa aktif Semester 5, belum pernah mengambil MK ini, dan IPK mencukupi.',
            'kaprodi_decision' => 'pending',
            'action_type' => null,
            'kaprodi_response_note' => null,
        ]);

        QuotaRequest::create([
            'request_number' => 'REQ-KUOTA-2026-002',
            'user_id' => $createdStudents[2]->id, // Mochammad Zaenal
            'course_id' => 'MK02', // Kecerdasan Buatan
            'urgency' => 'Wajib Semester Ini (Prioritas Utama)',
            'notes' => 'Ingin mengambil kelas AI bersama teman seangkatan kelas IF 7B.',
            'system_peminat_count' => 2,
            'system_validation_passed' => true,
            'system_validation_note' => 'Validasi Sistem SIAKAD: Mahasiswa aktif Semester 5, belum pernah mengambil MK ini.',
            'kaprodi_decision' => 'pending',
            'action_type' => null,
            'kaprodi_response_note' => null,
        ]);

        // 12. Seed Rooms
        $roomsData = [
            ['id' => 'RM1', 'name' => 'Lab Komputasi Cerdas (Perluasan Ruang)', 'capacity' => 55, 'slot' => 'Selasa, 09.30 - 12.00 & Jumat, 13.00 - 15.30', 'status' => 'Tersedia'],
            ['id' => 'RM2', 'name' => 'Lab Jaringan Komputer (Perluasan Ruang)', 'capacity' => 55, 'slot' => 'Rabu, 10.00 - 12.30 & Jumat, 09.30 - 12.00', 'status' => 'Tersedia'],
            ['id' => 'RM3', 'name' => 'R. Gedung F-301 (Kelas Paralel IF 7C)', 'capacity' => 45, 'slot' => 'Jumat, 13.00 - 15.30 WIB', 'status' => 'Tersedia'],
            ['id' => 'RM4', 'name' => 'R. Gedung F-302 (Kelas Paralel IF 7C)', 'capacity' => 45, 'slot' => 'Sabtu, 08.00 - 10.30 WIB', 'status' => 'Tersedia'],
        ];
        foreach ($roomsData as $r) {
            Room::create($r);
        }

        // 13. Seed Lecturers
        $lecturersData = [
            ['name' => 'Dr. Budi Dwi Satoto ST., M.Kom.', 'field' => 'Kecerdasan Buatan & Sistem Cerdas', 'load' => '9 SKS', 'status' => 'Siap Mengampu Kelas Tambahan'],
            ['name' => 'Dr. Kautsar Sophan ST., MT.', 'field' => 'Keamanan Jaringan & Infrastruktur', 'load' => '9 SKS', 'status' => 'Siap Mengampu Kelas Tambahan'],
            ['name' => 'Dr. RIKA YUNITARINI ST., MT.', 'field' => 'Pemodelan Proses Bisnis & Enterprise Architecture', 'load' => '10 SKS', 'status' => 'Siap Mengampu'],
            ['name' => 'Fika Hastarita Rachman ST., M.Eng.', 'field' => 'Rekayasa Perangkat Lunak Lanjut', 'load' => '9 SKS', 'status' => 'Siap Mengampu'],
        ];
        foreach ($lecturersData as $l) {
            Lecturer::create($l);
        }

        // 14. Seed Broadcast Messages (Pengumuman Resmi SIAKAD)
        BroadcastMessage::create([
            'sender_id' => $dosen->id,
            'sender_name' => $dosen->name,
            'sender_role' => 'dosen_wali',
            'type' => 'jadwal_perwalian',
            'message' => 'Pengumuman Dosen Wali: Jadwal perwalian akademik KRS IF 7B telah dibuka untuk tanggal 10 - 12 November 2026. Mahasiswa dipersilakan mengisi form progress bimbingan dan menyiapkan draf KRS.',
            'is_active' => true,
        ]);

        // 15. Seed Activity Logs
        ActivityLog::create([
            'user_id' => $kaprodi->id,
            'portal' => 'kaprodi',
            'title' => 'Masa KRS Resmi Dibuka',
            'detail' => 'Kaprodi telah memverifikasi mata kuliah dan membuka masa pengisian KRS Semester Gasal 2026/2027.',
            'badge_type' => 'success',
        ]);

        ActivityLog::create([
            'user_id' => $dosen->id,
            'portal' => 'advisor',
            'title' => 'Input Jadwal Perwalian SIAKAD',
            'detail' => 'Dosen Wali menginput jadwal perwalian (10-12 Nov 2026) dan sistem otomatis mengirimkan notifikasi ke mahasiswa bimbingan.',
            'badge_type' => 'info',
        ]);

        ActivityLog::create([
            'user_id' => $createdStudents[0]->id,
            'portal' => 'student',
            'title' => 'Permintaan Kuota Penuh Terkirim',
            'detail' => 'Ahmad Maulana mengirim permintaan kuota MK02 (Kecerdasan Buatan) langsung ke SIAKAD dan lolos validasi otomatis.',
            'badge_type' => 'warning',
        ]);

        ActivityLog::create([
            'user_id' => $createdStudents[1]->id,
            'portal' => 'advisor',
            'title' => 'Pengajuan KRS Mahasiswa',
            'detail' => 'Muhammad Ali Murtadho (240411100119) mengajukan draf KRS 14 SKS ke Dosen Wali setelah dokumen bimbingan lengkap.',
            'badge_type' => 'info',
        ]);

        ActivityLog::create([
            'user_id' => $createdStudents[3]->id,
            'portal' => 'kaprodi',
            'title' => 'Pengajuan Berkas MBKM',
            'detail' => 'Deo Candra Saputra (240411100137) mengajukan program Magang Bersertifikat MSIB di PT Telkom Indonesia untuk konversi 20 SKS.',
            'badge_type' => 'proposal',
        ]);
    }
}
