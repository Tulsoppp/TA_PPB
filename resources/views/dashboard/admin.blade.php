@extends('layouts.app', ['title' => 'Portal Admin Prodi — SIAKAD KRS UTM'])

@section('content')
<div class="space-y-8">

    <!-- 1. TOP STAT CARDS (Matching reference.webp 3-Card Row) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Card 1: Full Classes Count (Orange Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="exclamation-triangle" class="w-5 h-5 text-orange-500" />
                    <span>Kelas Berstatus Penuh</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Kuota Habis Terpakai (40/40)</div>
                <div class="text-3xl font-extrabold text-rose-600 mt-0.5 tracking-tight">
                    {{ $stats['full_classes'] }} <span class="text-sm font-semibold text-slate-400">Kelas</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Prioritas Penanganan</span>
                    <span class="font-bold text-rose-600">Mendesak</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium">MK Terdampak</span>
                    <span class="font-bold text-slate-800">Kecerdasan Buatan</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Incoming Himatif Proposals (Purple Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="chat-bubble" class="w-5 h-5 text-purple-600" />
                    <span>Usulan Advokasi Himatif</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <div class="text-[11px] font-semibold text-slate-400">Menunggu Tindak Lanjut Prodi</div>
                <div class="text-3xl font-extrabold text-amber-600 mt-0.5 tracking-tight">
                    {{ $stats['incoming_proposals'] }} <span class="text-sm font-semibold text-slate-400">Berkas</span>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Opsi Tindakan</span>
                    <span class="font-bold text-blue-600">Ekspansi Kuota / Kelas Baru</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium">Terselesaikan</span>
                    <span class="font-bold text-emerald-700">{{ $stats['resolved'] }} Berkas</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Active Classes (Green Theme) -->
        <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 font-bold text-slate-800 text-sm">
                    <x-icon name="book-open" class="w-5 h-5 text-emerald-600" />
                    <span>Total Kelas Berjalan</span>
                </div>
                <x-icon name="chevron-right" class="w-4 h-4 text-slate-300" />
            </div>

            <div>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Jumlah rombongan belajar aktif pada Program Studi S1 Teknik Informatika.
                </p>
                <div class="mt-3 w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="h-3 rounded-full bg-blue-600 transition-all duration-500" style="width: 85%"></div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="font-extrabold text-slate-900">{{ $stats['total_classes'] }} Kelas Aktif</span>
                <span class="font-bold text-blue-600">Semester 5 Ganjil</span>
            </div>
        </div>

    </div>

    <!-- 2. TAB 1: USULAN DARI HIMATIF -->
    <div id="admView_proposals" class="adm-view space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Usulan Advokasi dari Himatif</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar rekapitulasi keluhan mahasiswa yang diteruskan ke Tata Usaha Prodi</p>
            </div>

            <div class="space-y-3">
                @forelse($proposals as $prop)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-blue-700">{{ $prop->course->code }}</span>
                                <h4 class="text-base font-black text-slate-900">{{ $prop->course->name }}</h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold {{ $prop->status === 'Menunggu Keputusan Prodi' ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-900' }}">
                                    {{ $prop->status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed">{{ $prop->notes }}</p>
                            <div class="text-[11px] text-slate-400 font-mono">
                                Mahasiswa Terdampak: <strong>{{ $prop->student_count }} Orang</strong> • Kuota Saat Ini: {{ $prop->course->enrolled }}/{{ $prop->course->quota }}
                            </div>
                        </div>

                        <div class="shrink-0">
                            @if($prop->status === 'Menunggu Keputusan Prodi')
                                <button type="button" 
                                    onclick="openAnalysisForCourse('{{ $prop->course->id }}')" 
                                    class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-2"
                                >
                                    <x-icon name="sparkles" class="w-4 h-4 text-white" />
                                    <span>Analisis & Eksekusi</span>
                                </button>
                            @else
                                <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                                    <span>Telah Ditindaklanjuti</span>
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">Belum ada usulan advokasi dari Himatif.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. TAB 2: ANALISIS RUANG/DOSEN & FORM EKSEKUSI -->
    <div id="admView_analysis" class="adm-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Capacity Data -->
        <div class="lg:col-span-6 space-y-6">
            
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900">Kapasitas Ruang & Laboratorium</h4>
                        <p class="text-[11px] text-slate-400">Data ketersediaan ruang untuk ekspansi kuota</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Tersedia</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                                <th class="py-2 px-3">Ruang / Lab</th>
                                <th class="py-2 px-2 text-center">Kapasitas</th>
                                <th class="py-2 px-3">Slot Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rooms as $rm)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2 px-3 font-bold text-slate-800">{{ $rm->name }}</td>
                                    <td class="py-2 px-2 text-center font-extrabold text-blue-600">{{ $rm->capacity }} Kursi</td>
                                    <td class="py-2 px-3 text-slate-500 text-[11px]">{{ $rm->slot }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-900">Kesiapan & Beban Dosen Pengampu</h4>
                        <p class="text-[11px] text-slate-400">Evaluasi SKS mengajar dosen calon kelas paralel</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">Siap</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                                <th class="py-2 px-3">Nama Dosen</th>
                                <th class="py-2 px-2 text-center">Beban</th>
                                <th class="py-2 px-3">Bidang Keahlian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($lecturers as $lec)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2 px-3 font-bold text-slate-800">{{ $lec->name }}</td>
                                    <td class="py-2 px-2 text-center font-bold text-slate-600">{{ $lec->load }}</td>
                                    <td class="py-2 px-3 text-slate-500 text-[11px]">{{ $lec->field }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Right: Execution Form -->
        <div class="lg:col-span-6 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Formulir Eksekusi Keputusan Prodi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Terapkan penambahan kuota (+15 kursi) atau pembukaan rombel paralel baru</p>
            </div>

            <div>
                <label for="admTargetCourse" class="block text-xs font-bold text-slate-700 mb-1">Mata Kuliah Terdampak</label>
                <select id="admTargetCourse" onchange="updateCourseSummary()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 bg-slate-50/50">
                    @foreach($allCourses as $c)
                        <option value="{{ $c->id }}" data-quota="{{ $c->quota }}" data-enrolled="{{ $c->enrolled }}" data-kelas="{{ $c->kelas }}" data-code="{{ $c->code }}">
                            {{ $c->code }} - {{ $c->name }} ({{ $c->enrolled }}/{{ $c->quota }} {{ $c->enrolled >= $c->quota ? '• PENUH' : '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="courseSummaryBox" class="p-3.5 rounded-2xl bg-blue-50 border border-blue-200 text-xs space-y-1"></div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Pilihan Bentuk Keputusan:</label>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <label class="p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-blue-50 border-blue-300 ring-2 ring-blue-500/20" id="optLabelQuota">
                        <input type="radio" name="solutionType" value="add_quota" checked onchange="toggleSolutionForm('add_quota')" class="mt-0.5" />
                        <div>
                            <div class="font-extrabold text-slate-900">Tambah Kuota (+15 Kursi)</div>
                            <div class="text-[10px] text-slate-500 mt-0.5">Ekspansi kuota kelas berjalan.</div>
                        </div>
                    </label>

                    <label class="p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-slate-50 border-slate-200 hover:bg-slate-100" id="optLabelParallel">
                        <input type="radio" name="solutionType" value="new_parallel" onchange="toggleSolutionForm('new_parallel')" class="mt-0.5" />
                        <div>
                            <div class="font-extrabold text-slate-900">Buka Kelas Baru (IF 7C)</div>
                            <div class="text-[10px] text-slate-500 mt-0.5">Rombel paralel jadwal baru.</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Option A Fields -->
            <div id="formAddQuotaSection" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Tambahan</label>
                        <input type="number" id="addQuotaAmount" value="15" min="5" max="30" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ruang Laboratorium</label>
                        <select id="addQuotaRoom" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 bg-slate-50/50">
                            @foreach($rooms as $rm)
                                <option value="{{ $rm->id }}">{{ $rm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Option B Fields -->
            <div id="formNewParallelSection" class="hidden space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kelas Baru</label>
                        <input type="text" id="newClassName" value="IF 7C" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 bg-slate-50/50" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Kursi</label>
                        <input type="number" id="newClassQuota" value="35" min="15" max="50" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-900 bg-slate-50/50" />
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal & Ruang Baru</label>
                    <select id="newClassSchedule" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 bg-slate-50/50">
                        <option value="Jumat, 13.00 - 15.30 • R. Gedung F-301">Jumat, 13.00 - 15.30 • R. Gedung F-301</option>
                        <option value="Sabtu, 08.00 - 10.30 • R. Gedung F-302">Sabtu, 08.00 - 10.30 • R. Gedung F-302</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Dosen Pengampu</label>
                    <select id="newClassLecturer" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 bg-slate-50/50">
                        @foreach($lecturers as $lec)
                            <option value="{{ $lec->name }}">{{ $lec->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Keputusan Resmi:</label>
                <textarea id="adminDecisionNote" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-600 bg-slate-50/50">Kapasitas kuota mata kuliah telah ditambah +15 kursi. Mahasiswa dipersilakan mengisi KRS.</textarea>
            </div>

            <button type="button" onclick="executeAdminAction()" class="w-full py-3.5 px-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                <x-icon name="sparkles" class="w-4 h-4 text-white" />
                <span>Eksekusi Keputusan & Beri Tahu Himatif</span>
            </button>
        </div>

    </div>

    <!-- 4. TAB 3: MANAJEMEN KELAS & LOG -->
    <div id="admView_courses" class="adm-view hidden grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Daftar Seluruh Kelas Aktif</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemantauan keterisian kuota seluruh mata kuliah prodi</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-bold">
                            <th class="py-2.5 px-3">Kode & Nama MK</th>
                            <th class="py-2.5 px-2 text-center">Kelas</th>
                            <th class="py-2.5 px-2 text-center">SKS</th>
                            <th class="py-2.5 px-3">Dosen</th>
                            <th class="py-2.5 px-3 text-center">Kapasitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($allCourses as $c)
                            @php
                                $isFull = $c->enrolled >= $c->quota;
                            @endphp
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-900">{{ $c->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $c->code }} • {{ $c->schedule }}</div>
                                </td>
                                <td class="py-3 px-2 text-center font-bold">
                                    <span class="px-2 py-0.5 rounded text-[10px] {{ str_contains($c->kelas, 'IF 7C') ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $c->kelas }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-center font-bold">{{ $c->sks }}</td>
                                <td class="py-3 px-3 text-slate-700">{{ $c->lecturer }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="font-mono font-bold {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">{{ $c->enrolled }}/{{ $c->quota }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Rekam Jejak Admin Prodi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Audit log penambahan kuota dan pembukaan kelas</p>
            </div>

            <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                @forelse($logs as $log)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-slate-900">{{ $log->title }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 text-[11px] leading-relaxed">{{ $log->detail }}</p>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">Belum ada riwayat aktivitas.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchAdminTab(tabName) {
        ['proposals', 'analysis', 'courses'].forEach(t => {
            const el = document.getElementById(`admView_${t}`);
            const btn = document.getElementById(`admNavBtn_${t}`);
            if (t === tabName) {
                if (el) el.classList.remove('hidden');
                if (btn) btn.className = 'sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition bg-blue-50 text-blue-600';
            } else {
                if (el) el.classList.add('hidden');
                if (btn) btn.className = 'sidebar-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-xs transition text-slate-600 hover:bg-slate-50 hover:text-slate-900';
            }
        });
    }

    function openAnalysisForCourse(courseId) {
        switchAdminTab('analysis');
        const sel = document.getElementById('admTargetCourse');
        if (sel) {
            sel.value = courseId;
            updateCourseSummary();
        }
    }

    function updateCourseSummary() {
        const sel = document.getElementById('admTargetCourse');
        const opt = sel.options[sel.selectedIndex];
        const quota = opt.getAttribute('data-quota');
        const enrolled = opt.getAttribute('data-enrolled');
        const kelas = opt.getAttribute('data-kelas');
        const code = opt.getAttribute('data-code');

        const box = document.getElementById('courseSummaryBox');
        box.innerHTML = `
            <div class="flex items-center justify-between font-bold text-blue-900">
                <span>Status Kelas (${kelas}):</span>
                <span class="${enrolled >= quota ? 'text-rose-600 font-black' : 'text-blue-900'}">${enrolled} / ${quota} Kursi</span>
            </div>
            <div class="text-[11px] text-blue-700">Kode: <strong>${code}</strong> • Sisa Kuota: <strong>${Math.max(0, quota - enrolled)} kursi</strong></div>
        `;
    }

    function toggleSolutionForm(type) {
        const addSection = document.getElementById('formAddQuotaSection');
        const parallelSection = document.getElementById('formNewParallelSection');
        const labelQuota = document.getElementById('optLabelQuota');
        const labelParallel = document.getElementById('optLabelParallel');

        if (type === 'add_quota') {
            addSection.classList.remove('hidden');
            parallelSection.classList.add('hidden');
            labelQuota.className = 'p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-blue-50 border-blue-300 ring-2 ring-blue-500/20';
            labelParallel.className = 'p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-slate-50 border-slate-200 hover:bg-slate-100';
        } else {
            addSection.classList.add('hidden');
            parallelSection.classList.remove('hidden');
            labelParallel.className = 'p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-blue-50 border-blue-300 ring-2 ring-blue-500/20';
            labelQuota.className = 'p-3.5 rounded-2xl border cursor-pointer transition flex items-start gap-2.5 solution-option bg-slate-50 border-slate-200 hover:bg-slate-100';
        }
    }

    function executeAdminAction() {
        const courseId = document.getElementById('admTargetCourse').value;
        const solutionType = document.querySelector('input[name="solutionType"]:checked').value;
        const note = document.getElementById('adminDecisionNote').value;

        if (solutionType === 'add_quota') {
            const addQuota = document.getElementById('addQuotaAmount').value;
            const roomId = document.getElementById('addQuotaRoom').value;

            Swal.fire({
                title: 'Eksekusi Tambah Kuota (+15)?',
                text: `Kapasitas kuota kelas akan ditambah sebesar +${addQuota} kursi.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tambah Kuota',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.execute_quota") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            course_id: courseId,
                            add_quota: addQuota,
                            room_id: roomId,
                            note: note
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil Ditambahkan', data.message, 'success')
                                .then(() => window.location.reload());
                        }
                    });
                }
            });
        } else {
            const newClassName = document.getElementById('newClassName').value;
            const newClassQuota = document.getElementById('newClassQuota').value;
            const newSchedule = document.getElementById('newClassSchedule').value;
            const newLecturer = document.getElementById('newClassLecturer').value;

            Swal.fire({
                title: `Buka Kelas Baru (${newClassName})?`,
                text: `Kelas paralel baru akan dibuka dengan kuota ${newClassQuota} kursi.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Buka Kelas',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("admin.execute_parallel") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            base_course_id: courseId,
                            new_class_name: newClassName,
                            new_class_quota: newClassQuota,
                            new_class_schedule: newSchedule,
                            new_class_lecturer: newLecturer,
                            note: note
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Kelas Dibuka', data.message, 'success')
                                .then(() => window.location.reload());
                        }
                    });
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCourseSummary();
    });
</script>
@endpush
