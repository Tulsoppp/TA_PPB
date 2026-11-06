# SIAKAD KRS Terintegrasi — Program Studi S1 Teknik Informatika UTM

Aplikasi Web **Sistem Informasi Akademik (SIAKAD) Kartu Rencana Studi (KRS)** yang dirancang berdasarkan dokumen **Proposal Analisis Proses Bisnis Kartu Rencana Studi (KRS) Studi Kasus Program Studi Teknik Informatika Universitas Trunodjoyo Madura (UTM)** dan pemodelan proses bisnis **BPMN_SIAKAD**.

---

## Struktur Folder Proyek
```
SIAKAD_KRS_Teknik_Informatika_UTM/
├── index.html               # Halaman utama aplikasi web (Login & 4 Portal Aktor)
├── assets/
│   ├── css/
│   │   └── style.css        # Konfigurasi gaya tampilan & mode cetak PDF
│   └── js/
│       └── app.js           # Logika sistem, autentikasi email, & sinkronisasi antar-aktor
└── README.md                # Dokumentasi panduan penggunaan & daftar akun login resmi
```

---

## Cara Menjalankan Aplikasi
1. Ekstrak file **`SIAKAD_KRS_Teknik_Informatika_UTM.zip`** ke dalam satu folder di komputer/laptop Anda.
2. Buka file **`index.html`** menggunakan browser modern (Google Chrome, Microsoft Edge, Mozilla Firefox, atau Safari).
3. Pastikan koneksi internet aktif saat pertama kali membuka agar pustaka tampilan *Tailwind CSS* dan *Google Fonts* termuat dengan sempurna.

---

## Daftar Akun Resmi Terdaftar (Untuk Login)

Sistem menggunakan **Single Sign-On (SSO) Berbasis Email Resmi Terdaftar**. Halaman yang ditampilkan setelah login akan otomatis menyesuaikan peran pemilik email:

### 1. Akun Mahasiswa (Kelas IF 7B — Semester 5)
* **Kata Sandi Mahasiswa**: `utm2026`
  1. **Ahmad Maulana Ishaq** (`240411100157`)
     * Email: `240411100157@student.trunojoyo.ac.id`
  2. **Muhammad Ali Murtadho** (`240411100119`)
     * Email: `240411100119@student.trunojoyo.ac.id`
  3. **Mochammad Zaenal Abidin** (`240411100131`)
     * Email: `240411100131@student.trunojoyo.ac.id`
  4. **Deo Candra Saputra** (`240411100137`)
     * Email: `240411100137@student.trunojoyo.ac.id`
  5. **M. Fatihul Umam** (`240411100209`)
     * Email: `240411100209@student.trunojoyo.ac.id`

### 2. Akun Dosen Wali / Pembimbing Akademik
* **Nama Dosen**: Rika Yunitarini ST., MT. (`NIP. 198306072006042001`)
* **Email**: `rika.yunitarini@trunojoyo.ac.id`
* **Kata Sandi**: `dosen2026`

### 3. Akun Pengurus Himpunan Mahasiswa (Himatif)
* **Nama Unit**: Divisi Advokasi Himatif UTM
* **Email**: `advokasi.himatif@trunojoyo.ac.id`
* **Kata Sandi**: `himatif2026`

### 4. Akun Admin Program Studi (Tata Usaha / Akademik)
* **Nama Unit**: Admin Akademik Prodi S1 Teknik Informatika
* **Email**: `admin.informatika@trunojoyo.ac.id`
* **Kata Sandi**: `admin2026`

---

## Panduan Pengujian Skenario Proses Bisnis (Sesuai Proposal & BPMN)

### A. Skenario Normal (Kuota Kelas Tersedia)
1. Login menggunakan akun **Mahasiswa** (`240411100157@student.trunojoyo.ac.id`).
2. Pilih mata kuliah yang kuotanya tersedia, lalu klik **"Ajukan KRS ke Dosen Wali"**.
3. Logout, lalu login menggunakan akun **Dosen Wali** (`rika.yunitarini@trunojoyo.ac.id`).
4. Pilih mahasiswa tersebut di antrean validasi, periksa mata kuliah yang diambil, lalu klik **"Setujui & Sahkan KRS Mahasiswa (ACC)"**.
5. Logout dan login kembali sebagai **Mahasiswa** untuk mencetak **Lembar KRS Resmi (PDF)**.

### B. Skenario Alternatif (Kelas Penuh & Eskalasi melalui Himatif ke Admin Prodi)
1. Login sebagai **Mahasiswa**, lihat mata kuliah wajib yang berstatus **PENUH (`40/40`)** seperti *Kecerdasan Buatan* atau *Keamanan Informasi & Jaringan*.
2. Klik tombol **"Lapor Kuota ke Himatif"** dan kirimkan formulir keluhan kuota penuh.
3. Logout, lalu login sebagai **Himatif** (`advokasi.himatif@trunojoyo.ac.id`).
4. Verifikasi laporan mahasiswa yang masuk, lalu klik **"Rekap & Serahkan Usulan ke Admin Prodi"**.
5. Logout, lalu login sebagai **Admin Prodi** (`admin.informatika@trunojoyo.ac.id`).
6. Buka menu **Analisis Ruang & Dosen + Eksekusi Kelas**, pilih apakah ingin **Tambah Kapasitas Kuota (+15 kursi)** atau **Buka Kelas Paralel Baru (IF 7C)**, lalu klik **"Eksekusi Pembukaan Kelas / Kuota & Informasikan ke Himatif"**.
7. Logout, login kembali sebagai **Himatif** untuk menyebarkan pengumuman pembukaan kelas ke seluruh mahasiswa.
8. Login kembali sebagai **Mahasiswa**, ambil kelas yang baru dibuka, ajukan KRS ke **Dosen Wali**, dan lakukan validasi hingga selesai.
