<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Progress Bimbingan Akademik — {{ $user->name }} ({{ $user->identifier }})</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .sheet {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 flex flex-col items-center justify-center text-slate-800">

    <!-- Top Action Bar -->
    <div class="no-print w-full max-w-4xl mb-4 flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2 text-xs">
            <x-icon name="document-text" class="w-5 h-5 text-blue-600" />
            <span class="font-extrabold text-slate-900">Formulir Progress Bimbingan Akademik (Hard File Resmi)</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm transition flex items-center gap-1.5">
                <x-icon name="printer" class="w-4 h-4" />
                <span>Cetak / Simpan PDF (Ctrl+P)</span>
            </button>
            <button onclick="window.close()" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Official Sheet -->
    <div class="sheet w-full max-w-4xl bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-12 space-y-6">
        
        <!-- Header Kop Surat UTM -->
        <div class="border-b-2 border-slate-900 pb-4 text-center space-y-1">
            <div class="text-xs uppercase font-bold tracking-widest text-slate-600">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
            <div class="text-xl font-black tracking-tight text-slate-900">UNIVERSITAS TRUNOJOYO MADURA</div>
            <div class="text-sm font-extrabold text-slate-800">FAKULTAS TEKNIK — PROGRAM STUDI S1 TEKNIK INFORMATIKA</div>
            <div class="text-[11px] text-slate-500">Jl. Raya Telang PO BOX 2 Kamal, Bangkalan - Madura | Laman: trunojoyo.ac.id</div>
            <div class="pt-3">
                <span class="inline-block px-4 py-1 rounded bg-slate-100 text-slate-900 font-black text-xs uppercase tracking-wider border border-slate-300">
                    FORMULIR EVALUASI & PROGRESS BIMBINGAN PERWALIAN AKADEMIK
                </span>
            </div>
        </div>

        <!-- Student Details -->
        <div class="grid grid-cols-2 gap-4 text-xs text-slate-800 bg-slate-50 p-4 rounded-2xl border border-slate-200">
            <div class="space-y-1.5">
                <div class="flex"><span class="w-36 text-slate-500">Nama Mahasiswa</span><span class="font-bold">: {{ $user->name }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Nomor Induk (NIM)</span><span class="font-mono font-bold">: {{ $user->identifier }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Kelas / Semester</span><span>: {{ $user->kelas ?? 'IF 7B' }} / Semester 5</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Program Studi</span><span>: S1 Teknik Informatika</span></div>
            </div>
            <div class="space-y-1.5">
                <div class="flex"><span class="w-36 text-slate-500">IPK Kumulatif</span><span class="font-mono font-bold">: {{ number_format($user->ipk, 2) }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Total SKS Ditempuh</span><span class="font-mono font-bold">: {{ $progress->sks_completed ?? 88 }} SKS</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Dosen Pembimbing</span><span class="font-bold">: {{ optional($user->advisor)->name }}</span></div>
                <div class="flex"><span class="w-36 text-slate-500">Metode Perwalian</span><span class="font-bold uppercase">: {{ $progress->method ?? 'Offline' }}</span></div>
            </div>
        </div>

        <!-- Bagian 1: Rencana Studi Semester Berjalan -->
        <div class="space-y-2 text-xs">
            <h4 class="font-extrabold text-sm text-slate-900 border-b border-slate-200 pb-1">1. Rencana Pengambilan Beban Studi (SKS) Semester Gasal 2026/2027</h4>
            <div class="p-3.5 rounded-xl border border-slate-200 bg-white leading-relaxed">
                <p><strong>Fokus Bidang & Capaian:</strong> {{ $progress->study_plan_notes ?? 'Mengambil 18 SKS semester 5 dan mulai menyusun topik tugas akhir bidang Artificial Intelligence & Enterprise Architecture.' }}</p>
            </div>
        </div>

        <!-- Bagian 2: Evaluasi & Kendala Studi -->
        <div class="space-y-2 text-xs">
            <h4 class="font-extrabold text-sm text-slate-900 border-b border-slate-200 pb-1">2. Kendala Akademik / Catatan Evaluasi Perkuliahan</h4>
            <div class="p-3.5 rounded-xl border border-slate-200 bg-white leading-relaxed">
                <p>{{ $progress->obstacles_encountered ?? 'Tidak ada kendala akademik yang signifikan pada semester sebelumnya.' }}</p>
            </div>
        </div>

        <!-- Bagian 3: Ringkasan Mata Kuliah Diajukan dalam KRS -->
        @if($krs && $krs->courses->count() > 0)
            <div class="space-y-2 text-xs">
                <h4 class="font-extrabold text-sm text-slate-900 border-b border-slate-200 pb-1">3. Daftar Mata Kuliah yang Diajukan pada KRS (Total: {{ $krs->courses->sum('sks') }} SKS)</h4>
                <table class="w-full text-xs text-left border-collapse border border-slate-300">
                    <thead>
                        <tr class="bg-slate-100 font-bold">
                            <th class="p-2 border border-slate-300 text-center w-8">No</th>
                            <th class="p-2 border border-slate-300">Kode</th>
                            <th class="p-2 border border-slate-300">Nama Mata Kuliah</th>
                            <th class="p-2 border border-slate-300 text-center">SKS</th>
                            <th class="p-2 border border-slate-300">Jadwal & Ruang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($krs->courses as $idx => $c)
                            <tr>
                                <td class="p-2 border border-slate-300 text-center font-mono">{{ $idx + 1 }}</td>
                                <td class="p-2 border border-slate-300 font-mono font-bold">{{ $c->code }}</td>
                                <td class="p-2 border border-slate-300 font-bold">{{ $c->name }}</td>
                                <td class="p-2 border border-slate-300 text-center font-mono">{{ $c->sks }}</td>
                                <td class="p-2 border border-slate-300 text-slate-600">{{ $c->schedule }} ({{ $c->room }})</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Bagian 4: Catatan & Feedback Pembimbing Akademik -->
        <div class="space-y-2 text-xs">
            <h4 class="font-extrabold text-sm text-slate-900 border-b border-slate-200 pb-1">4. Lembar Verifikasi & Catatan Dosen Wali</h4>
            <div class="p-3.5 rounded-xl border border-slate-200 bg-white min-h-[70px] leading-relaxed">
                <p class="italic text-slate-700">{{ $progress->advisor_feedback ?? 'Dokumen progress bimbingan akademik lengkap dan telah diverifikasi dalam sesi perwalian tatap muka / daring.' }}</p>
            </div>
        </div>

        <!-- Tanda Tangan Pengesahan -->
        <div class="grid grid-cols-2 gap-8 pt-6 text-xs text-center">
            <div class="space-y-16">
                <div class="text-slate-500">Mahasiswa Pemohon,</div>
                <div>
                    <div class="font-bold underline text-slate-900">{{ $user->name }}</div>
                    <div class="text-[11px] font-mono text-slate-500">NIM. {{ $user->identifier }}</div>
                </div>
            </div>

            <div class="space-y-16">
                <div>
                    <div class="text-slate-500">Bangkalan, {{ date('d F Y') }}</div>
                    <div class="text-slate-500">Dosen Pembimbing Akademik (Wali),</div>
                </div>
                <div>
                    <div class="font-bold underline text-slate-900">{{ optional($user->advisor)->name }}</div>
                    <div class="text-[11px] font-mono text-slate-500">NIP. {{ optional($user->advisor)->identifier }}</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
