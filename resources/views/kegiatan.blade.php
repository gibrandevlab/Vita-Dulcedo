@extends('layouts.app')

@section('content')
<section class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kegiatan & Kunjungan</h1>
            <p class="text-slate-500 mt-1">Panti Asuhan Vita Dulcedo — Jadwal kegiatan dan permohonan kunjungan tamu.</p>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-5 rounded-2xl text-sm font-medium flex flex-col gap-4 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold text-slate-800 text-base">{{ session('success') }}</p>
                    @if(session('kode_kunjungan'))
                        <p class="mt-1.5 text-slate-600">Kode Unik Kunjungan Anda: <span class="bg-emerald-100/70 border border-emerald-200 px-2.5 py-0.5 rounded-lg font-mono font-extrabold text-emerald-900 select-all">{{ session('kode_kunjungan') }}</span></p>
                        <p class="mt-1 text-xs text-slate-500">Simpan kode ini untuk melacak status permohonan kunjungan Anda.</p>
                    @endif
                </div>
            </div>
            @if(session('wa_url'))
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-1">
                <a href="{{ session('wa_url') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#25D366] hover:bg-[#20ba56] text-white font-extrabold rounded-xl text-xs transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.567 1.485 5.396 1.486 5.43 0 9.849-4.385 9.852-9.776.002-2.611-1.015-5.064-2.864-6.919C17.082 2.09 14.636.85 12.013.85c-5.434 0-9.855 4.387-9.858 9.778-.002 1.962.518 3.878 1.505 5.576L2.6 20.354l4.047-1.2zm12.338-5.321c-.328-.165-1.942-.959-2.242-1.069-.3-.11-.519-.165-.736.165-.218.33-.843 1.069-1.033 1.289-.19.22-.38.247-.708.082-1.015-.508-1.745-.935-2.42-2.092-.121-.208-.121-.362.016-.484.218-.193.385-.441.517-.661.132-.22.066-.413-.033-.578-.1-.165-.736-1.762-1.008-2.422-.266-.64-.537-.552-.736-.562-.191-.01-.41-.011-.628-.011-.218 0-.573.082-.873.413-.3.33-1.145 1.116-1.145 2.721 0 1.605 1.169 3.159 1.332 3.379.163.22 2.299 3.511 5.568 4.922.778.336 1.385.538 1.859.689.782.248 1.493.213 2.057.129.628-.094 1.942-.794 2.215-1.56.273-.766.273-1.423.191-1.56-.082-.138-.3-.22-.628-.385z"/>
                    </svg>
                    Kirim Notifikasi WhatsApp ke Panti
                </a>
                <span class="text-xs text-slate-500 font-medium">Klik untuk mengirim notifikasi permohonan ke admin panti.</span>
            </div>
            @endif
        </div>
        @endif

        {{-- 3-Column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ===================================== --}}
            {{-- COLUMN 1: Kalender + Cek Status (Left) --}}
            {{-- ===================================== --}}
            <div class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">

                    {{-- Kalender Digital --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5"
                         x-data="kalender({{ $calendarData }})"
                         x-init="init()">

                        {{-- Header Kalender --}}
                        <div class="flex items-center justify-between mb-4">
                            <button @click="prevMonth()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <h2 class="text-sm font-extrabold text-slate-800" x-text="monthLabel"></h2>
                            <button @click="nextMonth()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 hover:text-slate-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        {{-- Nama Hari --}}
                        <div class="grid grid-cols-7 mb-2">
                            <template x-for="day in ['Min','Sen','Sel','Rab','Kam','Jum','Sab']">
                                <div class="text-center text-xs font-bold text-slate-400 py-1" x-text="day"></div>
                            </template>
                        </div>

                        {{-- Grid Tanggal --}}
                        <div class="grid grid-cols-7 gap-y-1">
                            {{-- Spacer untuk hari pertama --}}
                            <template x-for="s in firstDayOffset" :key="'s'+s">
                                <div></div>
                            </template>
                            {{-- Tanggal --}}
                            <template x-for="day in daysInMonth" :key="day">
                                <button
                                    @click="selectDate(day)"
                                    :class="{
                                        'bg-emerald-600 text-white font-extrabold shadow-sm': isSelected(day),
                                        'bg-emerald-50 text-emerald-700 font-bold ring-1 ring-emerald-200': isToday(day) && !isSelected(day),
                                        'text-slate-700 hover:bg-slate-100': !isSelected(day) && !isToday(day),
                                        'text-slate-300 cursor-default': false
                                    }"
                                    class="relative w-8 h-8 mx-auto flex flex-col items-center justify-center rounded-full text-xs transition-colors">
                                    <span x-text="day"></span>
                                    {{-- Dot indikator ada kegiatan --}}
                                    <template x-if="hasEvent(day)">
                                        <span :class="isSelected(day) ? 'bg-white' : 'bg-emerald-500'"
                                              class="absolute bottom-0.5 w-1 h-1 rounded-full"></span>
                                    </template>
                                </button>
                            </template>
                        </div>

                        {{-- Legend --}}
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-3 text-xs text-slate-400">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Ada kegiatan</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-100 ring-1 ring-emerald-300 inline-block"></span> Hari ini</span>
                        </div>
                    </div>

                    {{-- Card: Cek Status Kunjungan --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h2 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            Cek Status Kunjungan
                        </h2>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed font-medium">
                            Masukkan kode unik untuk melacak status permohonan kunjungan Anda.
                        </p>
                        <form action="{{ route('kegiatan') }}" method="GET" class="space-y-3">
                            <input type="text" name="kode" value="{{ $searchQuery ?? '' }}" placeholder="Contoh: ABC1234567890" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono uppercase tracking-wider text-center">
                            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-sm hover:shadow-indigo-500/20 hover:-translate-y-0.5 transition-all duration-300">
                                Cari Status
                            </button>
                            @if($searchQuery)
                                <a href="{{ route('kegiatan') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors pt-1">
                                    Bersihkan Pencarian
                                </a>
                            @endif
                        </form>
                    </div>

                </div>
            </div>

            {{-- ===================================== --}}
            {{-- COLUMN 2: Detail & Riwayat (Center) --}}
            {{-- ===================================== --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Hasil Cek Status Kunjungan --}}
                @if($searchQuery)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 {{ $searchResult ? ($searchResult->status === 'approved' ? 'border-l-emerald-500' : ($searchResult->status === 'rejected' ? 'border-l-red-500' : 'border-l-amber-500')) : 'border-l-rose-500' }}">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Hasil Pelacakan Kunjungan</h3>
                            <p class="text-xs text-slate-500">Status untuk kode: <span class="font-mono font-bold">{{ $searchQuery }}</span></p>
                        </div>
                        <a href="{{ route('kegiatan') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </div>

                    @if($searchResult)
                        @php
                            $words = explode(' ', $searchResult->nama_pengunjung);
                            $maskedWords = array_map(function($word) {
                                $len = strlen($word);
                                if ($len <= 2) return str_repeat('*', $len);
                                return substr($word, 0, 1) . str_repeat('*', $len - 2) . substr($word, -1);
                            }, $words);
                            $maskedName = implode(' ', $maskedWords);
                        @endphp
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-500 text-xs font-semibold">Nama Pengunjung</span>
                                <span class="text-slate-800 font-bold">{{ $maskedName }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-500 text-xs font-semibold">Tanggal Kunjungan</span>
                                <span class="text-slate-700 font-medium">{{ $searchResult->tanggal_kunjungan->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-500 text-xs font-semibold">Waktu</span>
                                <span class="text-slate-700 font-medium">{{ substr($searchResult->jam_mulai, 0, 5) }} – {{ substr($searchResult->jam_selesai, 0, 5) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                <span class="text-slate-500 text-xs font-semibold">Jumlah Pengunjung</span>
                                <span class="text-slate-700 font-medium">{{ $searchResult->jumlah_pengunjung }} orang</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-slate-500 text-xs font-semibold">Status</span>
                                @if($searchResult->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">Menunggu Persetujuan</span>
                                @elseif($searchResult->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Disetujui</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">Ditolak</span>
                                @endif
                            </div>
                            <div class="p-3.5 rounded-xl text-xs font-medium mt-2 leading-relaxed {{ $searchResult->status === 'approved' ? 'bg-emerald-50/70 text-emerald-800 border border-emerald-100/50' : ($searchResult->status === 'rejected' ? 'bg-red-50/70 text-red-800 border border-red-100/50' : 'bg-amber-50/70 text-amber-800 border border-amber-100/50') }}">
                                @if($searchResult->status === 'pending')
                                    Permohonan kunjungan Anda sedang ditinjau oleh pihak panti. Silakan tunggu konfirmasi lebih lanjut.
                                @elseif($searchResult->status === 'approved')
                                    Permohonan kunjungan Anda telah disetujui! Harap datang tepat waktu sesuai jadwal yang telah ditentukan.
                                @else
                                    Permohonan kunjungan Anda ditolak. Silakan hubungi admin panti untuk informasi lebih lanjut.
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-100 text-xs font-medium text-center">
                            Kode <span class="font-mono font-bold">{{ $searchQuery }}</span> tidak ditemukan. Periksa kembali kode unik kunjungan Anda.
                        </div>
                    @endif
                </div>
                @endif

                {{-- Detail Kegiatan Tanggal Dipilih (Alpine.js driven) --}}
                <div x-data="kalenderDetail({{ $calendarData }})" x-init="init()">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Jadwal Hari Ini</h3>
                                <p class="text-xs text-slate-500" x-text="selectedLabel"></p>
                            </div>
                        </div>

                        <div class="p-6">
                            <template x-if="selectedEvents.length === 0">
                                <div class="text-center py-6">
                                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-sm text-slate-400 font-medium">Tidak ada kegiatan terjadwal</p>
                                    <p class="text-xs text-slate-300 mt-0.5">Klik tanggal di kalender untuk melihat jadwal</p>
                                </div>
                            </template>
                            <template x-if="selectedEvents.length > 0">
                                <div class="space-y-3">
                                    <template x-for="(ev, i) in selectedEvents" :key="i">
                                        <div class="flex gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                            <div class="flex-shrink-0 text-center">
                                                <div class="bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg whitespace-nowrap" x-text="ev.jam_mulai + ' – ' + ev.jam_selesai"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800" x-text="ev.tujuan"></p>
                                                <p class="text-xs text-slate-500 mt-0.5" x-text="ev.jumlah + ' orang pengunjung'"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Kunjungan Disetujui --}}
                <div id="riwayat" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800">Jadwal Kunjungan Disetujui</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kunjungan yang telah dikonfirmasi pihak panti.</p>
                    </div>

                    @if($riwayatKunjungan->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tujuan</th>
                                    <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Jml</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($riwayatKunjungan as $kunjungan)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3 text-slate-700 font-medium whitespace-nowrap text-xs">
                                        {{ $kunjungan->tanggal_kunjungan->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 font-medium whitespace-nowrap text-xs">
                                        {{ substr($kunjungan->jam_mulai, 0, 5) }}–{{ substr($kunjungan->jam_selesai, 0, 5) }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-700 text-xs max-w-[160px] truncate">
                                        {{ $kunjungan->tujuan_kunjungan }}
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 font-semibold text-center text-xs">
                                        {{ $kunjungan->jumlah_pengunjung }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($riwayatKunjungan->hasPages())
                    <div class="px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-400">Halaman {{ $riwayatKunjungan->currentPage() }} dari {{ $riwayatKunjungan->lastPage() }}</p>
                        <div class="flex items-center gap-1">
                            @if($riwayatKunjungan->onFirstPage())
                                <span class="px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">‹ Prev</span>
                            @else
                                <a href="{{ $riwayatKunjungan->previousPageUrl() }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">‹ Prev</a>
                            @endif
                            @foreach($riwayatKunjungan->getUrlRange(1, $riwayatKunjungan->lastPage()) as $page => $url)
                                @if($page == $riwayatKunjungan->currentPage())
                                    <span class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-lg">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if($riwayatKunjungan->hasMorePages())
                                <a href="{{ $riwayatKunjungan->nextPageUrl() }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">Next ›</a>
                            @else
                                <span class="px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">Next ›</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @else
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">
                        Belum ada kunjungan yang disetujui.
                    </div>
                    @endif
                </div>

            </div>

            {{-- ============================================= --}}
            {{-- COLUMN 3: Form Request Kunjungan (Right) --}}
            {{-- ============================================= --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:sticky lg:top-6">

                    @auth
                        <h2 class="text-base font-bold text-slate-800 mb-1">Ajukan Permohonan Kunjungan</h2>
                        <p class="text-xs text-slate-500 mb-6">Isi form berikut untuk mengajukan kunjungan ke panti. Admin akan meninjau permohonan Anda.</p>

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-medium mb-5">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('kunjungan.store') }}" method="POST" class="space-y-4">
                            @csrf

                            {{-- Nama Pengunjung --}}
                            <div>
                                <label for="nama_pengunjung" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap Pengunjung</label>
                                <input type="text" id="nama_pengunjung" name="nama_pengunjung"
                                       value="{{ old('nama_pengunjung', Auth::user()->name ?? '') }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all"
                                       placeholder="Nama lengkap Anda">
                            </div>



                            {{-- Jumlah Pengunjung --}}
                            <div>
                                <label for="jumlah_pengunjung" class="block text-sm font-bold text-slate-700 mb-1.5">Jumlah Pengunjung</label>
                                <input type="number" id="jumlah_pengunjung" name="jumlah_pengunjung"
                                       value="{{ old('jumlah_pengunjung', 1) }}" required min="1" max="100"
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all"
                                       placeholder="Jumlah orang">
                            </div>

                            {{-- Tanggal Kunjungan --}}
                            <div>
                                <label for="tanggal_kunjungan" class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal Kunjungan</label>
                                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan"
                                       value="{{ old('tanggal_kunjungan') }}" required
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                            </div>

                            {{-- Jam Mulai & Selesai --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="jam_mulai" class="block text-sm font-bold text-slate-700 mb-1.5">Jam Mulai</label>
                                    <input type="time" id="jam_mulai" name="jam_mulai"
                                           value="{{ old('jam_mulai', '08:00') }}" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                </div>
                                <div>
                                    <label for="jam_selesai" class="block text-sm font-bold text-slate-700 mb-1.5">Jam Selesai</label>
                                    <input type="time" id="jam_selesai" name="jam_selesai"
                                           value="{{ old('jam_selesai', '10:00') }}" required
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                </div>
                            </div>

                            {{-- Tujuan Kunjungan --}}
                            <div>
                                <label for="tujuan_kunjungan" class="block text-sm font-bold text-slate-700 mb-1.5">Tujuan Kunjungan</label>
                                <textarea id="tujuan_kunjungan" name="tujuan_kunjungan" rows="3" required
                                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all resize-none"
                                          placeholder="Jelaskan tujuan dan maksud kunjungan Anda...">{{ old('tujuan_kunjungan') }}</textarea>
                            </div>

                            {{-- Submit --}}
                            <div class="pt-1">
                                <button type="submit" class="w-full py-3.5 px-6 bg-[#009664] hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-emerald-500/30 text-sm">
                                    Kirim Permohonan Kunjungan
                                </button>
                            </div>
                        </form>

                        <div class="mt-5 pt-5 border-t border-slate-100">
                            <p class="text-xs text-slate-400 leading-relaxed">
                                Permohonan Anda akan ditinjau oleh tim panti. Setelah mengirim, gunakan kode unik yang diberikan untuk melacak status permohonan.
                            </p>
                        </div>

                    @else
                        {{-- CTA Login --}}
                        <div class="text-center py-6">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-5">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h2 class="text-base font-extrabold text-slate-800 mb-2">Login Diperlukan</h2>
                            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                                Untuk mengajukan permohonan kunjungan, Anda harus masuk ke akun Anda terlebih dahulu.
                            </p>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 px-6 bg-[#009664] hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition-all duration-300 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Masuk / Login
                            </a>
                            <p class="text-xs text-slate-400 mt-4">Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-600 font-bold hover:underline">Daftar sekarang</a></p>
                        </div>
                    @endauth

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===================================== --}}
{{-- Alpine.js: Kalender Digital           --}}
{{-- ===================================== --}}
<script>
function kalender(calendarData) {
    return {
        calendarData: calendarData,
        currentYear: 0,
        currentMonth: 0,
        selectedDay: 0,
        monthLabel: '',
        firstDayOffset: 0,
        daysInMonth: 0,

        init() {
            const today = new Date();
            this.currentYear  = today.getFullYear();
            this.currentMonth = today.getMonth(); // 0-indexed
            this.selectedDay  = today.getDate();
            this.updateCalendar();
            // Broadcast tanggal hari ini ke detail component
            this.$nextTick(() => this.broadcastDate(today.getDate()));
        },

        updateCalendar() {
            const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            this.monthLabel = monthNames[this.currentMonth] + ' ' + this.currentYear;

            const firstDay = new Date(this.currentYear, this.currentMonth, 1).getDay(); // 0=Sun
            this.firstDayOffset = firstDay;

            const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            this.daysInMonth = lastDay;
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
            this.selectedDay = 0;
            this.updateCalendar();
            this.broadcastDate(0);
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
            this.selectedDay = 0;
            this.updateCalendar();
            this.broadcastDate(0);
        },

        selectDate(day) {
            this.selectedDay = day;
            this.broadcastDate(day);
        },

        isSelected(day) {
            return this.selectedDay === day;
        },

        isToday(day) {
            const today = new Date();
            return today.getFullYear() === this.currentYear &&
                   today.getMonth() === this.currentMonth &&
                   today.getDate() === day;
        },

        hasEvent(day) {
            const key = this.dateKey(day);
            return !!this.calendarData[key];
        },

        dateKey(day) {
            const m = String(this.currentMonth + 1).padStart(2, '0');
            const d = String(day).padStart(2, '0');
            return `${this.currentYear}-${m}-${d}`;
        },

        broadcastDate(day) {
            const key = day > 0 ? this.dateKey(day) : null;
            window.dispatchEvent(new CustomEvent('kalender-date-selected', {
                detail: { key: key, day: day, month: this.currentMonth + 1, year: this.currentYear }
            }));
        }
    };
}

function kalenderDetail(calendarData) {
    return {
        calendarData: calendarData,
        selectedEvents: [],
        selectedLabel: 'Pilih tanggal di kalender',

        init() {
            window.addEventListener('kalender-date-selected', (e) => {
                const { key, day, month, year } = e.detail;
                if (!key || !day) {
                    this.selectedEvents = [];
                    this.selectedLabel  = 'Pilih tanggal di kalender';
                    return;
                }
                this.selectedEvents = this.calendarData[key] || [];
                const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                this.selectedLabel = day + ' ' + monthNames[month - 1] + ' ' + year;
            });
        }
    };
}
</script>
@endsection
