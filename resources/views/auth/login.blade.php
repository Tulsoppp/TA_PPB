<?php
$kaprodiUser = $users->first(fn($u) => in_array($u->role, ['kaprodi', 'admin']));
$dosenUser = $users->first(fn($u) => $u->role === 'dosen_wali');
$students = $users->filter(fn($u) => $u->role === 'mahasiswa');
$himatifUser = $users->first(fn($u) => $u->role === 'himatif');
?>
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk SIAKAD KRS — S1 Teknik Informatika UTM</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #dfe5f0; /* Palet kanvas reference.webp */
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="min-h-screen p-4 sm:p-8 flex items-center justify-center text-slate-800 selection:bg-blue-600 selection:text-white">

    <!-- Main Floating Card matching reference.webp aesthetic -->
    <div class="max-w-5xl w-full bg-white rounded-[28px] shadow-2xl shadow-slate-300/60 border border-slate-200/80 overflow-hidden grid grid-cols-1 md:grid-cols-12">
        
        <!-- Left Side: Campus Branding & Scenario Description -->
        <div class="md:col-span-5 bg-slate-50/80 p-8 border-b md:border-b-0 md:border-r border-slate-100 flex flex-col justify-between space-y-6">
            <div class="space-y-6">
                <!-- University Logo Header -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/30">
                        <x-icon name="academic-cap" class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <h1 class="text-base font-black text-slate-900 tracking-tight">SIAKAD KRS Terintegrasi</h1>
                        <p class="text-[11px] font-semibold text-slate-400">Universitas Trunojoyo Madura</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold">
                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-blue-600" />
                        <span>Sesuai BPMN To-Be (BPMN_SIAKAD_FIX)</span>
                    </span>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight leading-snug">
                        Sistem Informasi Pengambilan KRS & Penanganan Kapasitas Kelas
                    </h2>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Implementasi terintegrasi proses bisnis pengisian KRS S1 Teknik Informatika UTM berpedoman pada <strong>Laporan TA</strong> dan diagram proses bisnis <strong>BPMN To-Be</strong>.
                    </p>
                </div>

                <!-- 4 Lanes Process Summary -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center gap-2.5 p-2.5 rounded-2xl bg-white border border-slate-100 text-xs">
                        <div class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <x-icon name="academic-cap" class="w-4 h-4" />
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">1. Mahasiswa (IF 7B)</div>
                            <div class="text-[10px] text-slate-400">Jalur Reguler & MBKM, Permintaan Kuota SIAKAD, Form Bimbingan</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-2.5 rounded-2xl bg-white border border-slate-100 text-xs">
                        <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <x-icon name="clipboard-check" class="w-4 h-4" />
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">2. Dosen Wali (Dr. Rika Yunitarini)</div>
                            <div class="text-[10px] text-slate-400">Jadwal Perwalian SIAKAD, Periksa Form, ACC KRS Final</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-2.5 rounded-2xl bg-white border border-slate-100 text-xs">
                        <div class="w-7 h-7 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <x-icon name="building-office" class="w-4 h-4" />
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">3. Kaprodi (Dr. Arik Kurniawati)</div>
                            <div class="text-[10px] text-slate-400">Buka/Tutup Masa KRS, Evaluasi Kuota (+15/Paralel), Validasi MBKM</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 p-2.5 rounded-2xl bg-white border border-slate-100 text-xs">
                        <div class="w-7 h-7 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                            <x-icon name="shield-check" class="w-4 h-4" />
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">4. Sistem SIAKAD (Otomatis)</div>
                            <div class="text-[10px] text-slate-400">Verifikasi Pembayaran UKT, Cek Bentrok/Prasyarat/Kuota, Notifikasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Meta -->
            <div class="pt-4 border-t border-slate-200/60 text-[11px] text-slate-400">
                <span>Mata Kuliah: Pemodelan Proses Bisnis (IF 7B) — 2026</span>
            </div>
        </div>

        <!-- Right Side: Standard Login Form + 1-Click Quick Demo Login -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-between space-y-6">
            
            <div class="space-y-6">
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Masuk ke Sistem</h3>
                    <p class="text-xs text-slate-500 mt-1">Gunakan akun resmi Universitas Trunojoyo Madura atau gunakan tombol 1-Klik Simulasi di bawah.</p>
                </div>

                <!-- Standard Email & Password Form -->
                <form action="{{ route('login.post') }}" method="POST" id="mainLoginForm" class="space-y-4">
                    @csrf
                    <div>
                        <label for="inputEmail" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email Resmi (Username)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <x-icon name="envelope" class="w-4 h-4" />
                            </div>
                            <input 
                                type="email" 
                                id="inputEmail" 
                                name="email" 
                                required 
                                value="{{ old('email', '240411100157@student.trunojoyo.ac.id') }}" 
                                placeholder="nim@student.trunojoyo.ac.id" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                            />
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="inputPassword" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi (Password)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <x-icon name="key" class="w-4 h-4" />
                            </div>
                            <input 
                                type="password" 
                                id="inputPassword" 
                                name="password" 
                                required 
                                value="utm2026" 
                                placeholder="••••••••" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-slate-200 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                            />
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                        <x-icon name="arrow-right-on-rectangle" class="w-4 h-4 text-white" />
                        <span>Masuk ke SIAKAD</span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center justify-center">
                    <div class="border-t border-slate-100 w-full"></div>
                    <span class="bg-white px-3 text-[10px] uppercase font-extrabold text-slate-400 tracking-wider absolute">
                        Pilih Cepat Peran Aktor (1-Klik Demo)
                    </span>
                </div>

                <!-- 1-Click Role Switcher Grid -->
                <div class="space-y-2.5">
                    
                    <!-- Kaprodi Button -->
                    @if($kaprodiUser)
                        <form action="{{ route('login.quick') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $kaprodiUser->email }}">
                            <button type="submit" class="w-full text-left p-3 rounded-2xl border border-purple-200 bg-purple-50/60 hover:bg-purple-100/80 transition flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                        KP
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-purple-950 flex items-center gap-1.5">
                                            <span>{{ $kaprodiUser->name }}</span>
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-purple-200 text-purple-900">KAPRODI</span>
                                        </div>
                                        <div class="text-[10px] text-purple-700">Masa KRS, Evaluasi Kuota Kelas, & Validasi MBKM</div>
                                    </div>
                                </div>
                                <x-icon name="chevron-right" class="w-4 h-4 text-purple-400 group-hover:translate-x-1 transition" />
                            </button>
                        </form>
                    @endif

                    <!-- Dosen Wali Button -->
                    @if($dosenUser)
                        <form action="{{ route('login.quick') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $dosenUser->email }}">
                            <button type="submit" class="w-full text-left p-3 rounded-2xl border border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100/80 transition flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                        RY
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-emerald-950 flex items-center gap-1.5">
                                            <span>{{ $dosenUser->name }}</span>
                                            <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-emerald-200 text-emerald-900">DOSEN WALI</span>
                                        </div>
                                        <div class="text-[10px] text-emerald-700">Penjadwalan Perwalian SIAKAD, Cek Form, & ACC KRS</div>
                                    </div>
                                </div>
                                <x-icon name="chevron-right" class="w-4 h-4 text-emerald-400 group-hover:translate-x-1 transition" />
                            </button>
                        </form>
                    @endif

                    <!-- Mahasiswa Selector Accordion / Pills -->
                    <div class="pt-1">
                        <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">
                            Pilih Akun Mahasiswa (IF 7B - Semester 5):
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($students as $st)
                                <form action="{{ route('login.quick') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="email" value="{{ $st->email }}">
                                    <button type="submit" class="w-full text-left p-2.5 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50/50 transition group flex items-center justify-between">
                                        <div class="truncate">
                                            <div class="font-bold text-xs text-slate-800 group-hover:text-blue-700 truncate">{{ $st->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">{{ $st->identifier }}</div>
                                        </div>
                                        @if($st->identifier == '240411100157')
                                            <span class="text-[8px] font-bold px-1 rounded bg-amber-100 text-amber-900 shrink-0">Kuota AI</span>
                                        @elseif($st->identifier == '240411100119')
                                            <span class="text-[8px] font-bold px-1 rounded bg-emerald-100 text-emerald-900 shrink-0">Siap ACC</span>
                                        @elseif($st->identifier == '240411100137')
                                            <span class="text-[8px] font-bold px-1 rounded bg-purple-100 text-purple-900 shrink-0">Jalur MBKM</span>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            <!-- Simulation Reset Notification -->
            <div class="pt-2 text-center text-[11px] text-slate-400">
                Basis data SQLite siap uji skenario BPMN To-Be. Kata sandi mahasiswa: <code class="font-mono font-bold text-slate-600">utm2026</code>.
            </div>

        </div>

    </div>

</body>
</html>
