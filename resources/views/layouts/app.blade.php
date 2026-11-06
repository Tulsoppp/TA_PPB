<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIAKAD KRS — S1 Teknik Informatika UTM' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
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
            background-color: #dfe5f0; /* Palet kanvas abu kebiruan reference.webp */
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .printable-card {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-800 antialiased p-0 sm:p-4 lg:p-6 flex flex-col justify-center items-center selection:bg-blue-600 selection:text-white">

    <!-- Outer Main Application Card (Reference.webp Style) -->
    <div class="printable-card w-full max-w-[1480px] bg-white rounded-none sm:rounded-[28px] shadow-2xl shadow-slate-300/60 border border-slate-200/80 flex flex-col min-h-[92vh] overflow-hidden">
        
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden">

            <!-- SIDEBAR (Left Navigation matching reference.webp) -->
            <aside class="no-print w-full md:w-64 bg-white border-b md:border-b-0 md:border-r border-slate-100 flex flex-col justify-between shrink-0 p-5 z-30">
                <div class="space-y-6">
                    <!-- Brand / University Logo Header -->
                    <div class="flex items-center justify-between pb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black shadow-md shadow-blue-500/30">
                                <x-icon name="academic-cap" class="w-6 h-6 text-white" />
                            </div>
                            <div>
                                <span class="font-black text-slate-900 tracking-tight text-base block">SIAKAD</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block -mt-1">UTM MADURA</span>
                            </div>
                        </div>
                        <button type="button" onclick="showBpmnModal()" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" title="Lihat Diagram Alur BPMN To-Be">
                            <x-icon name="sparkles" class="w-4 h-4" />
                        </button>
                    </div>

                    <!-- Navigation Links Group -->
                    <nav class="space-y-1.5" id="mainSidebarNav">
                        @php
                            $role = Auth::user()->role ?? 'mahasiswa';
                        @endphp

                        @if($role === 'mahasiswa')
                            <button type="button" onclick="switchMainTab('dashboard')" id="navBtn_dashboard" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition bg-blue-50 text-blue-600">
                                <div class="flex items-center gap-3">
                                    <x-icon name="squares-2x2" class="w-5 h-5 text-blue-600" />
                                    <span>Dashboard & Jadwal</span>
                                </div>
                            </button>

                            <button type="button" onclick="switchMainTab('krs')" id="navBtn_krs" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="clipboard-check" class="w-5 h-5 text-slate-400" />
                                    <span>KRS Reguler</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800" id="sidebarCartBadge">
                                    {{ isset($krs) ? $krs->courses->count() : 0 }}
                                </span>
                            </button>

                            <button type="button" onclick="switchMainTab('mbkm')" id="navBtn_mbkm" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="sparkles" class="w-5 h-5 text-slate-400" />
                                    <span>Jalur MBKM</span>
                                </div>
                                @if(isset($mbkm) && $mbkm)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $mbkm->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $mbkm->status === 'approved' ? '20 SKS' : 'Diajukan' }}
                                    </span>
                                @endif
                            </button>

                            <button type="button" onclick="switchMainTab('perwalian')" id="navBtn_perwalian" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="users" class="w-5 h-5 text-slate-400" />
                                    <span>Perwalian & Form</span>
                                </div>
                                @if(isset($advisingProgress) && $advisingProgress->document_status === 'verified_archived')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">Arsip</span>
                                @endif
                            </button>

                            <button type="button" onclick="switchMainTab('kuota')" id="navBtn_kuota" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="chat-bubble" class="w-5 h-5 text-slate-400" />
                                    <span>Permintaan Kuota</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900">
                                    {{ isset($quotaRequests) ? $quotaRequests->count() : 0 }}
                                </span>
                            </button>

                            <button type="button" onclick="switchMainTab('cetak')" id="navBtn_cetak" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="printer" class="w-5 h-5 text-slate-400" />
                                    <span>Cetak Lembar KRS</span>
                                </div>
                            </button>
                        @elseif($role === 'dosen_wali')
                            <button type="button" onclick="switchAdvisorTab('queue')" id="dsnNavBtn_queue" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition bg-blue-50 text-blue-600">
                                <div class="flex items-center gap-3">
                                    <x-icon name="clipboard-check" class="w-5 h-5 text-blue-600" />
                                    <span>Antrean Validasi (ACC)</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-slate-950">
                                    {{ $stats['pending'] ?? 0 }}
                                </span>
                            </button>

                            <button type="button" onclick="switchAdvisorTab('bimbingan')" id="dsnNavBtn_bimbingan" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="document-text" class="w-5 h-5 text-slate-400" />
                                    <span>Pemeriksaan Form Bimbingan</span>
                                </div>
                            </button>

                            <button type="button" onclick="switchAdvisorTab('schedule')" id="dsnNavBtn_schedule" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="calendar" class="w-5 h-5 text-slate-400" />
                                    <span>Jadwal Perwalian SIAKAD</span>
                                </div>
                            </button>

                            <button type="button" onclick="switchAdvisorTab('monitoring')" id="dsnNavBtn_monitoring" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="chart-bar" class="w-5 h-5 text-slate-400" />
                                    <span>Monitoring Beban & Kuota</span>
                                </div>
                            </button>
                        @elseif($role === 'kaprodi' || $role === 'admin')
                            <button type="button" onclick="switchKaprodiTab('quota')" id="kpNavBtn_quota" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition bg-purple-50 text-purple-700">
                                <div class="flex items-center gap-3">
                                    <x-icon name="chat-bubble" class="w-5 h-5 text-purple-600" />
                                    <span>Evaluasi Kuota Penuh</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-slate-950">
                                    {{ $stats['pending_quota_requests'] ?? 0 }}
                                </span>
                            </button>

                            <button type="button" onclick="switchKaprodiTab('mbkm')" id="kpNavBtn_mbkm" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="sparkles" class="w-5 h-5 text-slate-400" />
                                    <span>Validasi Jalur MBKM</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800">
                                    {{ $stats['pending_mbkm'] ?? 0 }}
                                </span>
                            </button>

                            <button type="button" onclick="switchKaprodiTab('krs_management')" id="kpNavBtn_krs_management" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="book-open" class="w-5 h-5 text-slate-400" />
                                    <span>Masa KRS & Mata Kuliah</span>
                                </div>
                            </button>

                            <button type="button" onclick="switchKaprodiTab('analysis')" id="kpNavBtn_analysis" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900">
                                <div class="flex items-center gap-3">
                                    <x-icon name="building-office" class="w-5 h-5 text-slate-400" />
                                    <span>Analisis Ruang & Dosen</span>
                                </div>
                            </button>
                        @elseif($role === 'himatif')
                            <button type="button" class="sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition bg-blue-50 text-blue-600">
                                <div class="flex items-center gap-3">
                                    <x-icon name="sparkles" class="w-5 h-5 text-blue-600" />
                                    <span>Transformasi To-Be SIAKAD</span>
                                </div>
                            </button>
                        @endif
                    </nav>
                </div>

                <!-- Bottom Segmented Controls: System Tools & Reset Simulation -->
                <div class="pt-6 border-t border-slate-100 space-y-3">
                    <button type="button" onclick="showBpmnModal()" class="w-full py-2 px-3 rounded-2xl bg-blue-50 hover:bg-blue-100/80 text-blue-700 font-extrabold text-xs transition flex items-center justify-center gap-2 border border-blue-200">
                        <x-icon name="sparkles" class="w-4 h-4 text-blue-600" />
                        <span>Alur BPMN To-Be</span>
                    </button>

                    <!-- Reset & Logout Quick Actions -->
                    <div class="flex items-center justify-between px-1 text-xs">
                        <button type="button" onclick="confirmResetSimulation()" class="text-slate-400 hover:text-rose-600 font-semibold transition flex items-center gap-1" title="Reset basis data simulasi">
                            <x-icon name="arrow-path" class="w-3.5 h-3.5" />
                            <span>Reset Data</span>
                        </button>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-slate-800 font-semibold transition flex items-center gap-1" title="Keluar">
                                <x-icon name="arrow-right-on-rectangle" class="w-3.5 h-3.5" />
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- MAIN WORKSPACE (Top Header + Dashboard Content) -->
            <main class="flex-1 flex flex-col bg-white overflow-y-auto">
                
                <!-- TOP NAVBAR (Matching reference.webp: User pill, Search Bar, Notification, Settings) -->
                <header class="no-print border-b border-slate-100 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 sticky top-0 bg-white/95 backdrop-blur z-20">
                    
                    <!-- Left: User Profile Dropdown & Role Indicator -->
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="w-11 h-11 rounded-full bg-slate-100 text-blue-700 font-extrabold text-sm flex items-center justify-center border-2 border-slate-200 overflow-hidden shrink-0">
                            {{ Auth::user()->initials ?? substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div class="relative group">
                            <button type="button" class="text-left flex items-center gap-2 group-hover:opacity-80 transition">
                                <div>
                                    <div class="font-extrabold text-slate-900 text-sm leading-tight flex items-center gap-1.5">
                                        <span>{{ Auth::user()->name }}</span>
                                        <x-icon name="chevron-up-down" class="w-3.5 h-3.5 text-slate-400" />
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium">
                                        @if(Auth::user()->role === 'mahasiswa')
                                            NIM: {{ Auth::user()->identifier }} • Kelas {{ Auth::user()->kelas ?? 'IF 7B' }}
                                        @elseif(Auth::user()->role === 'dosen_wali')
                                            Dosen Pembimbing Akademik • NIP: {{ Auth::user()->identifier }}
                                        @elseif(Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin')
                                            Koordinator Program Studi (Kaprodi) • NIP: {{ Auth::user()->identifier }}
                                        @else
                                            {{ Auth::user()->role_label }}
                                        @endif
                                    </div>
                                </div>
                            </button>

                            <!-- Role Switcher Dropdown (1-Click SSO) -->
                            <div class="absolute left-0 mt-2 w-84 rounded-2xl bg-white shadow-2xl border border-slate-100 p-2 hidden group-hover:block z-50">
                                <div class="px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 mb-1">
                                    Ganti Pengguna SSO (Pengujian Alur BPMN)
                                </div>
                                @if(isset($allUsers))
                                    @foreach($allUsers as $u)
                                        <form action="{{ route('login.quick') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $u->email }}">
                                            <button type="submit" class="w-full text-left px-3 py-2 rounded-xl text-xs flex items-center justify-between transition {{ Auth::id() === $u->id ? 'bg-blue-50 font-bold text-blue-700' : 'hover:bg-slate-50 text-slate-700' }}">
                                                <div class="truncate mr-2">
                                                    <div class="font-bold truncate">{{ $u->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-mono">{{ $u->identifier ?? $u->email }}</div>
                                                </div>
                                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded uppercase shrink-0 {{ $u->role === 'mahasiswa' ? 'bg-blue-100 text-blue-800' : ($u->role === 'dosen_wali' ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800') }}">
                                                    {{ $u->role }}
                                                </span>
                                            </button>
                                        </form>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Center: Search Input Bar (Matching reference.webp) -->
                    <div class="w-full sm:w-72 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <x-icon name="magnifying-glass" class="w-4 h-4" />
                        </div>
                        <input 
                            type="text" 
                            id="globalSearchInput"
                            placeholder="Cari mata kuliah, kode, atau jadwal..." 
                            class="w-full pl-10 pr-12 py-2 rounded-2xl border border-slate-200/90 text-xs font-semibold placeholder:text-slate-400 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/70"
                        />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-[10px] font-bold text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200 font-mono">⌘ F</span>
                        </div>
                    </div>

                    <!-- Right: Masa KRS Badge, Notification Pill & Action Buttons -->
                    <div class="flex items-center gap-2.5">
                        
                        <!-- Status Masa KRS Badge -->
                        @php
                            $isKrsOpen = \App\Models\SystemSetting::isKrsOpen();
                        @endphp
                        <div class="px-3 py-1.5 rounded-2xl text-xs font-bold flex items-center gap-1.5 border {{ $isKrsOpen ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}" title="Status Pembukaan Masa KRS oleh Kaprodi">
                            <span class="w-2 h-2 rounded-full {{ $isKrsOpen ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                            <span class="hidden sm:inline">Masa KRS:</span>
                            <span>{{ $isKrsOpen ? 'DIBUKA' : 'DITUTUP' }}</span>
                        </div>

                        <!-- Notification Badge -->
                        <div class="relative">
                            <button type="button" onclick="showNotificationModal()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200 text-xs font-bold hover:bg-rose-100 transition">
                                <x-icon name="bell" class="w-4 h-4 text-rose-500" />
                                <span>{{ isset($broadcast) && $broadcast && $broadcast->is_active ? '1 BARU' : '0' }}</span>
                            </button>
                        </div>

                        <!-- Button BPMN Modal -->
                        <button type="button" onclick="showBpmnModal()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm transition">
                            <x-icon name="sparkles" class="w-4 h-4 text-white" />
                            <span class="hidden lg:inline">Alur BPMN</span>
                        </button>
                    </div>

                </header>

                <!-- INNER CONTENT AREA -->
                <div class="p-6 lg:p-8 flex-1 space-y-8">
                    @yield('content')
                </div>

            </main>

        </div>

    </div>

    <!-- Notification Modal for Broadcast Announcements -->
    <div id="notificationModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <x-icon name="bell" class="w-5 h-5 text-rose-500" />
                    <h3 class="font-extrabold text-sm text-slate-900">Pusat Notifikasi & Pengumuman SIAKAD</h3>
                </div>
                <button type="button" onclick="closeNotificationModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <x-icon name="x-circle" class="w-5 h-5" />
                </button>
            </div>
            <div class="space-y-3">
                @if(isset($broadcast) && $broadcast && $broadcast->is_active)
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-xs space-y-1">
                        <div class="font-bold text-blue-900 flex items-center justify-between">
                            <span>Pengumuman Resmi: {{ $broadcast->sender_name ?? 'Kaprodi / Dosen Wali' }}</span>
                            <span class="text-[10px] text-blue-500 font-mono">{{ $broadcast->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-blue-800 leading-relaxed">{{ $broadcast->message }}</p>
                    </div>
                @else
                    <div class="py-6 text-center text-slate-400 text-xs">
                        Tidak ada pengumuman baru saat ini.
                    </div>
                @endif
            </div>
            <button type="button" onclick="closeNotificationModal()" class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- BPMN FIX VIEWER MODAL (BPMN_SIAKAD_FIX.png) -->
    <div id="bpmnModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-6">
        <div class="bg-white rounded-3xl max-w-6xl w-full h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                        <x-icon name="sparkles" class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-900 leading-tight">Pemodelan Proses Bisnis KRS Terintegrasi (BPMN To-Be)</h3>
                        <p class="text-[11px] text-slate-500">Diagram Alur Resmi Berdasarkan Dokumen BPMN_SIAKAD_FIX.png & LAPORAN_TA.pdf</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ asset('images/bpmn_siakad_fix.png') }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1">
                        <x-icon name="arrow-top-right-on-square" class="w-3.5 h-3.5" />
                        <span>Buka Gambar Asli HD</span>
                    </a>
                    <button type="button" onclick="closeBpmnModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                        <x-icon name="x-circle" class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Modal Content: High-Res Diagram Viewer & Lane Descriptions -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                <!-- 4 Lanes Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
                    <div class="p-3.5 rounded-2xl bg-blue-50/70 border border-blue-200">
                        <div class="font-extrabold text-blue-900">Lane 1: Mahasiswa</div>
                        <div class="text-[11px] text-blue-700 mt-1">Pembayaran UKT, Login, Pemilihan Jalur (Reguler / MBKM), Permintaan Kuota SIAKAD, Isi Form Bimbingan & Cetak Hard File, Perwalian.</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200">
                        <div class="font-extrabold text-emerald-900">Lane 2: Dosen Wali</div>
                        <div class="text-[11px] text-emerald-700 mt-1">Input Jadwal & Metode Perwalian ke SIAKAD, Periksa Form Bimbingan (Lengkap -> Arsip), Review KRS & Beri Wejangan, ACC KRS Final.</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-purple-50/70 border border-purple-200">
                        <div class="font-extrabold text-purple-900">Lane 3: Kaprodi</div>
                        <div class="text-[11px] text-purple-700 mt-1">Buka Masa KRS, Sub-process Memasukkan Mata Kuliah (jadwal & kuota), Evaluasi Kuota (+15 kursi atau Kelas Paralel), Validasi MBKM 20 SKS.</div>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-sky-50/70 border border-sky-200">
                        <div class="font-extrabold text-sky-900">Lane 4: Sistem SIAKAD</div>
                        <div class="text-[11px] text-sky-700 mt-1">Verifikasi Pembayaran UKT, Validasi Pembukaan Masa KRS, Cek Bentrok/Prasyarat/Kuota, Bandingkan Kebutuhan Riil Mahasiswa, Notifikasi Otomatis.</div>
                    </div>
                </div>

                <!-- BPMN Image Container with Pan / Zoom Hint -->
                <div class="border border-slate-200 rounded-2xl overflow-auto bg-slate-900/5 p-4 flex flex-col items-center">
                    <div class="w-full flex justify-between items-center pb-2 text-[11px] text-slate-500 font-semibold">
                        <span>Diagram BPMN 2.0: PROSES MENGAMBIL KRS (Resolusi Tinggi 15.514 x 2.936 px)</span>
                        <span class="text-blue-600 font-bold">Gunakan scroll horizontal untuk menelusuri seluruh tahapan alur</span>
                    </div>
                    <div class="overflow-x-auto w-full max-h-[60vh] border border-slate-200 rounded-xl bg-white shadow-inner p-2">
                        <img src="{{ asset('images/bpmn_siakad_fix.png') }}" alt="Diagram BPMN To-Be SIAKAD UTM" class="min-w-[2800px] max-w-none h-auto object-contain">
                    </div>
                </div>

                <!-- BPI Explanation Summary from Laporan TA Subbab 3.5 -->
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 text-xs space-y-2">
                    <div class="font-extrabold text-amber-950 flex items-center gap-1.5">
                        <x-icon name="sparkles" class="w-4 h-4 text-amber-600" />
                        <span>Prinsip Perbaikan Business Process Improvement (BPI) yang Diterapkan:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-amber-900 text-[11px] leading-relaxed">
                        <li><strong>Eliminate:</strong> Menghilangkan peran perantara Himatif dalam penampungan spreadsheet kuota dan pembuatan link perwalian WhatsApp.</li>
                        <li><strong>Automate:</strong> Sistem SIAKAD memverifikasi riwayat KRS mahasiswa secara otomatis untuk memastikan kebutuhan riil kuota, bukan sekadar iseng pindah kelas.</li>
                        <li><strong>Integrate:</strong> Dosen Wali memasukkan jadwal perwalian tatap muka / daring langsung ke SIAKAD, dan sistem secara terintegrasi mengirim notifikasi pengumuman ke mahasiswa.</li>
                    </ul>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 border-t border-slate-100 flex justify-end bg-slate-50/50">
                <button type="button" onclick="closeBpmnModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs transition">
                    Tutup Tampilan Alur
                </button>
            </div>
        </div>
    </div>

    <!-- Global SweetAlert2 & Simulation Reset Helper Scripts -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function showToast(icon, title, message = '') {
            Toast.fire({
                icon: icon,
                title: title,
                text: message
            });
        }

        function showNotificationModal() {
            document.getElementById('notificationModal').classList.remove('hidden');
        }

        function closeNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
        }

        function showBpmnModal() {
            document.getElementById('bpmnModal').classList.remove('hidden');
        }

        function closeBpmnModal() {
            document.getElementById('bpmnModal').classList.add('hidden');
        }

        function confirmResetSimulation() {
            Swal.fire({
                title: 'Reset Seluruh Simulasi?',
                text: 'Data kuota kelas, draf KRS, pengajuan MBKM, jadwal perwalian, dan log aktivitas akan dikembalikan ke kondisi awal skenario proposal BPMN To-Be.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mereset Database...',
                        text: 'Menjalankan seeder awal SQLite, mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route("simulation.reset") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Simulasi Direset!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(err => {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat mereset database simulasi.', 'error');
                    });
                }
            });
        }
    </script>

</body>
</html>
