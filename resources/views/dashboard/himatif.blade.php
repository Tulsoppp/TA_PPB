@extends('layouts.app', ['title' => 'Transformasi Proses Bisnis To-Be — SIAKAD UTM'])

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Hero Card Explanation -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 space-y-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-amber-500 text-slate-950 flex items-center justify-center font-black shadow-md shadow-amber-500/20">
                <x-icon name="sparkles" class="w-7 h-7" />
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-100 text-amber-950 text-[10px] font-bold">
                    <span>Business Process Improvement (BPI) — LAPORAN_TA.pdf Subbab 3.5</span>
                </span>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">
                    Otomasi Peran Himatif ke dalam SIAKAD Terpadu (Model To-Be)
                </h2>
            </div>
        </div>

        <p class="text-xs text-slate-600 leading-relaxed">
            Berdasarkan hasil analisis <em>Pain Point</em> dan usulan <strong>Business Process Improvement (BPI)</strong> pada Dokumen Proposal Proyek Akhir (Subbab 1.3 butir 6 dan Subbab 3.5.1), seluruh alur penampungan keluhan kuota dan penjadwalan perwalian yang sebelumnya dilakukan manual oleh organisasi mahasiswa (HIMATIF) melalui spreadsheet dan WhatsApp telah <strong>dieliminasi dan diotomasikan langsung ke dalam Sistem SIAKAD</strong>.
        </p>

        <!-- 3 Pilar Perbaikan To-Be Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <!-- Pilar 1 -->
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-2">
                <div class="font-extrabold text-rose-950 flex items-center gap-1.5">
                    <x-icon name="x-circle" class="w-4 h-4 text-rose-600" />
                    <span>1. Eliminate (Subbab 3.5.1)</span>
                </div>
                <p class="text-rose-900 text-[11px] leading-relaxed">
                    Menghapus peran perantara Himatif dalam merekap aspirasi kuota mahasiswa. Mahasiswa kini mengirim permintaan kuota langsung ke sistem SIAKAD.
                </p>
            </div>

            <!-- Pilar 2 -->
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 space-y-2">
                <div class="font-extrabold text-blue-950 flex items-center gap-1.5">
                    <x-icon name="check-badge" class="w-4 h-4 text-blue-600" />
                    <span>2. Automate (Subbab 3.5.1)</span>
                </div>
                <p class="text-blue-900 text-[11px] leading-relaxed">
                    Sistem SIAKAD secara otomatis memverifikasi riwayat KRS dan membandingkan kebutuhan riil mahasiswa sehingga Kaprodi hanya menerima data valid.
                </p>
            </div>

            <!-- Pilar 3 -->
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2">
                <div class="font-extrabold text-emerald-950 flex items-center gap-1.5">
                    <x-icon name="calendar" class="w-4 h-4 text-emerald-600" />
                    <span>3. Integrate (Subbab 3.5.2)</span>
                </div>
                <p class="text-emerald-900 text-[11px] leading-relaxed">
                    Penjadwalan perwalian diinputkan Dosen Wali langsung ke SIAKAD dan otomatis disiarkan ke dashboard mahasiswa tanpa spreadsheet terpisah.
                </p>
            </div>
        </div>

        <!-- Quick Jump to To-Be Portals -->
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-500">
                Pilih peran aktor di bawah untuk menguji alur proses bisnis To-Be pada SIAKAD:
            </div>
            <div class="flex items-center gap-2">
                @php
                    $mhsUser = \App\Models\User::where('role', 'mahasiswa')->first();
                    $dosenUser = \App\Models\User::where('role', 'dosen_wali')->first();
                    $kaprodiUser = \App\Models\User::where('role', 'kaprodi')->first();
                @endphp

                @if($kaprodiUser)
                    <form action="{{ route('login.quick') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $kaprodiUser->email }}">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-extrabold text-xs transition">
                            Portal Kaprodi
                        </button>
                    </form>
                @endif

                @if($dosenUser)
                    <form action="{{ route('login.quick') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $dosenUser->email }}">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs transition">
                            Portal Dosen Wali
                        </button>
                    </form>
                @endif

                @if($mhsUser)
                    <form action="{{ route('login.quick') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $mhsUser->email }}">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs transition">
                            Portal Mahasiswa
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
