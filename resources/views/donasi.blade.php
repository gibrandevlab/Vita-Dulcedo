@extends('layouts.app')

@section('content')
<section class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Donasi Transparan</h1>
            <p class="text-slate-500 mt-1">Panti Asuhan Vita Dulcedo — Setiap rupiah tercatat dan dipertanggungjawabkan.</p>
        </div>

        {{-- Tabs Navigation --}}
        <div class="mb-8 flex gap-2 border-b border-slate-200">
            <button id="tab-program" onclick="switchTab('program')" class="tab-btn px-6 py-3 font-bold text-slate-600 border-b-2 border-transparent hover:border-slate-300 hover:text-slate-800 transition-all active-tab" data-tab="program">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Program Bantuan
            </button>
            <button id="tab-donasi" onclick="switchTab('donasi')" class="tab-btn px-6 py-3 font-bold text-slate-600 border-b-2 border-transparent hover:border-slate-300 hover:text-slate-800 transition-all" data-tab="donasi">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Buat Donasi
            </button>
        </div>

        {{-- Active Campaigns (Kebutuhan Panti) --}}
        @if(isset($campaigns) && $campaigns->count() > 0)
        <div id="content-program" class="mb-12 tab-content active-content">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Program Bantuan Mendesak</h2>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                    {{ $campaigns->count() }} Program Aktif
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campaigns as $campaign)
                @php
                    $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
                @endphp
                <div class="relative group rounded-3xl overflow-hidden bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col h-full transform hover:-translate-y-1">
                    {{-- Glassmorphism Effect Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 to-emerald-50/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>

                    {{-- Image --}}
                    <div class="h-48 w-full bg-slate-100 relative overflow-hidden">
                        @if($campaign->image)
                            <img src="{{ Storage::url($campaign->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-emerald-400 flex items-center justify-center text-white">
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        @endif
                        @if($campaign->deadline)
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-slate-800 shadow-sm flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ \Carbon\Carbon::parse($campaign->deadline)->diffForHumans() }}
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col relative z-10">
                        <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $campaign->title }}</h3>
                        <p class="text-sm text-slate-500 mb-6 line-clamp-3">{{ $campaign->description }}</p>

                        <div class="mt-auto space-y-3">
                            <div class="flex justify-between items-end mb-1">
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 mb-0.5">Terkumpul</p>
                                    <p class="text-sm font-extrabold text-emerald-600">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-slate-500 mb-0.5">Target</p>
                                    <p class="text-xs font-bold text-slate-700">Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full relative" style="width: {{ $percentage }}%">
                                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                </div>
                            </div>

                            <button onclick="selectCampaign({{ $campaign->id }}, '{{ addslashes($campaign->title) }}')" class="w-full mt-4 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-sm font-bold rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2 z-20 relative">
                                <span>Donasi Program Ini</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($campaigns->hasPages())
            <div class="mt-8 flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">
                    Halaman {{ $campaigns->currentPage() }} dari {{ $campaigns->lastPage() }} • Menampilkan {{ $campaigns->count() }} dari {{ $campaigns->total() }} program
                </p>
                <div class="flex items-center gap-2">
                    @if($campaigns->onFirstPage())
                        <span class="px-3.5 py-2 text-sm font-bold text-slate-300 bg-slate-100 rounded-lg border border-slate-200 cursor-not-allowed">‹ Sebelumnya</span>
                    @else
                        <a href="{{ $campaigns->previousPageUrl() }}" class="px-3.5 py-2 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">‹ Sebelumnya</a>
                    @endif

                    @foreach($campaigns->getUrlRange(1, $campaigns->lastPage()) as $page => $url)
                        @if($page == $campaigns->currentPage())
                            <span class="px-3.5 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3.5 py-2 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($campaigns->hasMorePages())
                        <a href="{{ $campaigns->nextPageUrl() }}" class="px-3.5 py-2 text-sm font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">Selanjutnya ›</a>
                    @else
                        <span class="px-3.5 py-2 text-sm font-bold text-slate-300 bg-slate-100 rounded-lg border border-slate-200 cursor-not-allowed">Selanjutnya ›</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- DONATION TAB CONTENT --}}
        <div id="content-donasi" class="tab-content hidden">

        {{-- Flash Message --}}
        @if (session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-5 rounded-2xl text-sm font-medium flex flex-col gap-4 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-bold text-slate-800 text-base">{{ session('success') }}</p>
                    @if(session('kode_donasi'))
                        <p class="mt-1.5 text-slate-600">Kode Unik Donasi Anda: <span class="bg-emerald-100/70 border border-emerald-200 px-2.5 py-0.5 rounded-lg font-mono font-extrabold text-emerald-900 select-all">{{ session('kode_donasi') }}</span></p>
                    @endif
                </div>
            </div>
            @if(session('wa_url'))
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mt-1">
                <a href="{{ session('wa_url') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#25D366] hover:bg-[#20ba56] text-white font-extrabold rounded-xl text-xs transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.567 1.485 5.396 1.486 5.43 0 9.849-4.385 9.852-9.776.002-2.611-1.015-5.064-2.864-6.919C17.082 2.09 14.636.85 12.013.85c-5.434 0-9.855 4.387-9.858 9.778-.002 1.962.518 3.878 1.505 5.576L2.6 20.354l4.047-1.2zm12.338-5.321c-.328-.165-1.942-.959-2.242-1.069-.3-.11-.519-.165-.736.165-.218.33-.843 1.069-1.033 1.289-.19.22-.38.247-.708.082-1.015-.508-1.745-.935-2.42-2.092-.121-.208-.121-.362.016-.484.218-.193.385-.441.517-.661.132-.22.066-.413-.033-.578-.1-.165-.736-1.762-1.008-2.422-.266-.64-.537-.552-.736-.562-.191-.01-.41-.011-.628-.011-.218 0-.573.082-.873.413-.3.33-1.145 1.116-1.145 2.721 0 1.605 1.169 3.159 1.332 3.379.163.22 2.299 3.511 5.568 4.922.778.336 1.385.538 1.859.689.782.248 1.493.213 2.057.129.628-.094 1.942-.794 2.215-1.56.273-.766.273-1.423.191-1.56-.082-.138-.3-.22-.628-.385z"/>
                    </svg>
                    Kirim Konfirmasi WhatsApp
                </a>
                <span class="text-xs text-slate-500 font-medium">Buka WhatsApp secara otomatis dalam beberapa detik...</span>
            </div>
            <script>
                // Buka WhatsApp di tab baru secara otomatis
                setTimeout(() => {
                    window.open("{{ session('wa_url') }}", '_blank');
                }, 1500);
            </script>
            @endif
        </div>
        @endif

        {{-- 3-Column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ============================================= --}}
            {{-- COLUMN 1: Alur & Proses Donasi (Left) --}}
            {{-- ============================================= --}}
            {{-- ============================================= --}}
            {{-- COLUMN 1: Alur & Proses Donasi (Left) --}}
            {{-- ============================================= --}}
            <div class="lg:col-span-3">
                <div class="sticky top-6 space-y-6">
                    {{-- Card: Alur Donasi --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h2 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </span>
                            Alur Donasi
                        </h2>

                        <div class="space-y-0">
                            {{-- Step 1 --}}
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shadow-sm">1</div>
                                    <div class="w-0.5 h-full bg-emerald-200 my-1"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-bold text-slate-800">Transfer Dana Donasi</p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Transfer via kode QRIS atau ke salah satu rekening resmi bank yang tertera di samping.</p>
                                </div>
                            </div>

                            {{-- Step 2 --}}
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shadow-sm">2</div>
                                    <div class="w-0.5 h-full bg-emerald-200 my-1"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-bold text-slate-800">Isi Data & Upload Bukti</p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Lengkapi form konfirmasi di sebelah kanan dan upload screenshot bukti transfer.</p>
                                </div>
                            </div>

                            {{-- Step 3 --}}
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center shadow-sm">3</div>
                                    <div class="w-0.5 h-full bg-amber-200 my-1"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-bold text-slate-800">Verifikasi Admin</p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Tim panti akan mencocokkan data Anda dengan mutasi rekening secara manual.</p>
                                </div>
                            </div>

                            {{-- Step 4 --}}
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white text-xs font-bold flex items-center justify-center shadow-sm">4</div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Donasi Tercatat</p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Setelah disetujui, nominal donasi Anda masuk ke total transparansi publik.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Cek Status Donasi --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h2 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            Cek Status Donasi
                        </h2>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed font-medium">
                            Masukkan kode spesial untuk melacak status verifikasi donasi Anda.
                        </p>

                        <form action="{{ route('donasi') }}" method="GET" class="space-y-3">
                            <div>
                                <input type="text" name="kode" value="{{ $searchQuery ?? '' }}" placeholder="Contoh: ABC1234567890" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-mono uppercase tracking-wider text-center">
                            </div>
                            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-sm hover:shadow-indigo-500/20 hover:-translate-y-0.5 transition-all duration-300">
                                Cari Status
                            </button>
                            @if($searchQuery)
                                <a href="{{ route('donasi') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors pt-1">
                                    Bersihkan Pencarian
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- COLUMN 2: Dashboard & QRIS (Center) --}}
            {{-- ============================================= --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- Hasil Pelacakan Donasi --}}
                @if($searchQuery)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 border-l-4 {{ $searchResult ? ($searchResult->status === 'approved' ? 'border-l-emerald-500' : ($searchResult->status === 'rejected' ? 'border-l-red-500' : 'border-l-amber-500')) : 'border-l-rose-500' }}">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Hasil Pelacakan Donasi</h3>
                                <p class="text-xs text-slate-500">Menampilkan status untuk kode: <span class="font-mono font-bold">{{ $searchQuery }}</span></p>
                            </div>
                            <a href="{{ route('donasi') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        </div>

                        @if($searchResult)
                            @php
                                $words = explode(' ', $searchResult->nama_lengkap);
                                $maskedWords = array_map(function($word) {
                                    $len = strlen($word);
                                    if ($len <= 2) return str_repeat('*', $len);
                                    return substr($word, 0, 1) . str_repeat('*', $len - 2) . substr($word, -1);
                                }, $words);
                                $maskedName = implode(' ', $maskedWords);
                            @endphp

                            <div class="space-y-3.5 text-sm">
                                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                    <span class="text-slate-500 text-xs font-semibold">Nama Donatur</span>
                                    <span class="text-slate-800 font-bold">{{ $maskedName }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                    <span class="text-slate-500 text-xs font-semibold">Nominal Donasi</span>
                                    <span class="text-emerald-700 font-extrabold">Rp {{ number_format($searchResult->jumlah_donasi, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                                    <span class="text-slate-500 text-xs font-semibold">Tanggal</span>
                                    <span class="text-slate-700 font-medium">{{ $searchResult->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-slate-500 text-xs font-semibold">Status Verifikasi</span>
                                    @if($searchResult->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif($searchResult->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                            Gagal / Ditolak
                                        </span>
                                    @endif
                                </div>

                                {{-- Status Description Message --}}
                                <div class="p-3.5 rounded-xl text-xs font-medium mt-2 leading-relaxed {{ $searchResult->status === 'approved' ? 'bg-emerald-50/70 text-emerald-800 border border-emerald-100/50' : ($searchResult->status === 'rejected' ? 'bg-red-50/70 text-red-800 border border-red-100/50' : 'bg-amber-50/70 text-amber-800 border border-amber-100/50') }}">
                                    @if($searchResult->status === 'pending')
                                        Donasi Anda telah masuk ke sistem kami dan sedang menunggu verifikasi manual oleh pihak panti. Pihak panti akan mencocokkan data rekening Anda dengan mutasi.
                                    @elseif($searchResult->status === 'approved')
                                        Terima kasih banyak! Donasi Anda telah diverifikasi oleh pihak panti dan nominalnya telah ditambahkan ke total transparansi publik. Semoga kebaikan Anda dibalas berlipat ganda!
                                    @else
                                        Maaf, donasi Anda gagal diverifikasi oleh pihak panti. Mohon pastikan bukti transfer yang diunggah valid dan terbaca jelas. Silakan hubungi admin panti untuk informasi lebih lanjut.
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-100 text-xs font-medium text-center">
                                Kode donasi <span class="font-mono font-bold">{{ $searchQuery }}</span> tidak ditemukan. Mohon periksa kembali kesesuaian huruf dan angka pada kode unik donasi Anda.
                            </div>
                        @endif
                    </div>
                @endif


                {{-- Card: Total Donasi Keseluruhan --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-slate-500">Total Donasi Terkumpul</p>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">Semua Waktu</span>
                    </div>
                    <p class="text-3xl font-extrabold text-emerald-600 tracking-tight mt-2">
                        Rp {{ number_format($totalDonasi, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-2">Akumulasi seluruh donasi yang telah diverifikasi.</p>
                </div>

                {{-- Card: Metode Pembayaran --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <h3 class="text-sm font-bold text-slate-800">Metode Pembayaran Donasi</h3>
                    </div>

                    <div class="p-6 space-y-6">

                        {{-- QRIS Section --}}
                        <div>
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em] mb-3">Via QRIS (Semua m-Banking & e-Wallet)</p>
                            <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-slate-800 to-slate-700 rounded-2xl shadow-lg">
                                <div class="bg-white p-2 rounded-xl shadow-inner">
                                    <img src="{{ asset('assets/qris.png') }}" alt="QRIS" class="w-24 h-24 object-contain rounded-lg">
                                </div>
                                <div class="text-white">
                                    <div class="text-xs font-black uppercase tracking-widest text-slate-300 mb-1">QRIS Universal</div>
                                    <div class="text-sm font-bold text-white leading-relaxed">Scan dari semua<br>aplikasi e-wallet &<br>mobile banking</div>
                                    <div class="mt-2 inline-flex items-center gap-1.5 bg-white/10 border border-white/20 px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                        <span class="text-[10px] font-bold text-emerald-300">Transfer Instan</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-slate-100"></div>
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">atau transfer manual</span>
                            <div class="flex-1 h-px bg-slate-100"></div>
                        </div>

                        {{-- Bank Cards --}}
                        <div class="space-y-3">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em]">Rekening Bank Resmi</p>

                            {{-- BCA Card --}}
                            <div x-data="{ copied: false }" class="relative overflow-hidden rounded-2xl shadow-md cursor-default group">
                                {{-- Gradient Background --}}
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-700 via-blue-600 to-blue-500"></div>
                                {{-- Decorative circles --}}
                                <div class="absolute -top-8 -right-8 w-36 h-36 bg-white/5 rounded-full"></div>
                                <div class="absolute -bottom-6 -left-4 w-28 h-28 bg-white/5 rounded-full"></div>

                                <div class="relative p-5">
                                    <div class="flex items-start justify-between mb-5">
                                        <div>
                                            <div class="text-[9px] font-black text-blue-200 uppercase tracking-[0.2em] mb-0.5">Bank Central Asia</div>
                                            <div class="text-2xl font-black text-white tracking-widest">BCA</div>
                                        </div>
                                        <button
                                            @click="navigator.clipboard.writeText('5195209895'); copied = true; setTimeout(() => copied = false, 2500)"
                                            class="flex items-center gap-1.5 text-[10px] font-black px-3 py-1.5 rounded-lg transition-all duration-300"
                                            :class="copied
                                                ? 'bg-emerald-400 text-white shadow-lg shadow-emerald-500/30'
                                                : 'bg-white/15 text-white border border-white/25 hover:bg-white/25'">
                                            <span x-show="!copied" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                Salin No. Rek
                                            </span>
                                            <span x-show="copied" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                Tersalin!
                                            </span>
                                        </button>
                                    </div>
                                    <div class="font-mono text-xl font-black text-white tracking-[0.2em] mb-3">519 520 9895</div>
                                    <div class="h-px bg-white/15 mb-3"></div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-[9px] text-blue-200 font-bold uppercase tracking-widest mb-0.5">Atas Nama</div>
                                            <div class="text-sm font-black text-white">Rina Uli Banjarnahor</div>
                                        </div>
                                        <svg class="w-8 h-8 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 4v8h16V8H4zm2 5h2v2H6v-2zm3 0h6v2H9v-2z"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Mandiri Card --}}
                            <div x-data="{ copied: false }" class="relative overflow-hidden rounded-2xl shadow-md cursor-default group">
                                {{-- Gradient Background --}}
                                <div class="absolute inset-0 bg-gradient-to-br from-amber-600 via-amber-500 to-yellow-400"></div>
                                {{-- Decorative circles --}}
                                <div class="absolute -top-8 -right-8 w-36 h-36 bg-white/10 rounded-full"></div>
                                <div class="absolute -bottom-6 -left-4 w-28 h-28 bg-white/10 rounded-full"></div>

                                <div class="relative p-5">
                                    <div class="flex items-start justify-between mb-5">
                                        <div>
                                            <div class="text-[9px] font-black text-amber-100 uppercase tracking-[0.2em] mb-0.5">Bank Mandiri</div>
                                            <div class="text-2xl font-black text-white tracking-widest">MANDIRI</div>
                                        </div>
                                        <button
                                            @click="navigator.clipboard.writeText('1560019947359'); copied = true; setTimeout(() => copied = false, 2500)"
                                            class="flex items-center gap-1.5 text-[10px] font-black px-3 py-1.5 rounded-lg transition-all duration-300"
                                            :class="copied
                                                ? 'bg-emerald-400 text-white shadow-lg shadow-emerald-500/30'
                                                : 'bg-white/20 text-white border border-white/30 hover:bg-white/30'">
                                            <span x-show="!copied" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                Salin No. Rek
                                            </span>
                                            <span x-show="copied" class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                Tersalin!
                                            </span>
                                        </button>
                                    </div>
                                    <div class="font-mono text-xl font-black text-white tracking-[0.2em] mb-3">156-00-1994735-9</div>
                                    <div class="h-px bg-white/20 mb-3"></div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="text-[9px] text-amber-100 font-bold uppercase tracking-widest mb-0.5">Atas Nama</div>
                                            <div class="text-sm font-black text-white">YAYASAN VITA DULCEDO</div>
                                        </div>
                                        <svg class="w-8 h-8 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm0 4v8h16V8H4zm2 5h2v2H6v-2zm3 0h6v2H9v-2z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Card: Riwayat Agregat Harian --}}

                <div id="riwayat" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="text-sm font-bold text-slate-800">Riwayat Donasi Harian</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Data agregat — privasi donatur terlindungi.</p>
                    </div>

                    @if($riwayatHarian->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 text-left">
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Donasi</th>
                                    <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Donatur</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($riwayatHarian as $row)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3 text-slate-700 font-medium">
                                        {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-emerald-700 font-bold text-right">
                                        Rp {{ number_format($row->total_harian, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 font-semibold text-center">
                                        {{ $row->jumlah_donatur }} orang
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($riwayatHarian->hasPages())
                    <div class="px-6 py-3 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-400">
                            Halaman {{ $riwayatHarian->currentPage() }} dari {{ $riwayatHarian->lastPage() }}
                        </p>
                        <div class="flex items-center gap-1">
                            @if($riwayatHarian->onFirstPage())
                                <span class="px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">‹ Prev</span>
                            @else
                                <a href="{{ $riwayatHarian->previousPageUrl() }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat"
                                   class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">‹ Prev</a>
                            @endif

                            @foreach($riwayatHarian->getUrlRange(1, $riwayatHarian->lastPage()) as $page => $url)
                                @if($page == $riwayatHarian->currentPage())
                                    <span class="px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-lg">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat"
                                       class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($riwayatHarian->hasMorePages())
                                <a href="{{ $riwayatHarian->nextPageUrl() }}{{ $searchQuery ? '&kode='.$searchQuery : '' }}#riwayat"
                                   class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 rounded-lg border border-slate-200 transition-colors">Next ›</a>
                            @else
                                <span class="px-3 py-1.5 text-xs font-bold text-slate-300 bg-slate-50 rounded-lg border border-slate-100 cursor-not-allowed">Next ›</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @else
                    <div class="px-6 py-10 text-center text-slate-400 text-sm">
                        Belum ada data donasi terverifikasi.
                    </div>
                    @endif
                </div>
            </div>

            {{-- ============================================= --}}
            {{-- COLUMN 3: Formulir Konfirmasi Donasi (Right) --}}
            {{-- ============================================= --}}
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 lg:sticky lg:top-6">
                    <h2 class="text-base font-bold text-slate-800 mb-1">Konfirmasi Donasi</h2>
                    <p class="text-xs text-slate-500 mb-6">Lengkapi form ini setelah Anda melakukan transfer melalui QRIS.</p>

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

                    <form id="form-donasi" action="{{ route('donasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        {{-- Pilih Program Donasi --}}
                        @if(isset($campaigns) && $campaigns->count() > 0)
                        <div>
                            <label for="campaign_id" class="block text-sm font-bold text-slate-700 mb-1.5">Pilih Program Bantuan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <select id="campaign_id" name="campaign_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                <option value="">Donasi Umum Panti Asuhan</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                        {{ $campaign->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Jumlah Donasi --}}
                        <div>
                            <label for="jumlah_donasi" class="block text-sm font-bold text-slate-700 mb-1.5">Jumlah Donasi (Rp)</label>
                            <input type="number" id="jumlah_donasi" name="jumlah_donasi" value="{{ old('jumlah_donasi') }}" required min="1000" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" placeholder="Contoh: 100000">
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" placeholder="Nama lengkap Anda">
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="nomor_telepon" class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', Auth::user()->login ?? '') }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" placeholder="Contoh: 081234567890">
                        </div>

                        {{-- Pesan --}}
                        <div>
                            <label for="pesan" class="block text-sm font-bold text-slate-700 mb-1.5">
                                Pesan untuk Panti
                                <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea id="pesan" name="pesan" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all resize-none" placeholder="Doa atau pesan untuk anak-anak panti...">{{ old('pesan') }}</textarea>
                        </div>

                        {{-- Upload Bukti --}}
                        <div>
                            <label for="bukti_transfer" class="block text-sm font-bold text-slate-700 mb-1.5">Upload Bukti Pembayaran</label>
                            <div class="relative">
                                <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*" required class="w-full text-sm text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 file:cursor-pointer file:transition-colors cursor-pointer border border-slate-200 rounded-xl bg-slate-50 py-2 px-3">
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5">Format: JPG, PNG, WebP. Maks 2MB.</p>
                        </div>

                        {{-- Submit --}}
                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 px-6 bg-[#009664] hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm hover:shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-emerald-500/30 text-sm">
                                Kirim Konfirmasi Donasi
                            </button>
                        </div>
                    </form>

                    {{-- Disclaimer --}}
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Donasi Anda akan diverifikasi secara manual oleh tim kami. Admin kami akan mencocokkan jumlah di form ini dengan bukti transfer Anda.
                        </p>
                    </div>
                </div>
            </div>

        </div>
        {{-- END DONATION TAB --}}
    </div>
</section>

<script>
    // Tab Switching Functionality
    function switchTab(tabName) {
        // Hide all content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('active-content');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active-tab');
            btn.classList.remove('border-b-2', 'border-emerald-600', 'text-slate-900');
            btn.classList.add('border-transparent', 'text-slate-600');
        });

        // Show selected content
        const contentEl = document.getElementById('content-' + tabName);
        if(contentEl) {
            contentEl.classList.remove('hidden');
            contentEl.classList.add('active-content');
        }

        // Add active class to clicked tab
        const tabBtn = document.getElementById('tab-' + tabName);
        if(tabBtn) {
            tabBtn.classList.add('active-tab', 'border-b-2', 'border-emerald-600', 'text-slate-900');
            tabBtn.classList.remove('border-transparent', 'text-slate-600');
        }
    }

    // Set up initial tab state
    document.addEventListener('DOMContentLoaded', function() {
        const tab1 = document.getElementById('tab-program');
        if(tab1) {
            tab1.classList.add('active-tab', 'border-b-2', 'border-emerald-600', 'text-slate-900');
            tab1.classList.remove('border-transparent', 'text-slate-600');
        }
    });

    function selectCampaign(id, title) {
        const select = document.getElementById('campaign_id');
        if(select) {
            select.value = id;
            select.classList.add('ring-4', 'ring-indigo-500/30', 'border-indigo-500');
            setTimeout(() => {
                select.classList.remove('ring-4', 'ring-indigo-500/30', 'border-indigo-500');
            }, 2000);
        }
        // Switch to donation tab
        switchTab('donasi');
        setTimeout(() => {
            document.getElementById('form-donasi').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 200);
    }
</script>
@endsection
