<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS_{{ $user->identifier }}_{{ str_replace(' ', '_', $user->name) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 30px;
            background: #fff;
            font-size: 11px;
        }
        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .kop h3 {
            margin: 2px 0;
            font-size: 13px;
            font-weight: 700;
        }
        .kop p {
            margin: 0;
            font-size: 10px;
            color: #475569;
        }
        .badge-title {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 15px;
            font-weight: 800;
            font-size: 12px;
            border: 1px solid #000;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .meta-table td {
            padding: 3px 6px;
            vertical-align: top;
        }
        .krs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11px;
        }
        .krs-table th, .krs-table td {
            border: 1px solid #94a3b8;
            padding: 6px 8px;
        }
        .krs-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: left;
        }
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            text-align: center;
            font-size: 10px;
            margin-top: 30px;
            gap: 15px;
        }
        .stamp-box {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stamp-verified {
            border: 2px solid #059669;
            color: #059669;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 800;
            font-size: 8px;
            text-transform: uppercase;
            transform: rotate(-3deg);
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; padding: 12px; background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
        <div><strong>Pratinjau Cetak Lembar KRS Resmi:</strong> Gunakan menu Cetak (Ctrl+P / Command+P) untuk menyimpan sebagai PDF.</div>
        <button onclick="window.print()" style="padding: 6px 16px; background: #0369a1; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Cetak Sekarang</button>
    </div>

    <!-- Kop Surat Resmi -->
    <div class="kop">
        <p style="text-transform: uppercase; letter-spacing: 1px;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</p>
        <h2>UNIVERSITAS TRUNOJOYO MADURA</h2>
        <h3>FAKULTAS TEKNIK — PROGRAM STUDI S1 TEKNIK INFORMATIKA</h3>
        <p>Jl. Raya Telang PO BOX 2 Kamal, Bangkalan 69162 — Madura, Jawa Timur | Telp. (031) 3011146</p>
        <div class="badge-title">KARTU RENCANA STUDI (KRS)</div>
    </div>

    <!-- Identitas Mahasiswa -->
    <table class="meta-table">
        <tr>
            <td style="width: 15%;">Nama Mahasiswa</td>
            <td style="width: 35%;">: <strong>{{ $user->name }}</strong></td>
            <td style="width: 15%;">Tahun Akademik</td>
            <td style="width: 35%;">: <strong>2026/2027 Ganjil</strong></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>: <strong>{{ $user->identifier }}</strong></td>
            <td>Semester / Kelas</td>
            <td>: <strong>{{ $user->semester ?? 5 }} / {{ $user->kelas ?? 'IF 7B' }}</strong></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>: <strong>S1 Teknik Informatika</strong></td>
            <td>Dosen Pembimbing</td>
            <td>: <strong>{{ optional($advisor)->name }}</strong></td>
        </tr>
        <tr>
            <td>Jalur KRS</td>
            <td>: <strong>{{ strtoupper($krs->path ?? 'REGULER') }}</strong></td>
            <td>Status Pengesahan</td>
            <td>: <strong>{{ strtoupper($krs->status) }} ({{ $krs->status === 'approved' ? 'VALID & SAH (ACC)' : 'BELUM DI-ACC' }})</strong></td>
        </tr>
    </table>

    <!-- Tabel Pengambilan Mata Kuliah -->
    <table class="krs-table">
        <thead>
            <tr>
                <th style="text-align: center; width: 5%;">No</th>
                <th style="width: 12%;">Kode MK</th>
                <th style="width: 35%;">Nama Mata Kuliah</th>
                <th style="text-align: center; width: 8%;">Kelas</th>
                <th style="text-align: center; width: 8%;">SKS</th>
                <th style="width: 32%;">Dosen Pengampu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($krs->courses as $idx => $mk)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $mk->code }}</td>
                    <td><strong>{{ $mk->name }}</strong></td>
                    <td style="text-align: center;">{{ $mk->kelas }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $mk->sks }}</td>
                    <td>{{ $mk->lecturer }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f8fafc; font-weight: bold;">
                <td colspan="4" style="text-align: right;">TOTAL SKS DIAMBIL:</td>
                <td style="text-align: center; font-size: 12px; color: #1e3a8a;">{{ $krs->courses->sum('sks') }} SKS</td>
                <td style="font-size: 10px; color: #64748b;">Maksimal Hak Beban: {{ $user->max_sks }} SKS</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan 3 Kolom Resmi -->
    <div class="signature-grid">
        <div>
            <div>Bangkalan, {{ date('d F Y') }}</div>
            <div style="font-weight: bold; margin-top: 4px;">Mahasiswa Pemohon,</div>
            <div class="stamp-box">
                <span style="font-style: italic; color: #94a3b8;">[Tanda Tangan Digital]</span>
            </div>
            <div style="font-weight: 800; text-decoration: underline;">{{ $user->name }}</div>
            <div style="font-family: monospace; color: #475569;">NIM. {{ $user->identifier }}</div>
        </div>

        <div>
            <div>Menyetujui,</div>
            <div style="font-weight: bold; margin-top: 4px;">Dosen Pembimbing Akademik (Wali)</div>
            <div class="stamp-box">
                @if($krs->status === 'approved')
                    <div class="stamp-verified">
                        TERVERIFIKASI & DISAHKAN (ACC)<br>
                        UNIVERSITAS TRUNOJOYO MADURA<br>
                        {{ optional($krs->validated_at)->format('Y-m-d H:i') }} WIB
                    </div>
                @else
                    <span style="color: #d97706; font-style: italic;">[Belum Di-ACC Dosen Wali]</span>
                @endif
            </div>
            <div style="font-weight: 800; text-decoration: underline;">{{ optional($advisor)->name }}</div>
            <div style="font-family: monospace; color: #475569;">NIP. {{ optional($advisor)->identifier }}</div>
        </div>

        <div>
            <div>Mengetahui,</div>
            <div style="font-weight: bold; margin-top: 4px;">Koordinator Program Studi S1 TI</div>
            <div class="stamp-box">
                <div class="stamp-verified" style="border-color: #2563eb; color: #2563eb;">
                    TERDAFTAR RESMI<br>
                    SIAKAD UTM TEKNIK INFORMATIKA<br>
                    SEMESTER GASAL 2026/2027
                </div>
            </div>
            <div style="font-weight: 800; text-decoration: underline;">Dr. Arik Kurniawati, S.Kom., M.T.</div>
            <div style="font-family: monospace; color: #475569;">NIP. 197905142005012002</div>
        </div>
    </div>

</body>
</html>
