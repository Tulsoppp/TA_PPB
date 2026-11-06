@extends('layouts.app', ['title' => 'Portal Dosen Wali — SIAKAD KRS UTM'])

@section('content')
<div class="space-y-8">

    <!-- 1. TOP STAT CARDS (Matching reference.webp 3-Card Row) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        
        <!-- Card 1: Advisees Count (Orange Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="users" class="w-5 h-5 text-orange-500" />
                    <span>Mahasiswa Bimbingan</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Kelas Perwalian IF 7B</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-0.5 tracking-tight">
                    {{ $stats['total'] }} <span class="text-sm font-semibold text-slate-400">Mahasiswa</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Dosen Wali</span>
                    <span class="font-bold text-slate-900 truncate max-w-[140px]">{{ $user->name }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pending ACC Queue (Purple Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="clipboard-check" class="w-5 h-5 text-purple-600" />
                    <span>Antrean Pengesahan (ACC)</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Pengajuan Siap Divalidasi</div>
                <div class="text-3xl font-extrabold text-amber-600 mt-0.5 tracking-tight">
                    {{ $stats['pending'] }} <span class="text-sm font-semibold text-slate-400">Berkas</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Perlu Revisi</span>
                    <span class="font-bold text-rose-600">{{ $stats['revision'] }} Mahasiswa</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Validation Rate Progress (Green Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="check-circle" class="w-5 h-5 text-emerald-600" />
                    <span>KRS Resmi Disahkan (ACC)</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">End Event: KRS Final ACC</div>
                <div class="text-3xl font-extrabold text-emerald-600 mt-0.5 tracking-tight">
                    {{ $stats['approved'] }} <span class="text-sm font-semibold text-slate-400">Mahasiswa</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                @php
                    $rate = min(100, round(($stats['approved'] / max(1, $stats['total'])) * 100));
                @endphp
                <span class="font-bold text-slate-500">Progress Validasi</span>
                <span class="font-extrabold text-emerald-700">{{ $rate }}% Tuntas</span>
            </div>
        </div>

        <!-- Card 4: Form Bimbingan Status -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="document-text" class="w-5 h-5 text-blue-600" />
                    <span>Arsip Form Bimbingan</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Dokumen Lengkap & Diarsipkan</div>
                <div class="text-3xl font-extrabold text-blue-600 mt-0.5 tracking-tight">
                    {{ $stats['bimbingan_verified'] }} <span class="text-sm font-semibold text-slate-400">Arsip</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                <span class="font-medium">Metode</span>
                <span class="font-bold text-slate-800">Tatap Muka & Daring</span>
            </div>
        </div>

    </div>

    <!-- 2. TAB 1: ANTREAN VALIDASI & PENGESAHAN KRS (BPMN: Dosen Wali Meninjau KRS Mahasiswa & ACC) -->
    <div id="dsnView_queue" class="dsn-view space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Daftar Mahasiswa Bimbingan (KRS ACC)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">BPMN Lane Dosen Wali: Masuk Bagian KRS -> Cari Nama & NIM -> Tinjau & Beri Wejangan -> ACC KRS</p>
                </div>
                <div class="w-full sm:w-64 relative">
                    <input 
                        type="text" 
                        id="adviseeSearchInput" 
                        onkeyup="searchAdvisees()" 
                        placeholder="Cari Nama atau NIM..." 
                        class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <x-icon name="magnifying-glass" class="w-3.5 h-3.5" />
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                            <th class="py-3 px-4">Identitas Mahasiswa</th>
                            <th class="py-3 px-3 text-center">IPK / IPS</th>
                            <th class="py-3 px-3 text-center">Beban SKS</th>
                            <th class="py-3 px-3">Jalur & Status Form</th>
                            <th class="py-3 px-3">Status Pengesahan</th>
                            <th class="py-3 px-4 text-center">Tindakan Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="adviseeTableBody">
                        @foreach($advisees as $adv)
                            @php
                                $k = $adv->krs;
                                $p = $adv->advisingProgress;
                                $m = $adv->mbkmApplication;
                                $kStatus = $k->status ?? 'draft';
                                $sksTotal = $k ? $k->courses->sum('sks') : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition advisee-row" data-name="{{ strtolower($adv->name) }}" data-nim="{{ $adv->identifier }}">
                                <td class="py-3.5 px-4">
                                    <div class="font-extrabold text-slate-900 text-sm">{{ $adv->name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $adv->identifier }} • Kelas {{ $adv->kelas }}</div>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <div class="font-mono font-bold text-slate-900">{{ number_format($adv->ipk, 2) }}</div>
                                    <div class="text-[10px] text-slate-400">IPS: {{ number_format($adv->ips, 2) }}</div>
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <span class="font-mono font-extrabold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-xl">
                                        {{ $sksTotal }} / {{ $adv->max_sks }} SKS
                                    </span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <div class="flex items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ ($k->path ?? 'reguler') === 'mbkm' ? 'bg-purple-100 text-purple-900' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $k->path ?? 'Reguler' }}
                                        </span>
                                        @if($p && $p->document_status === 'verified_archived')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-900">Form Lengkap</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900">Form Belum Cek</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-3">
                                    @php
                                        $badgeClass = [
                                            'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            'submitted' => 'bg-amber-100 text-amber-900 border-amber-300',
                                            'approved' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                            'revision' => 'bg-rose-100 text-rose-900 border-rose-300',
                                        ];
                                        $label = [
                                            'draft' => 'Draf (Belum Diajukan)',
                                            'submitted' => 'Menunggu ACC',
                                            'approved' => 'Telah Di-ACC (Resmi)',
                                            'revision' => 'Minta Revisi',
                                        ];
                                    @endphp
                                    <span class="inline-block text-[11px] font-extrabold px-2.5 py-0.5 rounded-full border {{ $badgeClass[$kStatus] ?? 'bg-slate-100' }}">
                                        {{ $label[$kStatus] ?? $kStatus }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button 
                                        type="button" 
                                        onclick="openReviewModal('{{ $adv->id }}')" 
                                        class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition flex items-center justify-center gap-1 mx-auto"
                                    >
                                        <x-icon name="clipboard-check" class="w-3.5 h-3.5" />
                                        <span>Tinjau KRS</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 3. TAB 2: PEMERIKSAAN FORM BIMBINGAN (BPMN: Dosen Wali Memeriksa Form Hard File & Digital) -->
    <div id="dsnView_bimbingan" class="dsn-view hidden space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Pemeriksaan Form Progress Bimbingan Mahasiswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">BPMN Gateway "Dokumen Lengkap?": Dosen memeriksa hard file/digital. Jika lengkap -> disimpan sebagai arsip; jika belum -> minta lengkapi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($advisees as $adv)
                    @php
                        $p = $adv->advisingProgress;
                        $isComplete = $p && $p->document_status === 'verified_archived';
                    @endphp
                    <div class="p-5 rounded-2xl border {{ $isComplete ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50/60' }} space-y-3 text-xs">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-sm">{{ $adv->name }}</h4>
                                <span class="font-mono text-[10px] text-slate-500">{{ $adv->identifier }} • Semester {{ $adv->semester }} (SKS Tempuh: {{ $p->sks_completed ?? 88 }})</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] border {{ $isComplete ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : 'bg-amber-100 text-amber-900 border-amber-300' }}">
                                {{ $isComplete ? 'Arsip Lengkap' : 'Menunggu Verifikasi' }}
                            </span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 space-y-1.5">
                            <div><strong>Rencana Studi & TA:</strong> {{ $p->study_plan_notes ?? 'Belum mengisi rencana studi.' }}</div>
                            <div><strong>Kendala Perkuliahan:</strong> {{ $p->obstacles_encountered ?? 'Tidak ada kendala dicatat.' }}</div>
                            <div class="text-[10px] text-slate-400">Metode Diinginkan: <span class="font-bold uppercase text-slate-700">{{ $p->method ?? 'offline' }}</span></div>
                        </div>

                        @if($p && $p->advisor_feedback)
                            <div class="p-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-[11px]">
                                <strong>Feedback Dosen:</strong> {{ $p->advisor_feedback }}
                            </div>
                        @endif

                        <div class="flex items-center gap-2 pt-1">
                            @if(!$isComplete)
                                <button 
                                    type="button" 
                                    onclick="verifyBimbingan('{{ $adv->id }}', 'complete', '{{ addslashes($adv->name) }}')"
                                    class="flex-1 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition flex items-center justify-center gap-1"
                                >
                                    <x-icon name="check-circle" class="w-4 h-4" />
                                    <span>Tandai Lengkap & Simpan Arsip</span>
                                </button>
                                <button 
                                    type="button" 
                                    onclick="promptIncompleteBimbingan('{{ $adv->id }}', '{{ addslashes($adv->name) }}')"
                                    class="py-2 px-3 rounded-xl border border-rose-200 hover:bg-rose-50 text-rose-700 font-bold text-xs transition"
                                >
                                    Minta Lengkapi
                                </button>
                            @else
                                <div class="w-full text-center py-1.5 text-emerald-800 font-extrabold text-[11px] bg-emerald-100/70 rounded-xl">
                                    Dokumen telah diverifikasi dan tersimpan sebagai arsip resmi
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4. TAB 3: PENJADWALAN PERWALIAN SIAKAD (BPMN: Dosen Wali Menentukan Jadwal & Menginput ke SIAKAD) -->
    <div id="dsnView_schedule" class="dsn-view hidden space-y-6">
        <div class="max-w-2xl bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <x-icon name="calendar" class="w-5 h-5 text-blue-600" />
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight">Penjadwalan Perwalian SIAKAD</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Input jadwal & metode langsung ke sistem. SIAKAD otomatis menyiarkan notifikasi resmi ke mahasiswa.</p>
                </div>
            </div>

            <form onsubmit="saveScheduleForm(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Agenda Perwalian</label>
                    <input 
                        type="text" 
                        id="schedTitle" 
                        required 
                        value="{{ $schedule->title ?? 'Perwalian Akademik Pengesahan KRS Semester Gasal 2026/2027' }}" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Hari & Tanggal Pelaksanaan</label>
                        <input 
                            type="text" 
                            id="schedDate" 
                            required 
                            value="{{ $schedule->schedule_date ?? 'Senin - Rabu, 10 - 12 November 2026' }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rentang Jam / Sesi</label>
                        <input 
                            type="text" 
                            id="schedTime" 
                            required 
                            value="{{ $schedule->time_range ?? '09.00 - 15.00 WIB' }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Metode Perwalian</label>
                    <select id="schedMethod" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                        <option value="both" {{ ($schedule->method ?? 'both') === 'both' ? 'selected' : '' }}>Hybrid (Tatap Muka & Daring Tersedia)</option>
                        <option value="offline" {{ ($schedule->method ?? 'both') === 'offline' ? 'selected' : '' }}>Hanya Tatap Muka (Offline di Kampus)</option>
                        <option value="online" {{ ($schedule->method ?? 'both') === 'online' ? 'selected' : '' }}>Hanya Daring (Online via Google Meet)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lokasi Tatap Muka</label>
                        <input 
                            type="text" 
                            id="schedOffline" 
                            value="{{ $schedule->location_offline ?? 'Ruang Dosen F-203, Gedung Fakultas Teknik UTM' }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tautan Daring (Online Meeting)</label>
                        <input 
                            type="text" 
                            id="schedOnline" 
                            value="{{ $schedule->link_online ?? 'https://meet.google.com/utm-krs-wali' }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                        />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Instruksi / Catatan Khusus untuk Mahasiswa</label>
                    <textarea id="schedNotes" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">{{ $schedule->notes ?? 'Harap mahasiswa mengisi Form Progress Bimbingan Akademik digital, mencetak hard file form bimbingan, dan membawa draf KRS saat perwalian.' }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                        <x-icon name="paper-airplane" class="w-4 h-4 text-white" />
                        <span>Simpan Jadwal & Siarkan Pengumuman ke Mahasiswa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. TAB 4: MONITORING KELAS & KUOTA -->
    <div id="dsnView_monitoring" class="dsn-view hidden space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Monitoring Kapasitas Kelas & Kuota Mata Kuliah</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemantauan keterisian rombongan belajar kelas perwalian IF 7B</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($courses as $c)
                    @php
                        $isFull = $c->enrolled >= $c->quota;
                    @endphp
                    <div class="p-4 rounded-2xl border {{ $isFull ? 'border-rose-200 bg-rose-50/30' : 'border-slate-100 bg-slate-50/50' }} space-y-2 text-xs">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="font-mono text-[10px] text-blue-700 font-bold">{{ $c->code }}</span>
                                <h4 class="font-bold text-slate-900 leading-snug">{{ $c->name }}</h4>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold {{ $isFull ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $c->enrolled }}/{{ $c->quota }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500">{{ $c->schedule }} ({{ $c->room }})</div>
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="h-1.5 rounded-full {{ $isFull ? 'bg-rose-500' : 'bg-blue-600' }}" style="width: {{ min(100, round(($c->enrolled/$c->quota)*100)) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<!-- REVIEW & ACC KRS MODAL DIALOG (BPMN To-Be) -->
<div id="reviewModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[92vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold">
                    <x-icon name="clipboard-check" class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900" id="revStudentName">Nama Mahasiswa</h3>
                    <p class="text-[11px] text-slate-500" id="revStudentMeta">NIM • Kelas</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 p-1">
                <x-icon name="x-circle" class="w-5 h-5" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 space-y-5 text-xs">
            <!-- Student Academic Quick Info -->
            <div class="grid grid-cols-4 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                <div>
                    <span class="text-slate-400 text-[10px] block">IPK Kumulatif</span>
                    <strong class="font-mono text-base text-slate-900" id="revIpk">3.78</strong>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] block">IPS Terakhir</span>
                    <strong class="font-mono text-base text-slate-900" id="revIps">3.82</strong>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] block">Beban SKS Diajukan</span>
                    <strong class="font-mono text-base text-blue-700" id="revTotalSks">18 SKS</strong>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] block">Status UKT</span>
                    <strong class="text-emerald-600 text-xs font-bold block mt-1">Lunas (Valid)</strong>
                </div>
            </div>

            <!-- List of Courses Enrolled -->
            <div class="space-y-2">
                <div class="font-bold text-slate-700">Daftar Mata Kuliah dalam KRS:</div>
                <div class="divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden" id="revCoursesList">
                    <!-- Populated via JS -->
                </div>
            </div>

            <!-- Form Bimbingan Progress Info -->
            <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200 space-y-1.5" id="revProgressBox">
                <div class="font-bold text-blue-950 flex items-center justify-between">
                    <span>Catatan Form Progress Bimbingan Mahasiswa:</span>
                    <span id="revDocStatusBadge" class="text-[9px] px-2 py-0.5 rounded font-extrabold uppercase bg-blue-100 text-blue-800">Lengkap</span>
                </div>
                <p class="text-[11px] text-blue-900" id="revStudyNotes">-</p>
            </div>

            <!-- Dosen Notes / Wejangan Input -->
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-700">Wejangan / Arahan Pembimbing Akademik:</label>
                <textarea 
                    id="revAdvisorNote" 
                    rows="3" 
                    placeholder="Tuliskan arahan, motivasi, atau wejangan akademik untuk mahasiswa bimbingan..." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50"
                >KRS telah ditinjau dan disetujui sesuai rencana studi. Pertahankan prestasi dan fokus pada capaian pembelajaran semester 5.</textarea>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50">
            <button type="button" onclick="submitRevision()" class="px-4 py-2.5 rounded-xl border border-rose-300 hover:bg-rose-50 text-rose-700 font-extrabold text-xs transition flex items-center gap-1.5">
                <x-icon name="arrow-path" class="w-4 h-4" />
                <span>Minta Revisi KRS</span>
            </button>
            <button type="button" onclick="submitApprove()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                <x-icon name="check-badge" class="w-4 h-4 text-white" />
                <span>Sahkan KRS (ACC Resmi)</span>
            </button>
        </div>
    </div>
</div>

<script>
    let activeStudentId = null;

    function switchAdvisorTab(tab) {
        document.querySelectorAll('.dsn-view').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.sidebar-nav-btn').forEach(btn => {
            btn.classList.remove('bg-blue-50', 'text-blue-600');
            btn.classList.add('text-slate-600');
        });

        const targetView = document.getElementById('dsnView_' + tab);
        const targetBtn = document.getElementById('dsnNavBtn_' + tab);

        if (targetView) targetView.classList.remove('hidden');
        if (targetBtn) {
            targetBtn.classList.add('bg-blue-50', 'text-blue-600');
            targetBtn.classList.remove('text-slate-600');
        }
    }

    function searchAdvisees() {
        const query = document.getElementById('adviseeSearchInput').value.toLowerCase();
        document.querySelectorAll('.advisee-row').forEach(row => {
            const name = row.getAttribute('data-name');
            const nim = row.getAttribute('data-nim');
            if (name.includes(query) || nim.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function openReviewModal(studentId) {
        activeStudentId = studentId;

        fetch(`/advisor/student/${studentId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const s = data.student;
                const k = data.krs;
                const p = data.progress;

                document.getElementById('revStudentName').textContent = s.name;
                document.getElementById('revStudentMeta').textContent = `NIM: ${s.nim} • Kelas ${s.kelas} • Semester ${s.semester}`;
                document.getElementById('revIpk').textContent = parseFloat(s.ipk).toFixed(2);
                document.getElementById('revIps').textContent = parseFloat(s.ips).toFixed(2);
                document.getElementById('revTotalSks').textContent = (k ? k.total_sks : 0) + ' SKS';

                // Render courses
                const list = document.getElementById('revCoursesList');
                list.innerHTML = '';
                if (k && k.courses.length > 0) {
                    k.courses.forEach((c, idx) => {
                        list.innerHTML += `
                            <div class="p-3 bg-white flex items-center justify-between text-xs">
                                <div>
                                    <div class="font-extrabold text-slate-900">${c.name}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">${c.code} • ${c.schedule} (${c.room || '-'}) • ${c.lecturer}</div>
                                </div>
                                <span class="font-mono font-bold text-blue-700">${c.sks} SKS</span>
                            </div>
                        `;
                    });
                } else {
                    list.innerHTML = '<div class="p-4 text-center text-slate-400">Belum ada mata kuliah yang diambil.</div>';
                }

                // Progress form
                if (p) {
                    document.getElementById('revStudyNotes').textContent = p.study_plan_notes || 'Tidak ada catatan khusus.';
                    document.getElementById('revDocStatusBadge').textContent = p.document_status === 'verified_archived' ? 'Dokumen Lengkap & Arsip' : 'Belum Diverifikasi';
                } else {
                    document.getElementById('revStudyNotes').textContent = 'Mahasiswa belum melengkapi form progress bimbingan digital.';
                    document.getElementById('revDocStatusBadge').textContent = 'Belum Ada Form';
                }

                if (k && k.advisor_note) {
                    document.getElementById('revAdvisorNote').value = k.advisor_note;
                }

                document.getElementById('reviewModal').classList.remove('hidden');
            }
        });
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    function submitApprove() {
        const note = document.getElementById('revAdvisorNote').value;

        Swal.fire({
            title: 'Sahkan KRS Mahasiswa?',
            text: 'KRS akan disetujui (ACC) dan lembar KRS resmi berstempel digital dapat langsung dicetak oleh mahasiswa.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, ACC Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/advisor/approve/${activeStudentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ note })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'KRS Telah Di-ACC!',
                            text: data.message,
                            confirmButtonColor: '#059669'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }

    function submitRevision() {
        const note = document.getElementById('revAdvisorNote').value;

        if (!note || note.trim().length < 5) {
            Swal.fire('Perhatian', 'Harap masukkan catatan arahan revisi perbaikan untuk mahasiswa.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Kirim Catatan Revisi?',
            text: 'Status KRS mahasiswa akan diubah menjadi Perlu Revisi agar mahasiswa dapat menyesuaikan mata kuliah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Kirim Catatan Revisi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/advisor/revision/${activeStudentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ note })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Catatan Revisi Terkirim',
                            text: data.message,
                            confirmButtonColor: '#2563eb'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                });
            }
        });
    }

    function verifyBimbingan(studentId, status, studentName) {
        fetch(`/advisor/verify-document/${studentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Form Bimbingan Lengkap!',
                    text: data.message,
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        });
    }

    function promptIncompleteBimbingan(studentId, studentName) {
        Swal.fire({
            title: `Minta Lengkapi Dokumen ${studentName}?`,
            input: 'textarea',
            inputLabel: 'Catatan Kekurangan Dokumen Bimbingan',
            inputPlaceholder: 'Contoh: Mohon melengkapi isian evaluasi capaian SKS dan mencetak hard file...',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Kirim Permintaan Kelengkapan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                fetch(`/advisor/verify-document/${studentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: 'incomplete', feedback: result.value })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Terkirim!', data.message, 'info').then(() => window.location.reload());
                });
            }
        });
    }

    function saveScheduleForm(e) {
        e.preventDefault();
        const title = document.getElementById('schedTitle').value;
        const schedule_date = document.getElementById('schedDate').value;
        const time_range = document.getElementById('schedTime').value;
        const method = document.getElementById('schedMethod').value;
        const location_offline = document.getElementById('schedOffline').value;
        const link_online = document.getElementById('schedOnline').value;
        const notes = document.getElementById('schedNotes').value;

        fetch('{{ route("advisor.save_schedule") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title, schedule_date, time_range, method, location_offline, link_online, notes })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Jadwal Disimpan & Disiarkan!',
                    text: data.message,
                    confirmButtonColor: '#2563eb'
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        });
    }
</script>
@endsection
