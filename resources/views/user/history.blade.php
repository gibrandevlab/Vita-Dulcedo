@extends('layouts.app')

@section('content')
<section class="bg-slate-50 min-h-screen py-10" x-data="{ activeTab: 'donasi' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Saya</h1>
                <p class="text-slate-500 mt-1 text-sm">Lacak status donasi dan permohonan kunjungan Anda dengan mudah.</p>
            </div>
            
            <a href="{{ route('donasi') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Donasi Baru
            </a>
        </div>

        {{-- Card: Total Donasi Saya --}}
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-6 mb-8 relative overflow-hidden">
            <div class="absolute -right-6 -top-10 opacity-10">
                <svg class="w-48 h-48 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-sm font-semibold text-slate-500">Total Donasi Saya (Telah Disetujui)</p>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">Semua Waktu</span>
                </div>
                <p class="text-4xl font-extrabold text-emerald-600 tracking-tight mt-2">
                    Rp {{ number_format($totalDonasiSaya ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 mt-2 font-medium">Ini adalah akumulasi dari seluruh kebaikan Anda yang telah diverifikasi Panti Asuhan.</p>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-slate-200 pb-px">
            <button @click="activeTab = 'donasi'" :class="activeTab === 'donasi' ? 'border-emerald-500 text-emerald-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="border-b-2 px-4 py-3 text-sm transition-all focus:outline-none">
                Riwayat Donasi
            </button>
            <button @click="activeTab = 'kunjungan'" :class="activeTab === 'kunjungan' ? 'border-indigo-500 text-indigo-600 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'" class="border-b-2 px-4 py-3 text-sm transition-all focus:outline-none">
                Riwayat Kunjungan
            </button>
        </div>

        {{-- Panel: Donasi --}}
        <div x-show="activeTab === 'donasi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-init="$el.style.display = 'block'">
            @if($donations->isEmpty())
                <div class="bg-white rounded-3xl p-10 text-center border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Donasi</h3>
                    <p class="text-slate-500 text-sm">Anda belum melakukan donasi. Jadilah bagian dari kebaikan hari ini!</p>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Tanggal & Nominal</th>
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Pesan</th>
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($donations as $donasi)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-800">Rp {{ number_format($donasi->jumlah_donasi, 0, ',', '.') }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $donasi->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-600 italic line-clamp-2">{{ $donasi->pesan ?? 'Tanpa pesan.' }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($donasi->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Konfirmasi
                                            </span>
                                        @elseif($donasi->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Berhasil diverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Ditolak
                                            </span>
                                        @endif
                                        
                                        @if($donasi->catatan_admin)
                                            <p class="text-[11px] text-slate-500 mt-2 max-w-xs break-words">
                                                <span class="font-bold text-slate-700">Catatan Admin:</span> {{ $donasi->catatan_admin }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Panel: Kunjungan --}}
        <div x-cloak x-show="activeTab === 'kunjungan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            @if($visits->isEmpty())
                <div class="bg-white rounded-3xl p-10 text-center border border-slate-100 shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Kunjungan</h3>
                    <p class="text-slate-500 text-sm">Anda belum pernah membuat jadwal permohonan kunjungan.</p>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Kode & Jadwal Kunjungan</th>
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Detail</th>
                                    <th class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($visits as $visit)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block bg-slate-100 border border-slate-200 px-2 py-1 rounded font-mono font-extrabold text-slate-700 text-xs mb-2">
                                            {{ $visit->kode_kunjungan }}
                                        </span>
                                        <div class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($visit->tanggal_kunjungan)->translatedFormat('d F Y') }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ substr($visit->jam_mulai, 0, 5) }} - {{ substr($visit->jam_selesai, 0, 5) }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-slate-700">{{ $visit->nama_pengunjung }} <span class="font-normal text-slate-500">({{ $visit->jumlah_pengunjung }} Orang)</span></p>
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 max-w-sm">{{ $visit->tujuan_kunjungan }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visit->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($visit->status === 'approved')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Telah Disetujui
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Ditolak
                                            </span>
                                        @endif
                                        
                                        @if($visit->catatan_admin)
                                            <p class="text-[11px] text-slate-500 mt-2 max-w-xs break-words">
                                                <span class="font-bold text-slate-700">Catatan Admin:</span> {{ $visit->catatan_admin }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
