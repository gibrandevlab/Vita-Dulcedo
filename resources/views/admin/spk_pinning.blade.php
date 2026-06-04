@extends('layouts.admin')
@section('title', 'Kelola Pembobotan & Pinning Rekomendasi SPK')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-0 text-gray-800">Sistem Pendukung Keputusan (SPK)</h2>
        <p class="text-muted mb-0" style="font-size: 0.85rem;">Atur pembobotan kriteria TOPSIS & SAW, hitung ranking, dan simpan rekomendasi untuk halaman donasi.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Panduan Alur --}}
<div class="alert alert-info border-start border-info border-3" role="alert">
    <h5 class="alert-heading font-weight-bold"><i class="fa-solid fa-circle-info me-2"></i> Alur Penggunaan:</h5>
    <ol class="mb-0" style="font-size: 0.85rem;">
        <li><strong>Pilih Preset Filter</strong> — Pilih salah satu filter (Rekomendasi Panti, Paling Mendesak, dll).</li>
        <li><strong>Atur Bobot Kriteria (C1–C5)</strong> — Sesuaikan pembobotan setiap kriteria, total harus 100%.</li>
        <li><strong>Klik "Hitung Ranking"</strong> — Sistem akan menghitung peringkat 15 campaign terbaik berdasarkan TOPSIS & SAW.</li>
        <li><strong>Review Hasil</strong> — Periksa tabel ranking preview. Jika perlu, override manual di slot pinning.</li>
        <li><strong>Klik "Simpan Konfigurasi"</strong> — Bobot & slot disimpan ke database dan aktif di halaman donasi publik.</li>
    </ol>
</div>

<form action="{{ route('admin.spk.pinning.update') }}" method="POST" id="spkForm">
    @csrf

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 1: Pembobotan Kriteria per Preset              --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa-solid fa-sliders me-2"></i> Konfigurasi Pembobotan Kriteria (C1–C5)
            </h6>
        </div>
        <div class="card-body">
            {{-- Preset Tab Navigation --}}
            <ul class="nav nav-pills mb-4" id="presetTabs" role="tablist">
                @foreach($presets as $key => $preset)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                            id="tab-{{ $key }}" 
                            data-bs-toggle="pill" 
                            data-bs-target="#preset-{{ $key }}" 
                            type="button" 
                            role="tab"
                            data-preset="{{ $key }}">
                        @if($key === 'default')
                            <i class="fa-solid fa-star me-1"></i>
                        @elseif($key === 'urgent')
                            <i class="fa-solid fa-clock me-1"></i>
                        @elseif($key === 'almost_done')
                            <i class="fa-solid fa-bullseye me-1"></i>
                        @else
                            <i class="fa-solid fa-heart me-1"></i>
                        @endif
                        {{ $preset['label'] }}
                    </button>
                </li>
                @endforeach
            </ul>

            {{-- Preset Tab Content --}}
            <div class="tab-content" id="presetTabContent">
                @foreach($presets as $key => $preset)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                     id="preset-{{ $key }}" 
                     role="tabpanel">
                    
                    <p class="text-muted mb-3" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-info-circle me-1"></i> {{ $preset['description'] }}
                    </p>

                    <div class="row g-3">
                        @foreach($criteriaInfo as $idx => $crit)
                        @php $critKey = strtolower($crit['code']); @endphp
                        <div class="col-md-4 col-lg">
                            <div class="p-3 border rounded-3 bg-white shadow-sm">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge {{ $crit['type'] === 'benefit' ? 'bg-success' : 'bg-warning text-dark' }} mb-1" style="font-size: 0.65rem;">
                                            {{ strtoupper($crit['type']) }}
                                        </span>
                                        <div class="fw-bold text-dark" style="font-size: 0.8rem;">{{ $crit['code'] }} — {{ $crit['name'] }}</div>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm">
                                    <input type="number" 
                                           name="weights[{{ $key }}][{{ $critKey }}]" 
                                           class="form-control weight-input text-center fw-bold" 
                                           value="{{ $weights[$key][$critKey] }}"
                                           step="0.01" 
                                           min="0" 
                                           max="1"
                                           data-preset="{{ $key }}"
                                           style="font-size: 0.85rem;">
                                    <span class="input-group-text" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-percent"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total Weight Indicator --}}
                    <div class="mt-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted" style="font-size: 0.8rem;">Total Bobot:</span>
                            <span class="fw-bold ms-1 total-weight-display" id="total-{{ $key }}" style="font-size: 0.9rem;">
                                {{ number_format(array_sum(array_values($weights[$key])), 2) }}
                            </span>
                            <span class="ms-1 total-weight-badge" id="badge-{{ $key }}">
                                @if(abs(array_sum(array_values($weights[$key])) - 1.0) <= 0.01)
                                    <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fa-solid fa-check"></i> Valid</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 0.65rem;"><i class="fa-solid fa-xmark"></i> Harus 1.00</span>
                                @endif
                            </span>
                        </div>
                        <button type="button" 
                                class="btn btn-primary btn-sm px-4 btn-hitung" 
                                data-preset="{{ $key }}"
                                onclick="hitungRanking('{{ $key }}')">
                            <i class="fa-solid fa-calculator me-1"></i> Hitung Ranking
                        </button>
                    </div>

                    {{-- 15 Slot Pinning for this Preset --}}
                    <div class="card shadow-sm border mt-4 mb-3">
                        <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-list-ol me-1"></i> Pemetaan 15 Slot Rekomendasi ({{ $preset['label'] }})
                            </span>
                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" style="font-size: 0.7rem;" onclick="clearPresetSlots('{{ $key }}')">
                                <i class="fa-solid fa-eraser me-1"></i> Kosongkan
                            </button>
                        </div>
                        <div class="card-body py-3 px-3">
                            <div class="row g-2">
                                @for ($slot = 1; $slot <= 15; $slot++)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="p-2 border rounded bg-light" id="slot-container-{{ $key }}-{{ $slot }}">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge bg-primary text-white fw-bold px-2 py-0.5" style="font-size: 0.7rem;">
                                                Slot #{{ $slot }}
                                            </span>
                                            @if(isset($pinnedCampaigns[$key][$slot]))
                                                <span class="text-success fw-bold slot-status" style="font-size: 0.65rem;">
                                                    <i class="fa-solid fa-lock me-1"></i> Pinned
                                                </span>
                                            @else
                                                <span class="text-muted slot-status" style="font-size: 0.65rem;">
                                                    <i class="fa-solid fa-gears me-1"></i> Otomatis SPK
                                                </span>
                                            @endif
                                        </div>
                                        <select name="slots[{{ $key }}][{{ $slot }}]" class="form-select form-select-xs slot-select" id="slot-{{ $key }}-{{ $slot }}" data-preset="{{ $key }}" data-slot="{{ $slot }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                            <option value="">-- [Otomatis] Peringkat Terbaik --</option>
                                            @foreach($campaigns as $campaign)
                                                <option value="{{ $campaign->id }}" 
                                                    {{ (isset($pinnedCampaigns[$key][$slot]) && $pinnedCampaigns[$key][$slot]->id === $campaign->id) ? 'selected' : '' }}>
                                                    {{ $campaign->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('donasi') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-eye me-1"></i> Lihat Halaman Donasi
            </a>
            <button type="submit" class="btn btn-success px-5">
                <i class="fa-solid fa-save me-2"></i> Simpan Konfigurasi
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- SECTION 2: Hasil Ranking Preview                       --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="card shadow mb-4" id="rankingPreviewCard" style="display: none;">
        <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fa-solid fa-ranking-star me-2"></i> Hasil Ranking TOPSIS & SAW
                <span class="badge bg-secondary ms-2" id="previewPresetLabel" style="font-size: 0.7rem;"></span>
            </h6>
            <div>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="applyRankingToSlots()">
                    <i class="fa-solid fa-arrow-down me-1"></i> Terapkan ke Slot
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" style="font-size: 0.8rem;">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">Rank</th>
                            <th>Nama Campaign</th>
                            <th class="text-center">Skor TOPSIS</th>
                            <th class="text-center">Skor SAW</th>
                            <th class="text-center">Skor Final</th>
                            <th class="text-center">Progress</th>
                        </tr>
                    </thead>
                    <tbody id="rankingTableBody">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fa-solid fa-calculator me-2"></i> Klik "Hitung Ranking" untuk melihat hasil.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Loading Spinner --}}
    <div id="loadingSpinner" style="display: none;" class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Menghitung...</span>
        </div>
        <p class="text-muted mt-2" style="font-size: 0.85rem;">Sedang menghitung ranking TOPSIS & SAW...</p>
    </div>

</form>

{{-- ═══════════════════════════════════════════════════════ --}}
{{-- JavaScript                                             --}}
{{-- ═══════════════════════════════════════════════════════ --}}
<script>
    // Store the last calculated ranking for "Terapkan ke Slot"
    let lastRankingResult = [];
    let activePreviewPreset = null;

    /**
     * Recalculate and display weight totals in real-time.
     */
    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', function() {
            const preset = this.dataset.preset;
            updateWeightTotal(preset);
        });
    });

    function updateWeightTotal(preset) {
        const inputs = document.querySelectorAll(`.weight-input[data-preset="${preset}"]`);
        let total = 0;
        inputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const display = document.getElementById(`total-${preset}`);
        const badge = document.getElementById(`badge-${preset}`);

        display.textContent = total.toFixed(2);

        if (Math.abs(total - 1.0) <= 0.01) {
            badge.innerHTML = '<span class="badge bg-success" style="font-size: 0.65rem;"><i class="fa-solid fa-check"></i> Valid</span>';
        } else {
            badge.innerHTML = '<span class="badge bg-danger" style="font-size: 0.65rem;"><i class="fa-solid fa-xmark"></i> Harus 1.00</span>';
        }
    }

    /**
     * AJAX call to calculate TOPSIS-SAW ranking preview.
     */
    function hitungRanking(preset) {
        const inputs = document.querySelectorAll(`.weight-input[data-preset="${preset}"]`);
        const weights = {};
        inputs.forEach(input => {
            const name = input.name; // e.g. weights[default][c1]
            const matches = name.match(/\[(\w+)\]$/);
            if (matches) {
                weights[matches[1]] = parseFloat(input.value) || 0;
            }
        });

        // Validate total
        const total = Object.values(weights).reduce((a, b) => a + b, 0);
        if (Math.abs(total - 1.0) > 0.01) {
            alert(`Total bobot harus 1.00 (100%). Saat ini: ${total.toFixed(2)}`);
            return;
        }

        // Show loading
        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('rankingPreviewCard').style.display = 'none';

        // AJAX request
        fetch('{{ route("admin.spk.pinning.hitung") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                preset: preset,
                weights: weights,
            }),
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw data; });
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('loadingSpinner').style.display = 'none';
            renderRankingPreview(data, preset);
            autoApplyRankingToSlots(data.ranking, preset);
        })
        .catch(error => {
            document.getElementById('loadingSpinner').style.display = 'none';
            const message = error.error || error.message || 'Terjadi kesalahan saat menghitung.';
            alert(message);
        });
    }

    /**
     * Render the ranking preview table.
     */
    function renderRankingPreview(data, preset) {
        lastRankingResult = data.ranking;
        activePreviewPreset = preset;

        const presetLabels = {
            'default': 'Rekomendasi Panti',
            'urgent': 'Paling Mendesak',
            'almost_done': 'Sedikit Lagi Terkumpul',
            'popular': 'Paling Banyak Didukung',
        };

        document.getElementById('previewPresetLabel').textContent = presetLabels[preset] || preset;
        document.getElementById('rankingPreviewCard').style.display = 'block';

        const tbody = document.getElementById('rankingTableBody');

        if (!data.ranking || data.ranking.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fa-solid fa-inbox me-2"></i> Tidak ada campaign aktif untuk dihitung.
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        data.ranking.forEach(item => {
            const rankBadge = item.rank <= 3 
                ? `<span class="badge bg-warning text-dark fw-bold">${item.rank}</span>`
                : `<span class="badge bg-secondary">${item.rank}</span>`;

            const progressColor = item.percentage >= 75 ? 'bg-success' : (item.percentage >= 40 ? 'bg-info' : 'bg-primary');

            html += `
                <tr>
                    <td class="text-center align-middle">${rankBadge}</td>
                    <td class="align-middle">
                        <div class="fw-bold" style="font-size: 0.85rem;">${item.title}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">ID: ${item.id}</div>
                    </td>
                    <td class="text-center align-middle fw-bold text-primary">${item.topsis_score.toFixed(4)}</td>
                    <td class="text-center align-middle fw-bold text-info">${item.saw_score.toFixed(4)}</td>
                    <td class="text-center align-middle">
                        <span class="fw-bold text-success" style="font-size: 0.95rem;">${item.final_score.toFixed(4)}</span>
                    </td>
                    <td class="text-center align-middle" style="min-width: 120px;">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar ${progressColor}" style="width: ${item.percentage}%"></div>
                        </div>
                        <small class="text-muted">${item.percentage}%</small>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        // Scroll to the ranking preview card
        document.getElementById('rankingPreviewCard').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /**
     * Apply the last calculated ranking to the 15 pin slots.
     */
    function applyRankingToSlots() {
        if (!lastRankingResult || lastRankingResult.length === 0 || !activePreviewPreset) {
            alert('Belum ada ranking yang dihitung. Klik "Hitung Ranking" terlebih dahulu.');
            return;
        }

        if (!confirm(`Terapkan 15 teratas ke slot pinning untuk preset "${activePreviewPreset}"? Slot yang sudah diisi secara manual akan ditimpa.`)) {
            return;
        }

        autoApplyRankingToSlots(lastRankingResult, activePreviewPreset);
    }

    /**
     * Update slot visual indicator (pinned/auto).
     */
    function updateSlotVisual(preset, slot) {
        const select = document.getElementById(`slot-${preset}-${slot}`);
        if (!select) return;
        const container = document.getElementById(`slot-container-${preset}-${slot}`);
        if (!container) return;
        const statusSpan = container.querySelector('.slot-status');
        if (!statusSpan) return;

        if (select.value) {
            statusSpan.innerHTML = '<i class="fa-solid fa-lock me-1"></i> Pinned';
            statusSpan.className = 'text-success fw-bold slot-status';
            statusSpan.style.fontSize = '0.65rem';
        } else {
            statusSpan.innerHTML = '<i class="fa-solid fa-gears me-1"></i> Otomatis SPK';
            statusSpan.className = 'text-muted slot-status';
            statusSpan.style.fontSize = '0.65rem';
        }
    }

    // Add change listener to all slot selects
    document.querySelectorAll('.slot-select').forEach(select => {
        select.addEventListener('change', function() {
            const preset = this.dataset.preset;
            const slot = this.dataset.slot;
            updateSlotVisual(preset, slot);
        });
    });

    /**
     * Clear all pinning slots for a preset.
     */
    function clearPresetSlots(preset) {
        if (!confirm('Kosongkan semua slot untuk preset ini? Semua posisi akan disetel ke "Otomatis SPK".')) return;

        for (let slot = 1; slot <= 15; slot++) {
            const select = document.getElementById(`slot-${preset}-${slot}`);
            if (select) {
                select.value = '';
                updateSlotVisual(preset, slot);
            }
        }
    }

    /**
     * Automatically apply calculated ranking to the slots.
     */
    function autoApplyRankingToSlots(ranking, preset) {
        if (!ranking || ranking.length === 0) return;

        for (let slot = 1; slot <= 15; slot++) {
            const select = document.getElementById(`slot-${preset}-${slot}`);
            if (!select) continue;

            if (slot <= ranking.length) {
                const campaignId = ranking[slot - 1].id;
                const option = select.querySelector(`option[value="${campaignId}"]`);
                if (option) {
                    select.value = campaignId;
                } else {
                    select.value = '';
                }
            } else {
                select.value = '';
            }
            updateSlotVisual(preset, slot);
        }
    }
</script>
@endsection
