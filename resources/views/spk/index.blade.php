@extends('layouts.app')

@section('content')
<section class="bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rekomendasi Campaign</h1>
                    <p class="text-slate-500 mt-0.5">Sistem Pendukung Keputusan — Metode TOPSIS & SAW</p>
                </div>
            </div>
        </div>

        {{-- Preset Filter Cards --}}
        <div class="mb-8">
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-[0.15em] mb-3">Tampilkan Rekomendasi Berdasarkan</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($presets as $key => $preset)
                <a href="{{ route('spk.index', ['preset' => $key]) }}"
                   class="group relative rounded-2xl border-2 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg
                          {{ $activePreset === $key
                              ? 'border-indigo-500 bg-indigo-50 shadow-md shadow-indigo-500/10'
                              : 'border-slate-100 bg-white hover:border-indigo-200 hover:bg-indigo-50/30' }}">

                    {{-- Active Indicator --}}
                    @if($activePreset === $key)
                    <div class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    @endif

                    {{-- Icon --}}
                    <div class="w-9 h-9 rounded-xl mb-3 flex items-center justify-center
                                {{ $activePreset === $key ? 'bg-indigo-500 text-white shadow-sm' : 'bg-slate-100 text-slate-500 group-hover:bg-indigo-100 group-hover:text-indigo-600' }} transition-all">
                        @if($preset['icon'] === 'star')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @elseif($preset['icon'] === 'clock')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($preset['icon'] === 'target')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif($preset['icon'] === 'heart')
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        @endif
                    </div>

                    <h3 class="text-sm font-bold {{ $activePreset === $key ? 'text-indigo-700' : 'text-slate-800' }} mb-0.5">{{ $preset['label'] }}</h3>
                    <p class="text-[11px] leading-relaxed {{ $activePreset === $key ? 'text-indigo-500' : 'text-slate-400' }}">{{ $preset['description'] }}</p>
                </a>
                @endforeach
            </div>
        </div>

        @if($result && count($result['ranking']) > 0)

        {{-- Stats Bar --}}
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <span class="px-3.5 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-100">
                {{ $campaignCount }} Campaign Aktif Dianalisis
            </span>
            <span class="px-3.5 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full border border-slate-200">
                Preset: {{ $presets[$activePreset]['label'] }}
            </span>
            <span class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-100">
                Bobot: {{ implode(' / ', array_map(fn($w) => ($w*100).'%', $result['weights'])) }}
            </span>
        </div>

        {{-- Ranking Cards --}}
        <div class="space-y-4 mb-10">
            @foreach($result['ranking'] as $idx => $item)
            @php
                $campaign = $item['campaign'];
                $percentage = $campaign->target_amount > 0 ? min(100, round(($campaign->collected_amount / $campaign->target_amount) * 100)) : 0;
                $daysLeft = $campaign->deadline ? max(0, now()->diffInDays($campaign->deadline, false)) : null;
                $donaturCount = $item['scores']['C3'];
                $rank = $item['final_rank'];
            @endphp
            <div class="group relative bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden" x-data="{ showDetail: false }">

                {{-- Rank Badge (floating) --}}
                <div class="absolute top-4 left-4 z-10">
                    @if($rank === 1)
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-amber-400/30 ring-2 ring-amber-300">
                            {{ $rank }}
                        </div>
                    @elseif($rank === 2)
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-300 to-slate-400 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-slate-300/30 ring-2 ring-slate-200">
                            {{ $rank }}
                        </div>
                    @elseif($rank === 3)
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-600 to-amber-700 text-white font-black text-lg flex items-center justify-center shadow-lg shadow-amber-600/30 ring-2 ring-amber-500">
                            {{ $rank }}
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 font-black text-lg flex items-center justify-center border border-slate-200">
                            {{ $rank }}
                        </div>
                    @endif
                </div>

                <div class="flex flex-col md:flex-row">
                    {{-- Campaign Image --}}
                    <div class="w-full md:w-56 h-48 md:h-auto flex-shrink-0 relative overflow-hidden">
                        @if($campaign->image)
                            <img src="{{ Storage::url($campaign->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 p-6">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="pl-12 md:pl-0">
                                <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $campaign->title }}</h3>
                                <p class="text-sm text-slate-500 line-clamp-2 mt-1">{{ $campaign->description }}</p>
                            </div>
                        </div>

                        {{-- Metrics Row --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Ketercapaian</p>
                                <p class="text-lg font-extrabold text-emerald-600">{{ $percentage }}%</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sisa Waktu</p>
                                <p class="text-lg font-extrabold {{ $daysLeft !== null && $daysLeft <= 7 ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ $daysLeft !== null ? $daysLeft.' hari' : '∞' }}
                                </p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Donatur</p>
                                <p class="text-lg font-extrabold text-indigo-600">{{ $donaturCount }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sisa Kebutuhan</p>
                                <p class="text-sm font-extrabold text-slate-700">Rp {{ number_format(max(0, $campaign->target_amount - $campaign->collected_amount), 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-end mb-1.5">
                                <span class="text-xs font-bold text-emerald-600">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                <span class="text-xs font-medium text-slate-400">Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full relative transition-all duration-700" style="width: {{ $percentage }}%">
                                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Score Badges + Actions --}}
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    TOPSIS: {{ number_format($item['topsis_score'], 4) }}
                                    <span class="ml-1 text-indigo-400">(#{{ $item['topsis_rank'] }})</span>
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    SAW: {{ number_format($item['saw_score'], 4) }}
                                    <span class="ml-1 text-purple-400">(#{{ $item['saw_rank'] }})</span>
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Skor Akhir: {{ number_format($item['final_score'], 4) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="showDetail = !showDetail"
                                        class="px-3 py-2 text-xs font-bold text-slate-500 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 rounded-xl border border-slate-200 hover:border-indigo-200 transition-all">
                                    <span x-show="!showDetail" class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        Detail Perhitungan
                                    </span>
                                    <span x-show="showDetail" class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        Sembunyikan
                                    </span>
                                </button>
                                <a href="{{ route('donasi') }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-indigo-600 rounded-xl transition-colors shadow-sm flex items-center gap-1.5">
                                    Donasi Sekarang
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Expandable Detail Section --}}
                <div x-show="showDetail" x-collapse class="border-t border-slate-100 bg-slate-50/50 px-6 py-5">
                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        Detail Nilai Kriteria — {{ $campaign->title }}
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-white">
                                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kriteria</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-2.5 text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                                    <th class="px-4 py-2.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nilai Mentah</th>
                                    <th class="px-4 py-2.5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bobot</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $criteriaNames = ['C1' => 'Persentase Ketercapaian', 'C2' => 'Urgensi Waktu', 'C3' => 'Jumlah Donatur', 'C4' => 'Sisa Kebutuhan', 'C5' => 'Skala Campaign'];
                                    $criteriaTypes = ['C1' => 'BENEFIT', 'C2' => 'COST', 'C3' => 'BENEFIT', 'C4' => 'COST', 'C5' => 'BENEFIT'];
                                    $criteriaUnits = ['C1' => '%', 'C2' => ' hari', 'C3' => ' donatur', 'C4' => '', 'C5' => ''];
                                    $weightIdx = 0;
                                @endphp
                                @foreach($item['scores'] as $code => $value)
                                <tr class="hover:bg-white/80 transition-colors">
                                    <td class="px-4 py-2 font-mono font-bold text-indigo-600 text-xs">{{ $code }}</td>
                                    <td class="px-4 py-2 text-slate-700 font-medium text-xs">{{ $criteriaNames[$code] ?? $code }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ ($criteriaTypes[$code] ?? '') === 'BENEFIT' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                            {{ $criteriaTypes[$code] ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-right font-mono font-bold text-slate-800 text-xs">
                                        @if(in_array($code, ['C4', 'C5']))
                                            Rp {{ number_format($value, 0, ',', '.') }}
                                        @else
                                            {{ number_format($value, $code === 'C1' ? 1 : 0) }}{{ $criteriaUnits[$code] ?? '' }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right font-bold text-slate-500 text-xs">{{ ($result['weights'][$weightIdx] ?? 0) * 100 }}%</td>
                                </tr>
                                @php $weightIdx++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Full Decision Matrix Table --}}
        <div x-data="{ showMatrix: false }" class="mb-10">
            <button @click="showMatrix = !showMatrix"
                    class="flex items-center gap-2 px-5 py-3 bg-white rounded-2xl border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/30 text-sm font-bold text-slate-600 hover:text-indigo-600 transition-all shadow-sm w-full justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                <span x-show="!showMatrix">Tampilkan Matriks Keputusan Lengkap</span>
                <span x-show="showMatrix">Sembunyikan Matriks Keputusan</span>
                <svg class="w-4 h-4 transition-transform" :class="showMatrix ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="showMatrix" x-collapse class="mt-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Matriks Keputusan & Skor Akhir
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Perbandingan semua alternatif campaign terhadap 5 kriteria</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50">
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider min-w-[180px]">Campaign</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-emerald-500 uppercase tracking-wider">C1 (%)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-rose-500 uppercase tracking-wider">C2 (hari)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-emerald-500 uppercase tracking-wider">C3 (org)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-rose-500 uppercase tracking-wider">C4 (Rp)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-emerald-500 uppercase tracking-wider">C5 (Rp)</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-indigo-500 uppercase tracking-wider">TOPSIS</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-purple-500 uppercase tracking-wider">SAW</th>
                                    <th class="px-4 py-3 text-center text-[10px] font-bold text-slate-700 uppercase tracking-wider bg-slate-100">Final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($result['ranking'] as $item)
                                <tr class="hover:bg-indigo-50/30 transition-colors {{ $item['final_rank'] <= 3 ? 'bg-amber-50/20' : '' }}">
                                    <td class="px-4 py-2.5 font-black text-xs {{ $item['final_rank'] <= 3 ? 'text-amber-600' : 'text-slate-400' }}">{{ $item['final_rank'] }}</td>
                                    <td class="px-4 py-2.5 text-xs font-bold text-slate-800 truncate max-w-[200px]">{{ $item['campaign']->title }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-slate-700">{{ number_format($item['scores']['C1'], 1) }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-slate-700">{{ number_format($item['scores']['C2'], 0) }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-slate-700">{{ $item['scores']['C3'] }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-slate-700">{{ number_format($item['scores']['C4'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-slate-700">{{ number_format($item['scores']['C5'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-indigo-600">{{ number_format($item['topsis_score'], 4) }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-bold text-purple-600">{{ number_format($item['saw_score'], 4) }}</td>
                                    <td class="px-4 py-2.5 text-center font-mono text-xs font-extrabold text-slate-900 bg-slate-50">{{ number_format($item['final_score'], 4) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bobot Legend --}}
                    <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-wrap gap-3 items-center">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bobot:</span>
                        @php
                            $criteriaLabels = ['C1' => 'Ketercapaian', 'C2' => 'Urgensi', 'C3' => 'Donatur', 'C4' => 'Sisa Dana', 'C5' => 'Skala'];
                            $wi = 0;
                        @endphp
                        @foreach($criteriaLabels as $code => $label)
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ in_array($code, ['C1', 'C3', 'C5']) ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $code }} {{ $label }}: {{ ($result['weights'][$wi] ?? 0) * 100 }}%
                            </span>
                            @php $wi++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Methodology Info --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tentang Metodologi SPK
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100/50">
                    <h4 class="text-xs font-bold text-indigo-700 mb-2">TOPSIS <span class="font-normal text-indigo-400">(Technique for Order Preference by Similarity to Ideal Solution)</span></h4>
                    <p class="text-xs text-indigo-600/80 leading-relaxed">Memilih alternatif yang memiliki jarak terdekat dengan solusi ideal positif dan terjauh dari solusi ideal negatif. Skor Ci mendekati 1.0 = semakin ideal.</p>
                </div>
                <div class="bg-purple-50/50 rounded-xl p-4 border border-purple-100/50">
                    <h4 class="text-xs font-bold text-purple-700 mb-2">SAW <span class="font-normal text-purple-400">(Simple Additive Weighting)</span></h4>
                    <p class="text-xs text-purple-600/80 leading-relaxed">Menjumlahkan nilai normalisasi terbobot dari setiap kriteria. Skor Vi tertinggi = alternatif terbaik. Normalisasi disesuaikan tipe BENEFIT/COST.</p>
                </div>
            </div>
        </div>

        @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Campaign Aktif</h3>
            <p class="text-sm text-slate-500 mb-6">Saat ini tidak ada campaign aktif untuk dianalisis. Silakan cek kembali nanti.</p>
            <a href="{{ route('donasi') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-indigo-600 text-white text-sm font-bold rounded-xl transition-colors">
                Lihat Halaman Donasi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
