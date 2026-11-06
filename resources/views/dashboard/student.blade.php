@extends('layouts.app', ['title' => 'Portal Mahasiswa — SIAKAD KRS UTM'])

@section('content')
<div class="space-y-8">

    <!-- 1. TOP STAT CARDS (Matching reference.webp 3-Card Row) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Card 1: GPA / IPK Card (Orange Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="chart-bar" class="w-5 h-5 text-orange-500" />
                    <span>GPA / Indeks Prestasi</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Current GPA (IPK Kumulatif)</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-0.5 tracking-tight">
                    {{ number_format($user->ipk, 2) }}
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs">
                <div class="flex items-center justify-between text-slate-600">
                    <span class="font-medium truncate max-w-[180px]">IP Semester Terakhir (IPS)</span>
                    <span class="font-bold text-slate-900">{{ number_format($user->ips, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span class="font-medium">Maksimal Hak Beban</span>
                    <span class="font-bold text-blue-600">{{ $user->max_sks }} SKS</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Academic Status / Rencana Studi Card (Purple Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="credit-card" class="w-5 h-5 text-purple-600" />
                    <span>Status UKT & KRS</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Status Validasi Dosen Wali</div>
                <div class="mt-0.5">
                    @php
                        $status = $krs->status;
                        $statusBadge = [
                            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                            'submitted' => 'bg-amber-100 text-amber-900 border-amber-300',
                            'approved' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                            'revision' => 'bg-rose-100 text-rose-900 border-rose-300',
                        ];
                        $statusText = [
                            'draft' => 'Draf Belum Diajukan',
                            'submitted' => 'Menunggu Validasi ACC',
                            'approved' => 'Terverifikasi & ACC (Resmi)',
                            'revision' => 'Perlu Perbaikan / Revisi',
                        ];
                    @endphp
                    <span class="inline-block text-xs font-extrabold px-3 py-1 rounded-xl border {{ $statusBadge[$status] ?? 'bg-slate-100' }}">
                        {{ $statusText[$status] ?? ucfirst($status) }}
                    </span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Status Pembayaran UKT</span>
                    <span class="font-bold text-emerald-600 flex items-center gap-1">
                        <x-icon name="check-circle" class="w-3.5 h-3.5" />
                        <span>Lunas (Terverifikasi)</span>
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium">Jalur Pengambilan</span>
                    <span class="font-bold uppercase text-blue-700 font-mono">{{ $krs->path ?? 'Reguler' }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: My Progress / SKS Progress Card (Green Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="academic-cap" class="w-5 h-5 text-emerald-600" />
                    <span>Beban Studi Semester 5</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Total mata kuliah yang dipilih pada Kartu Rencana Studi semester ini.
                </p>
                @php
                    $percentSks = min(100, round(($totalSks / max(1, $user->max_sks)) * 100));
                @endphp
                <div class="mt-3 w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full bg-blue-600 transition-all duration-500" style="width: {{ $percentSks }}%"></div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-extrabold text-blue-700" id="topSksDisplay">{{ $totalSks }} / {{ $user->max_sks }} SKS</span>
                <span class="font-bold text-slate-500" id="topSksPercentDisplay">{{ $percentSks }}% Terisi</span>
            </div>
        </div>

    </div>

    <!-- Alert jika Masa KRS Belum Dibuka -->
    @if(!$isKrsOpen)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-center gap-3">
            <x-icon name="exclamation-triangle" class="w-6 h-6 text-amber-600 shrink-0" />
            <div>
                <div class="font-extrabold">Perhatian: Masa Pengisian KRS Belum Dibuka oleh Kaprodi</div>
                <p class="text-[11px] mt-0.5">Sesuai BPMN, Anda dapat melihat jadwal penawaran mata kuliah, namun penambahan mata kuliah ke draf KRS dikunci hingga Kaprodi resmi membuka masa KRS.</p>
            </div>
        </div>
    @endif

    <!-- Alert Wejangan / Catatan Dosen Wali jika ada -->
    @if($krs->advisor_note)
        <div class="p-4 rounded-2xl {{ $krs->status === 'approved' ? 'bg-emerald-50 border border-emerald-300 text-emerald-950' : 'bg-rose-50 border border-rose-300 text-rose-950' }} text-xs space-y-1">
            <div class="font-extrabold flex items-center gap-1.5">
                <x-icon name="{{ $krs->status === 'approved' ? 'check-circle' : 'exclamation-circle' }}" class="w-4 h-4 {{ $krs->status === 'approved' ? 'text-emerald-600' : 'text-rose-600' }}" />
                <span>{{ $krs->status === 'approved' ? 'Pengesahan & Wejangan Dosen Wali (ACC)' : 'Catatan Revisi dari Dosen Wali' }}</span>
            </div>
            <p class="italic text-[11px]">"{{ $krs->advisor_note }}"</p>
            <div class="text-[10px] text-slate-500 font-mono pt-1">
                Dosen Pembimbing Akademik: {{ optional($user->advisor)->name }} • {{ optional($krs->validated_at)->format('d M Y, H:i') }} WIB
            </div>
        </div>
    @endif

    <!-- 2. VIEW TAB: DASHBOARD & JADWAL (TIMETABLE GRID MATCHING REFERENCE.WEBP) -->
    <div id="view_dashboard" class="main-view space-y-6">
        
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <!-- Schedule Header Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <x-icon name="calendar" class="w-5 h-5 text-blue-600" />
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Jadwal Kuliah Mingguan</h3>
                        <p class="text-xs text-slate-500">Semester Gasal 2026/2027 • Kelas {{ $user->kelas ?? 'IF 7B' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl">2026 FALL / GANJIL</span>
                    <button type="button" onclick="switchMainTab('krs')" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm transition flex items-center gap-1.5">
                        <x-icon name="plus" class="w-4 h-4" />
                        <span>Rencanakan KRS</span>
                    </button>
                    <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1">
                        <button type="button" onclick="toggleTimetableView('grid')" id="btnViewGrid" class="p-1.5 rounded-lg bg-white shadow-xs text-slate-900" title="Tampilan Kalender">
                            <x-icon name="table-cells" class="w-4 h-4" />
                        </button>
                        <button type="button" onclick="toggleTimetableView('list')" id="btnViewList" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700" title="Tampilan Tabel">
                            <x-icon name="bars-3" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Timetable Day Headers -->
            <div id="timetableGridWrapper" class="space-y-4">
                <div class="grid grid-cols-8 gap-2 text-center text-xs font-bold text-slate-700 border-b border-slate-100 pb-3">
                    <div class="text-slate-400 font-mono">WAKTU</div>
                    <div class="py-1.5 rounded-xl bg-blue-50 text-blue-700 font-black">Senin</div>
                    <div class="py-1.5 rounded-xl">Selasa</div>
                    <div class="py-1.5 rounded-xl">Rabu</div>
                    <div class="py-1.5 rounded-xl">Kamis</div>
                    <div class="py-1.5 rounded-xl">Jumat</div>
                    <div class="py-1.5 rounded-xl text-slate-400">Sabtu</div>
                    <div class="py-1.5 rounded-xl text-slate-400">Minggu</div>
                </div>

                <!-- Timetable Slots -->
                <div class="divide-y divide-slate-100 text-xs">
                    <!-- Slot 07:30 - 10:00 -->
                    <div class="grid grid-cols-8 gap-2 py-3 min-h-[90px] items-stretch">
                        <div class="text-slate-400 font-mono text-[11px] pr-2 pt-1 border-r border-slate-100 flex flex-col justify-center text-center">
                            <span class="font-bold text-slate-700">07:30</span>
                            <span class="text-[10px] text-slate-400">10:00</span>
                        </div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK01'))
                                <div class="h-full p-2.5 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Pemodelan Proses Bisnis</div>
                                        <div class="text-[9px] text-indigo-700 mt-0.5">R. Gedung F-201</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-indigo-500 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK04'))
                                <div class="h-full p-2.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">RPL Lanjut</div>
                                        <div class="text-[9px] text-amber-700 mt-0.5">R. Gedung F-204</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-amber-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK05'))
                                <div class="h-full p-2.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Manajemen Proyek TI</div>
                                        <div class="text-[9px] text-emerald-700 mt-0.5">R. Gedung F-105</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-emerald-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                    </div>

                    <!-- Slot 09:30 - 12:00 -->
                    <div class="grid grid-cols-8 gap-2 py-3 min-h-[90px] items-stretch">
                        <div class="text-slate-400 font-mono text-[11px] pr-2 pt-1 border-r border-slate-100 flex flex-col justify-center text-center">
                            <span class="font-bold text-slate-700">09:30</span>
                            <span class="text-[10px] text-slate-400">12:00</span>
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK02'))
                                <div class="h-full p-2.5 rounded-2xl bg-sky-50 border border-sky-200 text-sky-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Kecerdasan Buatan (AI)</div>
                                        <div class="text-[9px] text-sky-700 mt-0.5">Lab Komputasi Cerdas</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-sky-600 font-semibold">3 SKS</span>
                                </div>
                            @elseif($krs->courses->contains('id', 'MK08'))
                                <div class="h-full p-2.5 rounded-2xl bg-purple-50 border border-purple-200 text-purple-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Pengolahan Citra Digital</div>
                                        <div class="text-[9px] text-purple-700 mt-0.5">Lab Multimedia</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-purple-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK03'))
                                <div class="h-full p-2.5 rounded-2xl bg-teal-50 border border-teal-200 text-teal-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Keamanan Jaringan</div>
                                        <div class="text-[9px] text-teal-700 mt-0.5">Lab Jaringan Komputer</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-teal-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                    </div>

                    <!-- Slot 13:00 - 15:30 -->
                    <div class="grid grid-cols-8 gap-2 py-3 min-h-[90px] items-stretch">
                        <div class="text-slate-400 font-mono text-[11px] pr-2 pt-1 border-r border-slate-100 flex flex-col justify-center text-center">
                            <span class="font-bold text-slate-700">13:00</span>
                            <span class="text-[10px] text-slate-400">15:30</span>
                        </div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK07'))
                                <div class="h-full p-2.5 rounded-2xl bg-blue-50 border border-blue-200 text-blue-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Metodologi Penelitian</div>
                                        <div class="text-[9px] text-blue-700 mt-0.5">R. Gedung F-202</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-blue-600 font-semibold">2 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK09'))
                                <div class="h-full p-2.5 rounded-2xl bg-cyan-50 border border-cyan-200 text-cyan-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Cloud Computing</div>
                                        <div class="text-[9px] text-cyan-700 mt-0.5">Lab Cloud Awan</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-cyan-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1">
                            @if($krs->courses->contains('id', 'MK06'))
                                <div class="h-full p-2.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-950 flex flex-col justify-between shadow-xs">
                                    <div>
                                        <div class="font-extrabold text-[11px] leading-tight">Pemrograman Mobile</div>
                                        <div class="text-[9px] text-rose-700 mt-0.5">Lab Rekayasa RPL</div>
                                    </div>
                                    <span class="text-[9px] font-mono text-rose-600 font-semibold">3 SKS</span>
                                </div>
                            @else
                                <div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div>
                            @endif
                        </div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100 flex items-center justify-center text-slate-300 text-[10px]">-</div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                        <div class="p-1"><div class="h-full rounded-xl border border-dashed border-slate-100"></div></div>
                    </div>
                </div>
            </div>

            <!-- List View (Alternatif) -->
            <div id="timetableListWrapper" class="hidden overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                            <th class="py-3 px-4">Mata Kuliah Diambil</th>
                            <th class="py-3 px-3">Kode MK</th>
                            <th class="py-3 px-3 text-center">SKS</th>
                            <th class="py-3 px-3">Kelas</th>
                            <th class="py-3 px-4">Jadwal & Ruang</th>
                            <th class="py-3 px-4">Dosen Pengampu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($krs->courses as $c)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3.5 px-4 font-extrabold text-slate-900">{{ $c->name }}</td>
                                <td class="py-3.5 px-3 font-mono font-bold text-blue-700">{{ $c->code }}</td>
                                <td class="py-3.5 px-3 text-center font-bold">{{ $c->sks }}</td>
                                <td class="py-3.5 px-3"><span class="px-2 py-0.5 rounded font-extrabold text-[10px] bg-slate-100 text-slate-700">{{ $c->kelas }}</span></td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $c->schedule }} ({{ $c->room }})</td>
                                <td class="py-3.5 px-4 text-slate-800">{{ $c->lecturer }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400">Belum ada mata kuliah yang diambil.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- 3. VIEW TAB: PENGISIAN KRS REGULER (BPMN: Lane Mahasiswa - Jalur Reguler) -->
    <div id="view_krs" class="main-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left 8 Cols: Offerings Table -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Mata Kuliah Ditawarkan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pemilihan Paket Semester Gasal 2026/2027 • S1 Teknik Informatika</p>
                </div>
                <div class="flex items-center gap-2">
                    <select id="courseCategoryFilter" onchange="filterCourses()" class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-slate-50 font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="all">Semua Mata Kuliah</option>
                        <option value="mandatory">Mata Kuliah Wajib</option>
                        <option value="elective">Mata Kuliah Pilihan</option>
                        <option value="available">Hanya Kuota Tersedia</option>
                        <option value="full">Kelas Penuh (Kuota Habis)</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                            <th class="py-3 px-4">Kode & Mata Kuliah</th>
                            <th class="py-3 px-2 text-center">SKS</th>
                            <th class="py-3 px-2">Kelas</th>
                            <th class="py-3 px-3">Jadwal & Ruang</th>
                            <th class="py-3 px-3">Dosen Pengampu</th>
                            <th class="py-3 px-3 text-center">Kapasitas</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($courses as $c)
                            @php
                                $isEnrolled = in_array($c->id, $selectedCourseIds);
                                $isFull = $c->enrolled >= $c->quota;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition course-row" 
                                data-id="{{ $c->id }}"
                                data-mandatory="{{ $c->is_mandatory ? '1' : '0' }}"
                                data-full="{{ $isFull ? '1' : '0' }}"
                            >
                                <td class="py-3.5 px-4">
                                    <div class="font-extrabold text-slate-900">{{ $c->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="font-mono text-[11px] text-blue-700 font-semibold">{{ $c->code }}</span>
                                        @if($c->is_mandatory)
                                            <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800">Wajib</span>
                                        @else
                                            <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded bg-purple-100 text-purple-800">Pilihan</span>
                                        @endif
                                        @if($c->prerequisite_course_code)
                                            <span class="text-[9px] font-semibold text-slate-400">Prasyarat: {{ $c->prerequisite_course_name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-2 text-center font-bold text-slate-900">{{ $c->sks }}</td>
                                <td class="py-3.5 px-2">
                                    <span class="px-2 py-0.5 rounded-md font-extrabold text-[10px] {{ str_contains($c->kelas, 'IF 7C') ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ $c->kelas }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="text-slate-700 font-medium">{{ $c->schedule }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $c->room }}</div>
                                </td>
                                <td class="py-3.5 px-3 text-slate-700 font-medium">
                                    {{ $c->lecturer }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="font-mono font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ $c->enrolled }}/{{ $c->quota }}
                                    </div>
                                    <div class="w-16 bg-slate-100 h-1.5 rounded-full mx-auto mt-1 overflow-hidden">
                                        <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ min(100, round(($c->enrolled/$c->quota)*100)) }}%"></div>
                                    </div>
                                    @if($isFull)
                                        <span class="text-[9px] font-extrabold text-rose-600 uppercase block mt-0.5">Penuh</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($isEnrolled)
                                        <button 
                                            type="button" 
                                            onclick="removeCourse('{{ $c->id }}', '{{ addslashes($c->name) }}')"
                                            class="px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 font-bold transition flex items-center justify-center gap-1 mx-auto"
                                            {{ in_array($krs->status, ['submitted', 'approved']) ? 'disabled' : '' }}
                                        >
                                            <x-icon name="trash" class="w-3.5 h-3.5" />
                                            <span>Batal</span>
                                        </button>
                                    @else
                                        @if($isFull)
                                            <button 
                                                type="button" 
                                                onclick="promptQuotaRequest('{{ $c->id }}', '{{ addslashes($c->name) }}', '{{ $c->code }}')"
                                                class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold shadow-sm transition flex items-center justify-center gap-1 mx-auto"
                                                title="Kuota penuh! Kirim permintaan kuota langsung ke SIAKAD"
                                            >
                                                <x-icon name="chat-bubble" class="w-3.5 h-3.5" />
                                                <span>Request</span>
                                            </button>
                                        @else
                                            <button 
                                                type="button" 
                                                onclick="addCourse('{{ $c->id }}', '{{ addslashes($c->name) }}')"
                                                class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold shadow-sm transition flex items-center justify-center gap-1 mx-auto"
                                                {{ in_array($krs->status, ['submitted', 'approved']) || !$isKrsOpen ? 'disabled' : '' }}
                                            >
                                                <x-icon name="plus" class="w-3.5 h-3.5" />
                                                <span>Ambil</span>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right 4 Cols: Selected Cart & Submission -->
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900">Draf KRS Anda</h4>
                        <p class="text-[11px] text-slate-400">Semester Gasal 2026/2027</p>
                    </div>
                    <span class="text-xs font-mono font-black px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700" id="cartTotalSksBadge">
                        {{ $totalSks }} SKS
                    </span>
                </div>

                <div class="space-y-2.5 max-h-[360px] overflow-y-auto" id="selectedCoursesList">
                    @forelse($krs->courses as $c)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3 text-xs" id="cartItem_{{ $c->id }}">
                            <div class="truncate">
                                <div class="font-extrabold text-slate-900 truncate">{{ $c->name }}</div>
                                <div class="text-[10px] text-slate-400 flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-blue-700 font-semibold">{{ $c->code }}</span>
                                    <span>•</span>
                                    <span>{{ $c->sks }} SKS</span>
                                    <span>•</span>
                                    <span>{{ $c->kelas }}</span>
                                </div>
                            </div>
                            @if(!in_array($krs->status, ['submitted', 'approved']))
                                <button type="button" onclick="removeCourse('{{ $c->id }}', '{{ addslashes($c->name) }}')" class="text-slate-400 hover:text-rose-600 p-1" title="Hapus dari KRS">
                                    <x-icon name="x-circle" class="w-4 h-4" />
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Belum ada mata kuliah yang dipilih ke dalam draf.
                        </div>
                    @endforelse
                </div>

                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Hak Beban Maksimal:</span>
                        <span class="font-extrabold text-slate-800">{{ $user->max_sks }} SKS</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Sisa Kuota SKS:</span>
                        <span class="font-extrabold text-blue-600" id="remainingSksText">{{ max(0, $user->max_sks - $totalSks) }} SKS</span>
                    </div>
                </div>

                <!-- Submit / Retract Action Buttons -->
                <div class="pt-2">
                    @if($krs->status === 'draft' || $krs->status === 'revision')
                        <button 
                            type="button" 
                            onclick="submitKrsToAdvisor()"
                            class="w-full py-3 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2 {{ $krs->courses->count() === 0 ? 'opacity-50 pointer-events-none' : '' }}"
                            id="btnSubmitKrs"
                        >
                            <x-icon name="paper-airplane" class="w-4 h-4 text-white" />
                            <span>Ajukan KRS ke Dosen Wali</span>
                        </button>
                    @elseif($krs->status === 'submitted')
                        <div class="space-y-2">
                            <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                                <x-icon name="clock" class="w-4 h-4 text-amber-600 shrink-0" />
                                <span>KRS sedang ditinjau Dosen Wali. Silakan lengkapi berkas perwalian.</span>
                            </div>
                            <button 
                                type="button" 
                                onclick="cancelKrsSubmission()"
                                class="w-full py-2.5 px-4 rounded-2xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition"
                            >
                                Tarik Kembali Pengajuan (Batal Kirim)
                            </button>
                        </div>
                    @elseif($krs->status === 'approved')
                        <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 text-xs flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-600 shrink-0" />
                            <span>KRS telah disahkan resmi oleh Dosen Wali (ACC). Lembar KRS siap dicetak.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- 4. VIEW TAB: JALUR MBKM (BPMN: Lane Mahasiswa - Jalur MBKM) -->
    <div id="view_mbkm" class="main-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Formulir Pengajuan Program MBKM -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-bold">
                    <x-icon name="sparkles" class="w-3 h-3 text-purple-600" />
                    <span>Jalur Alternatif BPMN MBKM</span>
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">Pengajuan Program MBKM & Bukti Penerimaan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Mahasiswa program MSIB, Pertukaran, atau Studi Independen tidak mengisi KRS reguler melainkan mengajukan konversi 20 SKS ke Kaprodi.</p>
            </div>

            <form onsubmit="submitMbkmForm(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Program MBKM</label>
                    <input 
                        type="text" 
                        id="mbkmProgramName" 
                        required 
                        value="{{ $mbkm->program_name ?? 'Magang Bersertifikat Kampus Merdeka (MSIB Batch 7)' }}"
                        placeholder="Contoh: Magang Bersertifikat MSIB Batch 7" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mitra Industri / Instansi Penyelenggara</label>
                    <input 
                        type="text" 
                        id="mbkmMitra" 
                        required 
                        value="{{ $mbkm->mitra ?? 'PT Telkom Indonesia (Persero) Tbk' }}"
                        placeholder="Contoh: PT Telkom Indonesia, PT GoTo, Bangkit Academy" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Posisi / Kompetensi yang Ditempuh</label>
                    <input 
                        type="text" 
                        id="mbkmRole" 
                        value="{{ $mbkm->role_position ?? 'Software Engineer Intern - Cloud Platform Division' }}"
                        placeholder="Contoh: Cloud Architecture & DevOps Specialist" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Program & Beban Konversi</label>
                    <input 
                        type="text" 
                        id="mbkmDuration" 
                        required 
                        value="{{ $mbkm->duration ?? '1 Semester (20 SKS Konversi)' }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700">Lampiran Surat Penerimaan (LoA / Acceptance Letter)</label>
                    <div class="flex items-center gap-2 text-xs text-blue-700 font-mono font-bold">
                        <x-icon name="document-text" class="w-4 h-4 text-blue-600" />
                        <span>{{ $mbkm->acceptance_letter_path ?? 'SURAT-PENERIMAAN-MSIB-2026.pdf' }}</span>
                    </div>
                    <p class="text-[10px] text-slate-400">Berkas digital telah terlampir dalam pangkalan data verifikasi Kaprodi.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-purple-600 hover:bg-purple-700 text-white font-black text-xs shadow-md shadow-purple-500/20 transition flex items-center justify-center gap-2">
                        <x-icon name="paper-airplane" class="w-4 h-4 text-white" />
                        <span>{{ isset($mbkm) && $mbkm->status === 'rejected' ? 'Perbaiki & Kirim Ulang Pengajuan MBKM' : 'Unggah & Ajukan Program MBKM' }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Status Konversi & Rincian 20 SKS -->
        <div class="lg:col-span-7 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900">Status Validasi & Konversi Mata Kuliah oleh Kaprodi</h4>
                        <p class="text-[11px] text-slate-500">BPMN Gateway: Kaprodi Memvalidasi Konversi Mata Kuliah MBKM</p>
                    </div>
                    @if(isset($mbkm))
                        <span class="text-xs font-extrabold px-3 py-1 rounded-full border {{ $mbkm->status === 'approved' ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : ($mbkm->status === 'rejected' ? 'bg-rose-100 text-rose-900 border-rose-300' : 'bg-amber-100 text-amber-900 border-amber-300') }}">
                            {{ $mbkm->status === 'approved' ? 'Disetujui (20 SKS Diinput)' : ($mbkm->status === 'rejected' ? 'Perlu Perbaikan' : 'Menunggu Validasi Kaprodi') }}
                        </span>
                    @else
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600">Belum Ada Pengajuan</span>
                    @endif
                </div>

                @if(isset($mbkm) && $mbkm->status === 'rejected')
                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-1">
                        <div class="font-extrabold flex items-center gap-1.5">
                            <x-icon name="exclamation-circle" class="w-4 h-4 text-rose-600" />
                            <span>Catatan Evaluasi Kaprodi (Perbaikan Diperlukan):</span>
                        </div>
                        <p class="text-[11px]">{{ $mbkm->rejection_note }}</p>
                    </div>
                @endif

                @if(isset($mbkm) && $mbkm->status === 'approved')
                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 text-xs space-y-1">
                        <div class="font-extrabold flex items-center gap-1.5">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                            <span>Paket 20 SKS Telah Diinputkan Kaprodi ke KRS Anda!</span>
                        </div>
                        <p class="text-[11px]">Mahasiswa dapat langsung mengikuti proses perwalian dengan Dosen Wali.</p>
                    </div>
                @endif

                <!-- Rincian Konversi Paket 20 SKS -->
                <div class="space-y-2">
                    <div class="text-xs font-bold text-slate-700">Daftar Mata Kuliah Ekivalensi / Konversi MBKM (20 SKS):</div>
                    <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden text-xs">
                        <div class="p-3 bg-slate-50 flex items-center justify-between font-bold text-slate-700">
                            <span>Mata Kuliah Konversi</span>
                            <span>Bobot</span>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">MBKM501 • Praktik Industri Telekomunikasi & Awan</div>
                                <div class="text-[10px] text-slate-400">Pembelajaran langsung di lingkungan profesional mitra</div>
                            </div>
                            <span class="font-mono font-bold text-blue-700">4 SKS</span>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">MBKM502 • Proyek Rekayasa Perangkat Lunak Industri</div>
                                <div class="text-[10px] text-slate-400">Pengembangan aplikasi skala enterprise</div>
                            </div>
                            <span class="font-mono font-bold text-blue-700">6 SKS</span>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">MBKM503 • Analisis dan Arsitektur Sistem Terapan</div>
                                <div class="text-[10px] text-slate-400">Desain sistem dan integrasi infrastruktur</div>
                            </div>
                            <span class="font-mono font-bold text-blue-700">4 SKS</span>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">MBKM504 • Komunikasi Profesional & Budaya Kerja TI</div>
                                <div class="text-[10px] text-slate-400">Softskill kolaborasi lintas divisi dan kepemimpinan</div>
                            </div>
                            <span class="font-mono font-bold text-blue-700">3 SKS</span>
                        </div>
                        <div class="p-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">MBKM505 • Etika Profesi & Tata Kelola Digital</div>
                                <div class="text-[10px] text-slate-400">Kepatuhan regulasi dan etika perekayasa perangkat lunak</div>
                            </div>
                            <span class="font-mono font-bold text-blue-700">3 SKS</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 5. VIEW TAB: PERWALIAN & FORM BIMBINGAN (BPMN: Persiapan & Pelaksanaan Perwalian) -->
    <div id="view_perwalian" class="main-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Jadwal Resmi Perwalian SIAKAD dari Dosen Wali -->
        <div class="lg:col-span-5 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <x-icon name="calendar" class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 leading-tight">Jadwal Perwalian SIAKAD</h3>
                        <p class="text-xs text-slate-500">Dosen Wali: {{ optional($user->advisor)->name }}</p>
                    </div>
                </div>

                @if($advisingSchedule)
                    <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-xs space-y-2">
                        <div class="font-extrabold text-emerald-950 text-sm">{{ $advisingSchedule->title }}</div>
                        <div class="space-y-1.5 text-emerald-900 text-[11px]">
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar" class="w-4 h-4 text-emerald-600" />
                                <span><strong>Tanggal:</strong> {{ $advisingSchedule->schedule_date }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="clock" class="w-4 h-4 text-emerald-600" />
                                <span><strong>Waktu:</strong> {{ $advisingSchedule->time_range }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="building-office" class="w-4 h-4 text-emerald-600" />
                                <span><strong>Tatap Muka:</strong> {{ $advisingSchedule->location_offline }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="video-camera" class="w-4 h-4 text-emerald-600" />
                                <span><strong>Daring (Online):</strong> <a href="{{ $advisingSchedule->link_online }}" target="_blank" class="underline font-bold text-emerald-700">Tautan Google Meet</a></span>
                            </div>
                        </div>
                        @if($advisingSchedule->notes)
                            <div class="pt-2 border-t border-emerald-200/80 text-[11px] text-emerald-900 italic">
                                "Catatan Dosen: {{ $advisingSchedule->notes }}"
                            </div>
                        @endif
                    </div>
                @else
                    <div class="py-6 text-center text-slate-400 text-xs">
                        Jadwal perwalian belum diinputkan oleh Dosen Wali.
                    </div>
                @endif

                <!-- BPI Benefit Card -->
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-xs space-y-1.5">
                    <div class="font-extrabold text-blue-900 flex items-center gap-1.5">
                        <x-icon name="sparkles" class="w-4 h-4 text-blue-600" />
                        <span>Integrasi Sistem To-Be:</span>
                    </div>
                    <p class="text-[11px] text-blue-800 leading-relaxed">
                        Sesuai BPMN To-Be, jadwal perwalian diinput dosen langsung ke SIAKAD dan otomatis disiarkan ke portal Anda, menggantikan spreadsheet dan WhatsApp terpisah.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Form Progress Bimbingan & Cetak Hard File -->
        <div class="lg:col-span-7 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Form Progress Bimbingan Akademik</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Wajib diisi digital dan dicetak sebagai Hard File saat perwalian</p>
                    </div>
                    <a href="{{ route('student.print_form_bimbingan') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5 shrink-0">
                        <x-icon name="printer" class="w-4 h-4 text-white" />
                        <span>Cetak Hard File (PDF)</span>
                    </a>
                </div>

                <!-- Form Inputs -->
                <form onsubmit="submitFormBimbingan(event)" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Total SKS Telah Ditempuh</label>
                            <input 
                                type="number" 
                                id="formSksCompleted" 
                                value="{{ $advisingProgress->sks_completed ?? 88 }}" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Rencana Metode Perwalian</label>
                            <select id="formMethod" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                                <option value="offline" {{ ($advisingProgress->method ?? 'offline') === 'offline' ? 'selected' : '' }}>Tatap Muka (Offline di R. Dosen F-203)</option>
                                <option value="online" {{ ($advisingProgress->method ?? 'offline') === 'online' ? 'selected' : '' }}>Daring (Online via Google Meet)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rencana Studi & Minat Tugas Akhir</label>
                        <textarea 
                            id="formStudyPlan" 
                            rows="2" 
                            required 
                            placeholder="Tuliskan target beban SKS, fokus bidang keahlian (misal: AI / Software Engineering), dan rencana tema tugas akhir..." 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        >{{ $advisingProgress->study_plan_notes ?? 'Rencana mengambil 18 SKS semester 5 dan mulai menyusun topik tugas akhir bidang Artificial Intelligence & Enterprise System.' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kendala Akademik / Catatan untuk Dosen Wali</label>
                        <textarea 
                            id="formObstacles" 
                            rows="2" 
                            placeholder="Tuliskan kendala perkuliahan yang dihadapi semester lalu (jika ada)..." 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        >{{ $advisingProgress->obstacles_encountered ?? 'Kendala ketersediaan kuota kelas pada mata kuliah wajib semester 5.' }}</textarea>
                    </div>

                    <!-- Dokumen Status Status Banner -->
                    <div class="p-3.5 rounded-2xl border flex items-center justify-between {{ ($advisingProgress->document_status ?? '') === 'verified_archived' ? 'bg-emerald-50 border-emerald-200 text-emerald-950' : (($advisingProgress->document_status ?? '') === 'incomplete' ? 'bg-rose-50 border-rose-200 text-rose-950' : 'bg-slate-50 border-slate-200 text-slate-800') }}">
                        <div>
                            <div class="text-[10px] uppercase font-bold text-slate-500">Status Form Bimbingan:</div>
                            <div class="font-extrabold text-xs">
                                @if(($advisingProgress->document_status ?? '') === 'verified_archived')
                                    Lengkap — Telah Diperiksa & Disimpan Sebagian Arsip Dosen Wali
                                @elseif(($advisingProgress->document_status ?? '') === 'incomplete')
                                    Belum Lengkap — Dosen Wali meminta kelengkapan berkas
                                @else
                                    Tersimpan & Siap Diserahkan saat Perwalian
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs transition">
                            Simpan Form
                        </button>
                    </div>

                    @if($advisingProgress && $advisingProgress->advisor_feedback)
                        <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900">
                            <strong>Umpan Balik Dosen Wali:</strong> {{ $advisingProgress->advisor_feedback }}
                        </div>
                    @endif
                </form>
            </div>
        </div>

    </div>

    <!-- 6. VIEW TAB: PERMINTAAN KUOTA SIAKAD (BPMN To-Be: Mahasiswa Mengirim Permintaan Matakuliah Penuh Ke SIAKAD) -->
    <div id="view_kuota" class="main-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Form Permintaan Kuota SIAKAD -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[10px] font-bold">
                    <x-icon name="chat-bubble" class="w-3 h-3 text-amber-700" />
                    <span>Fitur Penanganan Kuota Otomatis</span>
                </span>
                <h3 class="text-base font-extrabold text-slate-900 mt-1">Permintaan Kuota Penuh ke SIAKAD</h3>
                <p class="text-xs text-slate-500 mt-0.5">Kirim permintaan langsung ke sistem. SIAKAD otomatis memvalidasi riwayat studi Anda dan meneruskan antrean peminat ke Kaprodi.</p>
            </div>

            <form onsubmit="submitDirectQuotaRequest(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Mata Kuliah yang Kuotanya Penuh</label>
                    <select id="reqCourseId" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ $c->id === 'MK02' ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->code }}) — {{ $c->enrolled }}/{{ $c->quota }} Kursi
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Urgensi Pengambilan</label>
                    <select id="reqUrgency" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                        <option value="Wajib Semester Ini (Prioritas Utama)">Wajib Semester Ini (Prioritas Utama)</option>
                        <option value="Mata Kuliah Prasyarat Tugas Akhir">Mata Kuliah Prasyarat Tugas Akhir</option>
                        <option value="Mengulang Mata Kuliah Semester Lalu">Mengulang Mata Kuliah Semester Lalu</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan / Catatan Kebutuhan</label>
                    <textarea 
                        id="reqNotes" 
                        rows="3" 
                        required 
                        placeholder="Contoh: Kuota kelas IF 7B penuh (40/40). Saya membutuhkan mata kuliah wajib ini pada semester 5 untuk memenuhi prasyarat konsentrasi..." 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    >Kuota kelas IF 7B penuh 40/40. Membutuhkan penambahan kapasitas kelas untuk semester 5.</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-2">
                        <x-icon name="paper-airplane" class="w-4 h-4" />
                        <span>Kirim Permintaan Kuota ke SIAKAD</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Riwayat Permintaan Kuota & Status Keputusan Kaprodi -->
        <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Riwayat Permintaan Kuota SIAKAD</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Validasi sistem otomatis & keputusan penambahan kuota Kaprodi</p>
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-700">
                    {{ $quotaRequests->count() }} Permintaan
                </span>
            </div>

            <div class="space-y-3">
                @forelse($quotaRequests as $qr)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-900">{{ $qr->request_number }}</span>
                                <h4 class="font-bold text-slate-900 mt-1">{{ $qr->course->name }} ({{ $qr->course->code }})</h4>
                            </div>
                            @php
                                $decisionBadge = [
                                    'pending' => 'bg-amber-100 text-amber-900 border-amber-300',
                                    'approved_quota' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                    'approved_parallel' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                    'rejected' => 'bg-rose-100 text-rose-900 border-rose-300',
                                ];
                                $decisionLabel = [
                                    'pending' => 'Menunggu Evaluasi Kaprodi',
                                    'approved_quota' => 'Disetujui - Kuota Ditambah',
                                    'approved_parallel' => 'Disetujui - Kelas Paralel Baru',
                                    'rejected' => 'Ditolak',
                                ];
                            @endphp
                            <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border {{ $decisionBadge[$qr->kaprodi_decision] ?? 'bg-slate-100' }}">
                                {{ $decisionLabel[$qr->kaprodi_decision] ?? $qr->kaprodi_decision }}
                            </span>
                        </div>

                        <!-- System Automated Validation Badge -->
                        @if($qr->system_validation_passed)
                            <div class="p-2 rounded-xl bg-sky-50 border border-sky-200 text-sky-900 text-[11px] flex items-center gap-1.5">
                                <x-icon name="check-badge" class="w-4 h-4 text-sky-600 shrink-0" />
                                <span>{{ $qr->system_validation_note ?? 'Lolos Validasi Otomatis Sistem SIAKAD' }}</span>
                            </div>
                        @endif

                        <p class="text-slate-600 text-[11px] italic">"{{ $qr->notes }}"</p>

                        @if($qr->kaprodi_response_note)
                            <div class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-[11px]">
                                <strong>Keputusan Kaprodi:</strong> {{ $qr->kaprodi_response_note }}
                            </div>
                        @endif

                        <div class="text-[10px] text-slate-400 font-mono flex items-center justify-between pt-1 border-t border-slate-200/60">
                            <span>Antrean Peminat Terhitung: {{ $qr->system_peminat_count }} Mhs</span>
                            <span>{{ $qr->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">Belum ada riwayat permintaan kuota ke SIAKAD.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 7. VIEW TAB: CETAK LEMBAR KRS RESMI (BERSTEMPEL DIGITAL) -->
    <div id="view_cetak" class="main-view hidden space-y-6">
        
        <!-- Top Print Action Bar -->
        <div class="no-print rounded-2xl p-4 border flex items-center justify-between gap-4 {{ $krs->status === 'approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-950' : 'bg-amber-50 border-amber-200 text-amber-950' }}">
            <div class="flex items-center gap-3">
                <x-icon name="{{ $krs->status === 'approved' ? 'shield-check' : 'exclamation-triangle' }}" class="w-6 h-6 {{ $krs->status === 'approved' ? 'text-emerald-600' : 'text-amber-600' }}" />
                <div>
                    <div class="font-extrabold text-xs">
                        {{ $krs->status === 'approved' ? 'Kartu Rencana Studi Telah Disahkan (ACC)' : 'Status KRS: Belum Disahkan Resmi oleh Dosen Wali' }}
                    </div>
                    <p class="text-[11px] opacity-80 mt-0.5">
                        {{ $krs->status === 'approved' ? 'Dokumen ini memiliki kekuatan hukum akademik dengan stempel digital resmi Universitas Trunojoyo Madura.' : 'Anda dapat mencetak draf KRS ini untuk keperluan perwalian akademik dengan Dosen Wali.' }}
                    </p>
                </div>
            </div>
            <button onclick="window.print()" class="shrink-0 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm transition flex items-center gap-1.5">
                <x-icon name="printer" class="w-4 h-4" />
                <span>Cetak Lembar KRS (PDF)</span>
            </button>
        </div>

        <!-- Official UTM Printable Sheet -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-lg p-8 sm:p-12 max-w-4xl mx-auto printable-sheet">
            <!-- Kop Surat Resmi -->
            <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center space-y-1">
                <div class="text-xs uppercase font-bold tracking-widest text-slate-600">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
                <div class="text-lg font-black tracking-tight text-slate-900">UNIVERSITAS TRUNOJOYO MADURA</div>
                <div class="text-sm font-extrabold text-slate-800">FAKULTAS TEKNIK — PROGRAM STUDI S1 TEKNIK INFORMATIKA</div>
                <div class="text-[11px] text-slate-500">Jl. Raya Telang PO BOX 2 Kamal, Bangkalan - Madura | Laman: trunojoyo.ac.id</div>
                <div class="pt-2">
                    <span class="inline-block px-4 py-1 rounded bg-slate-100 text-slate-900 font-black text-xs uppercase tracking-wider border border-slate-300">
                        KARTU RENCANA STUDI (KRS)
                    </span>
                </div>
            </div>

            <!-- Student Bio Details -->
            <div class="grid grid-cols-2 gap-4 text-xs mb-6 text-slate-800">
                <div class="space-y-1">
                    <div class="flex"><span class="w-32 text-slate-500">Nama Mahasiswa</span><span class="font-bold">: {{ $user->name }}</span></div>
                    <div class="flex"><span class="w-32 text-slate-500">Nomor Induk (NIM)</span><span class="font-mono font-bold">: {{ $user->identifier }}</span></div>
                    <div class="flex"><span class="w-32 text-slate-500">Program Studi</span><span>: S1 Teknik Informatika</span></div>
                </div>
                <div class="space-y-1">
                    <div class="flex"><span class="w-32 text-slate-500">Semester / Tahun</span><span>: Semester Gasal 2026/2027</span></div>
                    <div class="flex"><span class="w-32 text-slate-500">Dosen Pembimbing</span><span class="font-bold">: {{ optional($user->advisor)->name }}</span></div>
                    <div class="flex"><span class="w-32 text-slate-500">Status Pembayaran</span><span class="text-emerald-700 font-bold">: UKT Lunas (Terverifikasi)</span></div>
                </div>
            </div>

            <!-- Official Table -->
            <table class="w-full text-xs text-left border-collapse border border-slate-300 mb-6">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300 font-bold text-slate-900">
                        <th class="p-2 border border-slate-300 text-center w-10">No</th>
                        <th class="p-2 border border-slate-300">Kode MK</th>
                        <th class="p-2 border border-slate-300">Nama Mata Kuliah</th>
                        <th class="p-2 border border-slate-300 text-center">SKS</th>
                        <th class="p-2 border border-slate-300">Kelas</th>
                        <th class="p-2 border border-slate-300">Jadwal & Ruang</th>
                        <th class="p-2 border border-slate-300">Dosen Pengampu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($krs->courses as $index => $c)
                        <tr>
                            <td class="p-2 border border-slate-300 text-center font-mono">{{ $index + 1 }}</td>
                            <td class="p-2 border border-slate-300 font-mono font-bold text-blue-800">{{ $c->code }}</td>
                            <td class="p-2 border border-slate-300 font-bold text-slate-900">{{ $c->name }}</td>
                            <td class="p-2 border border-slate-300 text-center font-bold">{{ $c->sks }}</td>
                            <td class="p-2 border border-slate-300 text-center">{{ $c->kelas }}</td>
                            <td class="p-2 border border-slate-300 text-slate-700">{{ $c->schedule }} ({{ $c->room }})</td>
                            <td class="p-2 border border-slate-300 text-slate-800">{{ $c->lecturer }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-6 text-center text-slate-400">Belum ada mata kuliah yang disetujui.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 font-bold border-t-2 border-slate-300">
                        <td colspan="3" class="p-2 text-right">Total Beban Studi (SKS) :</td>
                        <td class="p-2 text-center font-mono text-sm text-blue-900">{{ $totalSks }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>

            <!-- Signatures Section -->
            <div class="grid grid-cols-2 gap-8 pt-6 text-xs text-center">
                <div class="space-y-16">
                    <div>
                        <div class="text-slate-500">Mahasiswa Pemohon,</div>
                    </div>
                    <div>
                        <div class="font-bold underline text-slate-900">{{ $user->name }}</div>
                        <div class="text-[11px] font-mono text-slate-500">NIM. {{ $user->identifier }}</div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="text-slate-500">Bangkalan, {{ date('d F Y') }}</div>
                        <div class="text-slate-500">Dosen Pembimbing Akademik,</div>
                    </div>

                    @if($krs->status === 'approved')
                        <!-- Digital Approval Stamp -->
                        <div class="w-32 h-32 mx-auto rounded-full border-2 border-dashed border-emerald-600 flex flex-col items-center justify-center p-2 text-emerald-700 rotate-[-8deg] bg-emerald-50/50">
                            <x-icon name="check-badge" class="w-7 h-7 text-emerald-600 mb-0.5" />
                            <span class="text-[9px] font-black uppercase tracking-wider">DISAHKAN (ACC)</span>
                            <span class="text-[7px] font-mono">DIGITAL SIGNED</span>
                            <span class="text-[7px]">{{ optional($krs->validated_at)->format('d/m/Y') }}</span>
                        </div>
                    @else
                        <div class="h-20 flex items-center justify-center text-amber-700 text-[11px] italic">
                            (Belum Di-ACC Dosen Wali)
                        </div>
                    @endif

                    <div>
                        <div class="font-bold underline text-slate-900">{{ optional($user->advisor)->name }}</div>
                        <div class="text-[11px] font-mono text-slate-500">NIP. {{ optional($user->advisor)->identifier }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Dialog Permintaan Kuota SIAKAD (BPMN To-Be) -->
<div id="quotaRequestModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <x-icon name="chat-bubble" class="w-5 h-5 text-amber-500" />
                <h3 class="font-extrabold text-sm text-slate-900">Permintaan Kuota Penuh ke SIAKAD</h3>
            </div>
            <button type="button" onclick="closeQuotaModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <x-icon name="x-circle" class="w-5 h-5" />
            </button>
        </div>

        <p class="text-xs text-slate-500 leading-relaxed">
            Kapasitas kelas untuk <strong id="modalCourseName" class="text-slate-900">Mata Kuliah</strong> sudah penuh. Sesuai <strong>BPMN To-Be</strong>, Anda dapat mengirim permintaan langsung ke sistem tanpa melalui organisasi perantara.
        </p>

        <form onsubmit="handleModalQuotaSubmit(event)" class="space-y-3.5">
            <input type="hidden" id="modalCourseId" value="">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tingkat Urgensi</label>
                <select id="modalUrgency" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                    <option value="Wajib Semester Ini (Prioritas Utama)">Wajib Semester Ini (Prioritas Utama)</option>
                    <option value="Prasyarat Mata Kuliah Lanjutan">Prasyarat Mata Kuliah Lanjutan</option>
                    <option value="Mengulang Mata Kuliah Semester Lalu">Mengulang Mata Kuliah Semester Lalu</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Kebutuhan</label>
                <textarea id="modalNotes" rows="3" required placeholder="Jelaskan kebutuhan Anda untuk mengambil mata kuliah ini..." class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">Kuota kelas IF 7B penuh 40/40. Membutuhkan tambahan kuota untuk memenuhi hak SKS wajib semester 5.</textarea>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <button type="button" onclick="closeQuotaModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-sm transition">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab Switcher
    function switchMainTab(tab) {
        document.querySelectorAll('.main-view').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.sidebar-nav-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-600');
            btn.classList.add('text-slate-600');
        });

        const targetView = document.getElementById('view_' + tab);
        const targetBtn = document.getElementById('navBtn_' + tab);

        if (targetView) targetView.classList.remove('hidden');
        if (targetBtn) {
            targetBtn.classList.add('bg-blue-50', 'text-blue-600');
            targetBtn.classList.remove('text-slate-600');
        }
    }

    function toggleTimetableView(view) {
        if (view === 'grid') {
            document.getElementById('timetableGridWrapper').classList.remove('hidden');
            document.getElementById('timetableListWrapper').classList.add('hidden');
            document.getElementById('btnViewGrid').classList.add('bg-white', 'shadow-xs', 'text-slate-900');
            document.getElementById('btnViewList').classList.remove('bg-white', 'shadow-xs', 'text-slate-900');
            document.getElementById('btnViewList').classList.add('text-slate-400');
        } else {
            document.getElementById('timetableGridWrapper').classList.add('hidden');
            document.getElementById('timetableListWrapper').classList.remove('hidden');
            document.getElementById('btnViewList').classList.add('bg-white', 'shadow-xs', 'text-slate-900');
            document.getElementById('btnViewGrid').classList.remove('bg-white', 'shadow-xs', 'text-slate-900');
            document.getElementById('btnViewGrid').classList.add('text-slate-400');
        }
    }

    function filterCourses() {
        const val = document.getElementById('courseCategoryFilter').value;
        const rows = document.querySelectorAll('.course-row');

        rows.forEach(r => {
            const isMandatory = r.getAttribute('data-mandatory') === '1';
            const isFull = r.getAttribute('data-full') === '1';

            if (val === 'all') r.style.display = '';
            else if (val === 'mandatory') r.style.display = isMandatory ? '' : 'none';
            else if (val === 'elective') r.style.display = !isMandatory ? '' : 'none';
            else if (val === 'available') r.style.display = !isFull ? '' : 'none';
            else if (val === 'full') r.style.display = isFull ? '' : 'none';
        });
    }

    // Add Course with System Check Handlers (Bentrok, Prasyarat, Kuota Penuh)
    function addCourse(courseId, courseName) {
        fetch('{{ route("student.add_course") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ course_id: courseId })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Ditambahkan!',
                    text: body.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                // Tangani jenis kegagalan sesuai BPMN Sub-process "Matakuliah Gagal Diambil"
                if (body.error_type === 'schedule_clash') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Jadwal Bertabrakan (Bentrok)!',
                        html: `<p class="text-xs mb-2 text-slate-600">${body.message}</p><div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">Mata kuliah bentrok dengan: <strong>${body.clashing_course}</strong></div>`,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Kembali Ke Tampilan KRS'
                    });
                } else if (body.error_type === 'prerequisite_not_met') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Belum Memenuhi Prasyarat Mata Kuliah!',
                        html: `<p class="text-xs mb-3 text-slate-600">${body.message}</p><div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs text-left"><strong>Prasyarat Wajib:</strong> ${body.prerequisite_code} — ${body.prerequisite_name}</div>`,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Kembali Ke Tampilan KRS'
                    });
                } else if (body.error_type === 'quota_full') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Mata Kuliah Penuh (' + body.enrolled + '/' + body.quota + ' Kursi)',
                        text: body.message,
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Kirim Permintaan Matakuliah Penuh Ke SIAKAD',
                        cancelButtonText: 'Kembali Ke Tampilan KRS'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            promptQuotaRequest(courseId, courseName, body.course_code || '');
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Dapat Menambah Mata Kuliah',
                        text: body.message,
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'Kembali Ke Tampilan KRS'
                    });
                }
            }
        })
        .catch(err => {
            Swal.fire('Kesalahan!', 'Terjadi gangguan jaringan.', 'error');
        });
    }

    function removeCourse(courseId, courseName) {
        Swal.fire({
            title: 'Hapus Mata Kuliah?',
            text: `Yakin ingin menghapus ${courseName} dari draf KRS?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("student.remove_course") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ course_id: courseId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }

    function promptQuotaRequest(courseId, courseName, courseCode) {
        document.getElementById('modalCourseId').value = courseId;
        document.getElementById('modalCourseName').textContent = courseName + (courseCode ? ' (' + courseCode + ')' : '');
        document.getElementById('quotaRequestModal').classList.remove('hidden');
    }

    function closeQuotaModal() {
        document.getElementById('quotaRequestModal').classList.add('hidden');
    }

    function handleModalQuotaSubmit(e) {
        e.preventDefault();
        const courseId = document.getElementById('modalCourseId').value;
        const urgency = document.getElementById('modalUrgency').value;
        const notes = document.getElementById('modalNotes').value;

        sendQuotaRequest(courseId, urgency, notes);
        closeQuotaModal();
    }

    function submitDirectQuotaRequest(e) {
        e.preventDefault();
        const courseId = document.getElementById('reqCourseId').value;
        const urgency = document.getElementById('reqUrgency').value;
        const notes = document.getElementById('reqNotes').value;

        sendQuotaRequest(courseId, urgency, notes);
    }

    function sendQuotaRequest(courseId, urgency, notes) {
        Swal.fire({
            title: 'SIAKAD Memvalidasi...',
            text: 'Sistem sedang memverifikasi peminat & riwayat KRS Anda secara otomatis...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('{{ route("student.quota_request") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                course_id: courseId,
                urgency: urgency,
                notes: notes
            })
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 200 && body.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Validasi Sistem Lolos!',
                    html: `<p class="text-xs mb-2">${body.message}</p><span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-xl text-xs border border-blue-200">Antrean Peminat: ${body.peminat_count} Mahasiswa</span>`,
                    confirmButtonColor: '#2563eb',
                    confirmButtonText: 'OK'
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Gagal!', body.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Kesalahan!', 'Terjadi gangguan jaringan.', 'error');
        });
    }

    function submitMbkmForm(e) {
        e.preventDefault();
        const program_name = document.getElementById('mbkmProgramName').value;
        const mitra = document.getElementById('mbkmMitra').value;
        const role_position = document.getElementById('mbkmRole').value;
        const duration = document.getElementById('mbkmDuration').value;

        Swal.fire({
            title: 'Mengajukan Program MBKM?',
            text: 'Berkas dan LoA akan dikirim ke Kaprodi untuk validasi konversi 20 SKS.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Ajukan ke Kaprodi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("student.submit_mbkm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ program_name, mitra, role_position, duration })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengajuan Terkirim!',
                            text: data.message,
                            confirmButtonColor: '#9333ea'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }

    function submitFormBimbingan(e) {
        e.preventDefault();
        const sks_completed = document.getElementById('formSksCompleted').value;
        const method = document.getElementById('formMethod').value;
        const study_plan_notes = document.getElementById('formStudyPlan').value;
        const obstacles_encountered = document.getElementById('formObstacles').value;

        fetch('{{ route("student.form_bimbingan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sks_completed, method, study_plan_notes, obstacles_encountered })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Form Tersimpan!',
                    text: data.message,
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        });
    }

    function submitKrsToAdvisor() {
        Swal.fire({
            title: 'Ajukan KRS ke Dosen Wali?',
            text: 'Setelah diajukan, KRS akan ditinjau dan divalidasi oleh Dr. RIKA YUNITARINI ST., MT.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Ajukan Sekarang',
            cancelButtonText: 'Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("student.submit_krs") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'KRS Berhasil Diajukan!',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal Mengajukan!', data.message, 'error');
                    }
                });
            }
        });
    }

    function cancelKrsSubmission() {
        Swal.fire({
            title: 'Tarik Pengajuan KRS?',
            text: 'Status KRS akan kembali ke Draf sehingga Anda dapat mengubah pilihan mata kuliah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Tarik Pengajuan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("student.cancel_krs") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengajuan Ditarik',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
