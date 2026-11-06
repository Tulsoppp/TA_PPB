# SIAKAD KRS Terintegrasi — S1 Teknik Informatika UTM (BPMN To-Be)

Aplikasi Web **Sistem Informasi Akademik (SIAKAD) Kartu Rencana Studi (KRS)** yang dikembangkan menggunakan **Laravel 13**, **SQLite**, dan **Tailwind CSS**. Sistem ini mengimplementasikan pemodelan proses bisnis akademik terintegrasi berpedoman penuh pada dokumen analisis **[LAPORAN_TA.pdf]** dan diagram proses bisnis **[BPMN_SIAKAD_FIX.png]**.

---

## Keselarasan dengan Laporan TA & BPMN To-Be

Sesuai dengan **Bab 3 Laporan Tugas Akhir** dan diagram **BPMN To-Be (BPMN_SIAKAD_FIX.png)**, sistem ini memodelkan kolaborasi dalam 1 Pool dengan **4 Lane Aktor Utama**:

1. **Lane Mahasiswa**:
   - Pemicu awal: Setelah mahasiswa melakukan pembayaran UKT (status terverifikasi lunas).
   - Login SIAKAD & pemilihan jalur studi (**Reguler** atau **MBKM**).
   - Pengisian KRS Reguler: Pemilihan paket semester, penambahan mata kuliah, pengecekan keranjang SKS.
   - Penanganan kegagalan otomatis: Peringatan jadwal bertabrakan (bentrok), belum memenuhi prasyarat, dan mata kuliah penuh.
   - Mengirim permintaan penambahan kuota langsung ke SIAKAD (**tanpa perantara spreadsheet manual/Himatif**).
   - Persiapan perwalian: Pengisian Form Progress Bimbingan Akademik digital & pencetakan dokumen Hard File resmi.
   - Pelaksanaan perwalian tatap muka (offline) maupun daring (online).
   - Mengunduh / mencetak lembar KRS resmi berstempel pengesahan digital setelah disetujui (ACC).

2. **Lane Dosen Wali (Dr. Rika Yunitarini ST., MT.)**:
   - Menentukan jadwal dan metode perwalian (offline / online / both) lalu menginputnya langsung ke SIAKAD.
   - Sistem secara otomatis menyiarkan notifikasi resmi ke seluruh mahasiswa bimbingan.
   - Memeriksa kelengkapan berkas bimbingan mahasiswa (Hard File & Digital); jika belum lengkap meminta perbaikan, jika lengkap dokumen disimpan sebagai arsip resmi.
   - Meninjau draf KRS mahasiswa (daftar mata kuliah, total SKS, jadwal).
   - Memberikan arahan / wejangan bimbingan atau meminta revisi perbaikan.
   - Memberikan pengesahan (**ACC KRS Final**).

3. **Lane Kaprodi (Dr. Arik Kurniawati, S.Kom., M.T.)**:
   - Menggantikan peran Admin Tata Usaha sebelumnya (*Subbab 3.1: Kaprodi memiliki kewenangan mengelola masa KRS dan kuota kelas*).
   - Sub-process **Memasukkan Mata Kuliah**: Menambah mata kuliah, mengatur jadwal, ruang kuliah/laboratorium, dosen pengampu, dan kuota kursi.
   - Mengendalikan pembukaan dan penutupan masa pengisian KRS.
   - Mengevaluasi permintaan kuota penuh mahasiswa yang telah divalidasi sistem:
     - Pedoman < 10 mahasiswa: Menambah kuota pada kelas yang sudah ada (+15 kursi).
     - Pedoman ≥ 10 mahasiswa: Membuka kelas paralel baru (contoh: IF 7C).
     - Menolak dengan alasan kapasitas laboratorium / batas beban mengajar dosen.
   - Memvalidasi berkas program MBKM (LoA/Surat Penerimaan) dan mengesahkan paket 20 SKS konversi mata kuliah langsung ke KRS mahasiswa.

4. **Lane Sistem SIAKAD (Otomatis)**:
   - Verifikasi status pembayaran UKT sebelum KRS dapat diakses.
   - Validasi pembukaan masa KRS.
   - Validasi integritas KRS: Batas hak SKS, irisan jadwal bentrok, dan prasyarat mata kuliah.
   - BPI Automate: Pengecekan riwayat akademik mahasiswa pemohon kuota untuk memastikan kebutuhan riil dan mencegah pendaftaran ganda/hanya iseng pindah kelas.
   - Pengiriman notifikasi broadcast jadwal perwalian dan pembukaan kuota kelas secara terpusat.

---

## Penerapan Business Process Improvement (BPI) — Subbab 3.5

| Prinsip BPI | Kondisi As-Is (Lama) | Perbaikan To-Be pada Sistem Web |
|:---|:---|:---|
| **Eliminate** | Mahasiswa mengisi spreadsheet aspirasi manual ke Himatif, lalu Himatif merekap ke Admin. | Penampungan Himatif dihapus. Mahasiswa mengirim permintaan kuota langsung ke SIAKAD. |
| **Automate** | Rekapitulasi manual rawan diisi mahasiswa yang hanya ingin pindah kelas atau manipulasi data. | Sistem otomatis memvalidasi riwayat KRS mahasiswa & menghitung akumulasi peminat valid. |
| **Integrate** | Jadwal perwalian disebar lewat spreadsheet link & pesan broadcast grup WhatsApp. | Dosen Wali input jadwal langsung di SIAKAD, notifikasi terintegrasi tampil di portal mahasiswa. |
| **Simplify** | Dokumen bimbingan tercecer di dua media dan tidak terintegrasi dengan persetujuan KRS. | Form bimbingan digital terintegrasi di SIAKAD dengan cetak hard file berformat kop resmi UTM dan arsip digital. |

---

## Desain Antarmuka Modern (Sesuai `reference.webp` & `prompt.md`)

- **Palet Warna**: Mengikuti kanvas abu kebiruan lembut (`#dfe5f0`), kontainer kartu rounded luas (`rounded-[28px]`, `shadow-2xl`), dan aksen warna fungsional (*Blue, Purple, Emerald, Amber*).
- **Weekly Schedule Timetable**: Kalender visual mingguan (Senin s/d Minggu) dengan grid waktu slot kuliah dan kartu pill berwarna pastel untuk setiap mata kuliah yang diambil.
- **Bebas Emoji**: Sepenuhnya menggunakan ikon SVG resmi **Heroicons** dan dialog modal **SweetAlert2**.
- **Viewer BPMN Interaktif**: Modal penampil diagram resolusi tinggi **`BPMN_SIAKAD_FIX.png`** (15.514 × 2.936 piksel) yang dapat digeser (pan/scroll) untuk demonstrasi alur.

---

## Kredensial Login Pengguna (User Login Table)

| No | Peran (Aktor BPMN) | Nama Akun | Identitas (NIM / NIP) | Email Resmi (Username) | Kata Sandi | Skenario Pengujian Utama |
|:--:|:---|:---|:---|:---|:---|:---|
| 1 | **Kaprodi** | Dr. Arik Kurniawati, S.Kom., M.T. | `197905142005012002` | `kaprodi.informatika@trunojoyo.ac.id` | `kaprodi2026` | Buka/Tutup Masa KRS, Evaluasi Kuota (+15/Paralel), Validasi MBKM |
| 2 | **Dosen Wali** | Dr. RIKA YUNITARINI ST., MT. | `198306072006042001` | `rika.yunitarini@trunojoyo.ac.id` | `dosen2026` | Input Jadwal Perwalian SIAKAD, Cek Form Bimbingan, ACC KRS Final |
| 3 | **Mahasiswa** | Ahmad Maulana Ishaq | `240411100157` | `240411100157@student.trunojoyo.ac.id` | `utm2026` | Skenario Kuota AI Penuh & Permintaan Kuota SIAKAD (BPI Automate) |
| 4 | **Mahasiswa** | Muhammad Ali Murtadho | `240411100119` | `240411100119@student.trunojoyo.ac.id` | `utm2026` | Skenario Draf Siap di-ACC Dosen Wali & Cetak KRS Resmi |
| 5 | **Mahasiswa** | Deo Candra Saputra | `240411100137` | `240411100137@student.trunojoyo.ac.id` | `utm2026` | Skenario Jalur MBKM (Magang Telkom & Konversi 20 SKS) |
| 6 | **Mahasiswa** | M. Fatihul Umam | `240411100209` | `240411100209@student.trunojoyo.ac.id` | `utm2026` | Skenario Uji Prasyarat (Prerequisite Unmet pada MK08) |
| 7 | **Mahasiswa** | Mochammad Zaenal Abidin | `240411100131` | `240411100131@student.trunojoyo.ac.id` | `utm2026` | Skenario Pemilihan Mata Kuliah Reguler |

> **Catatan Pengujian:** Pada halaman login (`/login`), tersedia tombol **1-Klik Quick Login** untuk berpindah aktor secara instan tanpa perlu mengetik kata sandi manual.

---

## Cara Menjalankan & Menguji Aplikasi

### 1. Jalankan Server Web
```bash
php artisan serve
```
Akses di peramban web: **`http://localhost:8000`**

### 2. Jalankan Seluruh Uji Otomatis (Feature & Unit Tests)
```bash
php artisan test
```
*Seluruh 12 feature & unit test (73 assertions) mencakup skenario end-to-end alur BPMN To-Be.*

### 3. Reset Basis Data ke Kondisi Awal
Klik tombol **"Reset Data"** di navigasi kiri aplikasi atau jalankan di terminal:
```bash
php artisan migrate:fresh --seed
```
