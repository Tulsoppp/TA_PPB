@extends('layouts.app', ['title' => 'Portal Kaprodi — SIAKAD KRS UTM'])

@section('content')
<div class="space-y-8">

    <!-- 1. TOP STAT CARDS (Matching reference.webp 3-Card Row) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        
        <!-- Card 1: Masa KRS Switcher Card (Purple Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="key" class="w-5 h-5 text-purple-600" />
                    <span>Masa Pengisian KRS</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Otoritas Buka/Tutup Kaprodi</div>
                <div class="mt-1 flex items-center justify-between">
                    <span class="text-xl font-black {{ $isKrsOpen ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $isKrsOpen ? 'RESMI DIBUKA' : 'DITUTUP' }}
                    </span>
                    <button 
                        type="button" 
                        onclick="toggleMasaKrs()"
                        class="px-3 py-1 rounded-xl text-xs font-bold transition {{ $isKrsOpen ? 'bg-amber-100 text-amber-900 hover:bg-amber-200' : 'bg-emerald-100 text-emerald-900 hover:bg-emerald-200' }}"
                    >
                        {{ $isKrsOpen ? 'Tutup Masa KRS' : 'Buka Masa KRS' }}
                    </button>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 text-xs text-slate-500">
                <span>Semester Gasal 2026/2027 • Tervalidasi Sistem</span>
            </div>
        </div>

        <!-- Card 2: Quota Requests Pending Evaluation (Orange Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="chat-bubble" class="w-5 h-5 text-orange-500" />
                    <span>Permintaan Kuota SIAKAD</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Antrean Evaluasi Kaprodi</div>
                <div class="text-3xl font-extrabold text-amber-600 mt-0.5 tracking-tight">
                    {{ $stats['pending_quota_requests'] }} <span class="text-sm font-semibold text-slate-400">Pengajuan</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                <span class="font-medium">Terselesaikan</span>
                <span class="font-bold text-emerald-600">{{ $stats['resolved_quota_requests'] }} Pengajuan</span>
            </div>
        </div>

        <!-- Card 3: MBKM Validation (Purple Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="sparkles" class="w-5 h-5 text-purple-600" />
                    <span>Validasi Berkas MBKM</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Pengajuan Konversi 20 SKS</div>
                <div class="text-3xl font-extrabold text-purple-700 mt-0.5 tracking-tight">
                    {{ $stats['pending_mbkm'] }} <span class="text-sm font-semibold text-slate-400">Berkas</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                <span class="font-medium">MBKM Disetujui</span>
                <span class="font-bold text-emerald-700">{{ $stats['approved_mbkm'] }} Mahasiswa</span>
            </div>
        </div>

        <!-- Card 4: Total Courses & Full Classes (Blue Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="book-open" class="w-5 h-5 text-blue-600" />
                    <span>Katalog Kelas & Kuota</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Total Rombel Kelas Aktif</div>
                <div class="text-3xl font-extrabold text-slate-900 mt-0.5 tracking-tight">
                    {{ $stats['total_courses'] }} <span class="text-sm font-semibold text-slate-400">Kelas</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-600">
                <span class="font-medium">Kelas Berstatus Penuh</span>
                <span class="font-extrabold text-rose-600">{{ $stats['full_classes'] }} Kelas</span>
            </div>
        </div>

    </div>

    <!-- 2. TAB 1: EVALUASI PERMINTAAN KUOTA PENUH (BPMN: Kaprodi Mengevaluasi Pengajuan -> Kuota Kelas Ditambah?) -->
    <div id="kpView_quota" class="kp-view space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Evaluasi Permintaan Kuota Penuh (Otomasi SIAKAD To-Be)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Sesuai Subbab 3.5.1 LAPORAN_TA: Peminat valid &lt; 10 mhs -> Tambah kuota (+15 kursi); Peminat &gt;= 10 mhs -> Buka kelas paralel baru.</p>
                </div>
                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                    Otoritas Kaprodi S1 TI
                </span>
            </div>

            <div class="space-y-4">
                @forelse($quotaRequests as $qr)
                    @php
                        $isPending = $qr->kaprodi_decision === 'pending';
                    @endphp
                    <div class="p-5 rounded-2xl border {{ $isPending ? 'border-amber-200 bg-amber-50/30' : 'border-slate-100 bg-slate-50/50' }} space-y-3 text-xs">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-900">{{ $qr->request_number }}</span>
                                    <span class="text-slate-400">•</span>
                                    <span class="font-bold text-slate-700">{{ $qr->user->name }} (NIM: {{ $qr->user->identifier }} • Kelas {{ $qr->user->kelas }})</span>
                                </div>
                                <h4 class="font-extrabold text-slate-900 text-sm mt-1">
                                    {{ $qr->course->name }} ({{ $qr->course->code }}) — Kelas {{ $qr->course->kelas }}
                                </h4>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Kapasitas Saat Ini: <strong class="text-rose-600 font-mono">{{ $qr->course->enrolled }}/{{ $qr->course->quota }} Kursi</strong> • Jadwal: {{ $qr->course->schedule }} ({{ $qr->course->room }})
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1.5">
                                <span class="px-3 py-1 rounded-full font-extrabold text-[10px] border {{ $isPending ? 'bg-amber-100 text-amber-900 border-amber-300' : ($qr->kaprodi_decision === 'rejected' ? 'bg-rose-100 text-rose-900 border-rose-300' : 'bg-emerald-100 text-emerald-900 border-emerald-300') }}">
                                    {{ $isPending ? 'Menunggu Evaluasi Kaprodi' : ($qr->kaprodi_decision === 'rejected' ? 'Ditolak' : 'Disetujui & Kuota Diperbarui') }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-400">{{ $qr->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- System Validation Detail -->
                        <div class="p-3 rounded-xl bg-sky-50/80 border border-sky-200 text-sky-950 text-[11px] flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <x-icon name="check-badge" class="w-4 h-4 text-sky-600 shrink-0" />
                                <span>{{ $qr->system_validation_note ?? 'Lolos Validasi Otomatis: Mahasiswa aktif Semester 5 & membutuhkan mata kuliah ini.' }}</span>
                            </div>
                            <span class="font-bold text-sky-800 shrink-0 font-mono bg-white px-2 py-0.5 rounded border border-sky-200">
                                Akumulasi Peminat: {{ $qr->system_peminat_count }} Mhs
                            </span>
                        </div>

                        <p class="text-slate-600 italic">"{{ $qr->notes }}"</p>

                        @if($qr->kaprodi_response_note)
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-[11px]">
                                <strong>Catatan Tindak Lanjut Kaprodi:</strong> {{ $qr->kaprodi_response_note }}
                            </div>
                        @endif

                        @if($isPending)
                            <div class="pt-2 border-t border-slate-200/60 flex flex-wrap items-center gap-2">
                                <!-- Option 1: Tambah Kuota Kelas (Pedoman < 10 Mahasiswa) -->
                                <button 
                                    type="button" 
                                    onclick="executeQuotaDecision('{{ $qr->id }}', 'tambah_kuota', '{{ addslashes($qr->course->name) }}', 15)"
                                    class="py-2 px-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5"
                                >
                                    <x-icon name="plus-circle" class="w-4 h-4" />
                                    <span>Tambah Kuota (+15 Kursi)</span>
                                </button>

                                <!-- Option 2: Buka Kelas Paralel Baru (Pedoman >= 10 Mahasiswa) -->
                                <button 
                                    type="button" 
                                    onclick="executeQuotaDecision('{{ $qr->id }}', 'buka_paralel', '{{ addslashes($qr->course->name) }}', 40)"
                                    class="py-2 px-3.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5"
                                >
                                    <x-icon name="sparkles" class="w-4 h-4" />
                                    <span>Buka Kelas Paralel Baru (IF 7C)</span>
                                </button>

                                <!-- Option 3: Tolak -->
                                <button 
                                    type="button" 
                                    onclick="promptRejectQuota('{{ $qr->id }}', '{{ addslashes($qr->course->name) }}')"
                                    class="py-2 px-3 rounded-xl border border-rose-200 hover:bg-rose-50 text-rose-700 font-bold text-xs transition"
                                >
                                    Tolak Pengajuan
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        Tidak ada antrean permintaan kuota mata kuliah saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. TAB 2: VALIDASI JALUR MBKM (BPMN: Kaprodi Memvalidasi Konversi MBKM & Menginput ke KRS) -->
    <div id="kpView_mbkm" class="kp-view hidden space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Validasi Berkas & Konversi 20 SKS MBKM</h3>
                <p class="text-xs text-slate-500 mt-0.5">BPMN Gateway "Konversi Disetujui?": Kaprodi memeriksa berkas MBKM mahasiswa. Jika setuju -> paket 20 SKS otomatis masuk ke KRS mahasiswa; jika tolak -> mahasiswa memperbaiki berkas.</p>
            </div>

            <div class="space-y-4">
                @forelse($mbkmApplications as $mb)
                    @php
                        $isPending = $mb->status === 'pending';
                    @endphp
                    <div class="p-5 rounded-2xl border {{ $isPending ? 'border-purple-200 bg-purple-50/30' : 'border-slate-100 bg-slate-50/50' }} space-y-3 text-xs">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div>
                                <span class="font-bold text-slate-700">{{ $mb->user->name }} (NIM: {{ $mb->user->identifier }} • Kelas {{ $mb->user->kelas }})</span>
                                <h4 class="font-extrabold text-slate-900 text-sm mt-0.5">
                                    {{ $mb->program_name }} — {{ $mb->mitra }}
                                </h4>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Posisi: <strong>{{ $mb->role_position }}</strong> • Durasi: {{ $mb->duration }}
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full font-extrabold text-[10px] border {{ $mb->status === 'approved' ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : ($mb->status === 'rejected' ? 'bg-rose-100 text-rose-900 border-rose-300' : 'bg-purple-100 text-purple-900 border-purple-300') }}">
                                {{ $mb->status === 'approved' ? 'Disahkan (20 SKS Diinput ke KRS)' : ($mb->status === 'rejected' ? 'Perlu Perbaikan' : 'Menunggu Validasi Kaprodi') }}
                            </span>
                        </div>

                        <div class="p-3 rounded-xl bg-white border border-slate-200/80 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-700">
                                <x-icon name="document-text" class="w-4 h-4 text-purple-600" />
                                <span>Lampiran Surat Penerimaan: <strong class="font-mono">{{ $mb->acceptance_letter_path }}</strong></span>
                            </div>
                            <span class="text-[10px] text-emerald-600 font-bold">Terverifikasi Otentik</span>
                        </div>

                        <!-- 20 SKS Conversion Package Preview -->
                        <div class="space-y-1">
                            <div class="font-bold text-slate-700 text-[11px]">Rancangan 5 Mata Kuliah Ekivalensi / Konversi (20 SKS):</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[10px]">
                                <div class="p-2 rounded-lg bg-slate-100/80">MBKM501 • Praktik Industri Telekomunikasi & Awan (4 SKS)</div>
                                <div class="p-2 rounded-lg bg-slate-100/80">MBKM502 • Proyek RPL Industri (6 SKS)</div>
                                <div class="p-2 rounded-lg bg-slate-100/80">MBKM503 • Analisis & Arsitektur Sistem Terapan (4 SKS)</div>
                                <div class="p-2 rounded-lg bg-slate-100/80">MBKM504 • Komunikasi Profesional & Budaya Kerja (3 SKS)</div>
                                <div class="p-2 rounded-lg bg-slate-100/80 col-span-2">MBKM505 • Etika Profesi & Tata Kelola Digital (3 SKS)</div>
                            </div>
                        </div>

                        @if($mb->rejection_note)
                            <div class="p-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-[11px]">
                                <strong>Catatan Perbaikan:</strong> {{ $mb->rejection_note }}
                            </div>
                        @endif

                        @if($isPending)
                            <div class="pt-2 border-t border-slate-200/60 flex items-center gap-2">
                                <button 
                                    type="button" 
                                    onclick="validateMbkmAction('{{ $mb->id }}', 'setuju', '{{ addslashes($mb->user->name) }}')"
                                    class="py-2 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5"
                                >
                                    <x-icon name="check-circle" class="w-4 h-4" />
                                    <span>Setujui & Inputkan 20 SKS Konversi ke KRS</span>
                                </button>
                                <button 
                                    type="button" 
                                    onclick="promptRejectMbkm('{{ $mb->id }}', '{{ addslashes($mb->user->name) }}')"
                                    class="py-2 px-3 rounded-xl border border-rose-200 hover:bg-rose-50 text-rose-700 font-bold text-xs transition"
                                >
                                    Minta Perbaikan Berkas
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        Tidak ada pengajuan program MBKM saat ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. TAB 3: MANAJEMEN MASA KRS & MATA KULIAH (BPMN Sub-process: Memasukkan Mata Kuliah) -->
    <div id="kpView_krs_management" class="kp-view hidden space-y-6">
        
        <!-- Top Action Card: Toggle Masa KRS -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Kendali Pembukaan Masa Pengisian KRS</h3>
                <p class="text-xs text-slate-500 mt-0.5">BPMN Gateway "Masa KRS Sudah Dibuka?": Jika belum -> Kaprodi masukkan jadwal/kuota lalu buka; jika sudah -> mahasiswa dapat login dan mengisi KRS.</p>
            </div>
            <button 
                type="button" 
                onclick="toggleMasaKrs()"
                class="px-5 py-2.5 rounded-xl text-xs font-black transition flex items-center gap-2 {{ $isKrsOpen ? 'bg-amber-500 hover:bg-amber-600 text-slate-950' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}"
            >
                <x-icon name="key" class="w-4 h-4" />
                <span>{{ $isKrsOpen ? 'Tutup Masa KRS' : 'Buka Masa KRS Sekarang' }}</span>
            </button>
        </div>

        <!-- Add Course Form -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Sub-process: Memasukkan Mata Kuliah</h3>
                    <p class="text-xs text-slate-500">Menambah mata kuliah beserta jadwal, ruang, dan kuotanya ke basis data SIAKAD</p>
                </div>
            </div>

            <form onsubmit="handleStoreCourse(event)" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">ID Unik MK</label>
                        <input type="text" id="addMkId" required placeholder="Contoh: MK10" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kode Mata Kuliah</label>
                        <input type="text" id="addMkCode" required placeholder="Contoh: IF24510" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 font-mono bg-slate-50/50" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Nama Mata Kuliah</label>
                        <input type="text" id="addMkName" required placeholder="Contoh: Pemrograman Internet of Things" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bobot SKS</label>
                        <input type="number" id="addMkSks" required min="1" max="6" value="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Rombel Kelas</label>
                        <input type="text" id="addMkKelas" required value="IF 7B" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kapasitas Kursi (Kuota)</label>
                        <input type="number" id="addMkQuota" required min="10" max="100" value="40" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Sifat Mata Kuliah</label>
                        <select id="addMkMandatory" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50">
                            <option value="1">Wajib</option>
                            <option value="0">Pilihan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Hari</label>
                        <select id="addMkDay" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jam Mulai - Selesai</label>
                        <div class="flex items-center gap-1">
                            <input type="text" id="addMkStart" value="09:30" class="w-1/2 px-2 py-2 rounded-xl border border-slate-200 text-center font-mono" />
                            <span>-</span>
                            <input type="text" id="addMkEnd" value="12:00" class="w-1/2 px-2 py-2 rounded-xl border border-slate-200 text-center font-mono" />
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Ruangan / Laboratorium</label>
                        <input type="text" id="addMkRoom" required value="Lab Rekayasa Perangkat Lunak" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Dosen Pengampu</label>
                        <input type="text" id="addMkLecturer" required value="Dr. Arik Kurniawati, S.Kom., M.T." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/50" />
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="py-2.5 px-5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm transition flex items-center gap-1.5">
                        <x-icon name="plus" class="w-4 h-4" />
                        <span>Simpan Mata Kuliah Baru</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table of Existing Courses -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 font-extrabold text-slate-900 text-sm">
                Daftar Mata Kuliah Terdaftar di SIAKAD
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                            <th class="py-3 px-4">Kode & Mata Kuliah</th>
                            <th class="py-3 px-3 text-center">SKS</th>
                            <th class="py-3 px-3">Kelas</th>
                            <th class="py-3 px-4">Jadwal & Ruang</th>
                            <th class="py-3 px-4">Dosen Pengampu</th>
                            <th class="py-3 px-3 text-center">Kapasitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($allCourses as $c)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4">
                                    <div class="font-extrabold text-slate-900">{{ $c->name }}</div>
                                    <div class="font-mono text-[10px] text-blue-700">{{ $c->code }}</div>
                                </td>
                                <td class="py-3 px-3 text-center font-bold">{{ $c->sks }}</td>
                                <td class="py-3 px-3"><span class="px-2 py-0.5 rounded font-extrabold text-[10px] bg-slate-100 text-slate-800">{{ $c->kelas }}</span></td>
                                <td class="py-3 px-4 text-slate-600">{{ $c->schedule }} ({{ $c->room }})</td>
                                <td class="py-3 px-4 text-slate-800">{{ $c->lecturer }}</td>
                                <td class="py-3 px-3 text-center font-mono font-bold {{ $c->enrolled >= $c->quota ? 'text-rose-600' : 'text-slate-800' }}">
                                    {{ $c->enrolled }}/{{ $c->quota }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- 5. TAB 4: ANALISIS RUANG & DOSEN -->
    <div id="kpView_analysis" class="kp-view hidden space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Lab & Rooms -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-2">
                    <x-icon name="building-office" class="w-5 h-5 text-blue-600" />
                    <h3 class="font-extrabold text-sm text-slate-900">Ketersediaan Laboratorium & Ruang Kuliah</h3>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($rooms as $r)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-900">{{ $r->name }}</div>
                                <div class="text-[10px] text-slate-400">Kapasitas Maksimal: {{ $r->capacity }} Kursi • Slot: {{ $r->slot }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-100 text-emerald-900">
                                {{ $r->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Lecturer Loading -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center gap-2">
                    <x-icon name="academic-cap" class="w-5 h-5 text-purple-600" />
                    <h3 class="font-extrabold text-sm text-slate-900">Beban Mengajar Dosen Pengampu S1 TI</h3>
                </div>
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($lecturers as $l)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-900">{{ $l->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $l->field }} • Beban: {{ $l->load }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-900">
                                {{ $l->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function switchKaprodiTab(tab) {
        document.querySelectorAll('.kp-view').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.sidebar-nav-btn').forEach(btn => {
            btn.classList.remove('bg-purple-50', 'text-purple-700');
            btn.classList.add('text-slate-600');
        });

        const targetView = document.getElementById('kpView_' + tab);
        const targetBtn = document.getElementById('kpNavBtn_' + tab);

        if (targetView) targetView.classList.remove('hidden');
        if (targetBtn) {
            targetBtn.classList.add('bg-purple-50', 'text-purple-700');
            targetBtn.classList.remove('text-slate-600');
        }
    }

    function toggleMasaKrs() {
        Swal.fire({
            title: 'Ubah Status Masa Pengisian KRS?',
            text: 'Perubahan status masa KRS akan divalidasi oleh Sistem SIAKAD dan disiarkan ke seluruh portal aktor.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Ubah Status',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("kaprodi.toggle_krs") }}', {
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
                            title: 'Status Masa KRS Diperbarui!',
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

    function executeQuotaDecision(requestId, action, courseName, addQuota) {
        const title = action === 'tambah_kuota' 
            ? `Tambah Kuota (+${addQuota} Kursi)?` 
            : `Buka Kelas Paralel Baru (${courseName} IF 7C)?`;
        
        const text = action === 'tambah_kuota'
            ? `Kapasitas kelas akan ditambah sebesar +${addQuota} kursi dan sistem otomatis menyiarkan notifikasi resmi ke mahasiswa.`
            : `Kelas paralel IF 7C baru akan dibuka dengan kuota ${addQuota} kursi di R. Gedung F-301.`;

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'tambah_kuota' ? '#2563eb' : '#9333ea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Eksekusi Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/kaprodi/evaluate-quota/${requestId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action, add_quota: addQuota })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Keputusan Dieksekusi!',
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

    function promptRejectQuota(requestId, courseName) {
        Swal.fire({
            title: `Tolak Permintaan Kuota ${courseName}?`,
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Contoh: Kapasitas laboratorium komputasi cerdas telah mencapai batas maksimal...',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Tolak Pengajuan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/kaprodi/evaluate-quota/${requestId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action: 'tolak', note: result.value })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Selesai', data.message, 'info').then(() => window.location.reload());
                });
            }
        });
    }

    function validateMbkmAction(mbkmId, action, studentName) {
        Swal.fire({
            title: `Setujui MBKM & Konversi 20 SKS?`,
            text: `Paket 20 SKS konversi program industri akan otomatis diinputkan ke KRS mahasiswa ${studentName}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Sahkan Konversi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/kaprodi/validate-mbkm/${mbkmId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Konversi Disetujui!',
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

    function promptRejectMbkm(mbkmId, studentName) {
        Swal.fire({
            title: `Minta Perbaikan Berkas MBKM?`,
            input: 'textarea',
            inputLabel: 'Catatan Kekurangan LoA / Berkas MBKM',
            inputPlaceholder: 'Contoh: Surat LoA belum mencantumkan jam kerja dan rincian modul industri...',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Kembalikan untuk Perbaikan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/kaprodi/validate-mbkm/${mbkmId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action: 'tolak', rejection_note: result.value })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire('Terkirim', data.message, 'info').then(() => window.location.reload());
                });
            }
        });
    }

    function handleStoreCourse(e) {
        e.preventDefault();
        const id = document.getElementById('addMkId').value;
        const code = document.getElementById('addMkCode').value;
        const name = document.getElementById('addMkName').value;
        const sks = document.getElementById('addMkSks').value;
        const kelas = document.getElementById('addMkKelas').value;
        const quota = document.getElementById('addMkQuota').value;
        const is_mandatory = document.getElementById('addMkMandatory').value;
        const day = document.getElementById('addMkDay').value;
        const time_start = document.getElementById('addMkStart').value;
        const time_end = document.getElementById('addMkEnd').value;
        const room = document.getElementById('addMkRoom').value;
        const lecturer = document.getElementById('addMkLecturer').value;

        fetch('{{ route("kaprodi.store_course") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id, code, name, sks, kelas, quota, is_mandatory, day, time_start, time_end, room, lecturer })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Mata Kuliah Disimpan!',
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
