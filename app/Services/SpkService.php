<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * SpkService — Sistem Pendukung Keputusan (SPK)
 *
 * Mengimplementasikan metode TOPSIS dan SAW untuk meranking
 * campaign donasi di Panti Asuhan Vita Dulcedo.
 *
 * Pendekatan hybrid: skor akhir = rata-rata skor TOPSIS & SAW.
 */
class SpkService
{
    // ────────────────────────────────────────────────────────────
    //  Konstanta tipe kriteria
    // ────────────────────────────────────────────────────────────

    private const BENEFIT = 'benefit';
    private const COST    = 'cost';

    /**
     * Definisi 5 kriteria penilaian campaign.
     */
    private const CRITERIA = [
        'C1' => ['name' => 'Persentase Ketercapaian',   'type' => self::BENEFIT],
        'C2' => ['name' => 'Urgensi Waktu',             'type' => self::COST],
        'C3' => ['name' => 'Jumlah Donatur',            'type' => self::BENEFIT],
        'C4' => ['name' => 'Sisa Kebutuhan Dana',       'type' => self::COST],
        'C5' => ['name' => 'Skala Campaign',            'type' => self::BENEFIT],
    ];

    /**
     * Preset bobot untuk pendekatan hybrid.
     * Urutan bobot: [C1, C2, C3, C4, C5]
     */
    private const WEIGHT_PRESETS = [
        'default'     => [0.30, 0.25, 0.20, 0.15, 0.10],
        'urgent'      => [0.10, 0.50, 0.10, 0.20, 0.10],
        'almost_done' => [0.50, 0.15, 0.15, 0.10, 0.10],
        'popular'     => [0.15, 0.10, 0.50, 0.15, 0.10],
    ];

    // ────────────────────────────────────────────────────────────
    //  Public API
    // ────────────────────────────────────────────────────────────

    /**
     * Hitung ranking campaign menggunakan metode TOPSIS + SAW.
     *
     * @param  Collection  $campaigns  Collection Campaign (eager-load: donations)
     * @param  string      $preset     Nama preset bobot
     * @return array{
     *     ranking: array,
     *     criteria: array,
     *     weights: array,
     *     preset: string,
     *     decision_matrix: array
     * }
     */
    /**
     * Hitung ranking dengan bobot kustom tanpa perlu membaca dari database.
     *
     * @param  Collection  $campaigns      Campaign yang sudah di-filter
     * @param  array       $customWeights  Bobot [C1, C2, C3, C4, C5]
     * @return array
     */
    public function calculateWithCustomWeights(Collection $campaigns, array $customWeights): array
    {
        return $this->performCalculation($campaigns, $customWeights, 'custom');
    }

    public function calculate(Collection $campaigns, string $preset = 'default'): array
    {
        $weights = $this->getPresetWeights($preset);

        return $this->performCalculation($campaigns, $weights, $preset);
    }

    /**
     * Core calculation engine shared by calculate() and calculateWithCustomWeights().
     */
    private function performCalculation(Collection $campaigns, array $weights, string $preset): array
    {
        // Filter campaign yang target_amount > 0
        $filtered = $campaigns->filter(fn ($c) => $c->target_amount > 0)->values();

        // Bangun matriks keputusan
        $matrix        = $this->buildDecisionMatrix($filtered);
        $criteriaTypes = array_column(self::CRITERIA, 'type');

        // Edge case: tidak ada campaign valid
        if ($filtered->isEmpty()) {
            return [
                'ranking'         => [],
                'criteria'        => self::getCriteriaInfo(),
                'weights'         => $weights,
                'preset'          => $preset,
                'decision_matrix' => [],
            ];
        }

        // Edge case: hanya 1 campaign — langsung skor sempurna
        if ($filtered->count() === 1) {
            $single = $filtered->first();
            $scores = $matrix[0];

            $ranking = [[
                'campaign'     => $single,
                'scores'       => $this->labelScores($scores),
                'topsis_score' => 1.0,
                'saw_score'    => 1.0,
                'final_score'  => 1.0,
                'topsis_rank'  => 1,
                'saw_rank'     => 1,
                'final_rank'   => 1,
            ]];

            return [
                'ranking'         => $ranking,
                'criteria'        => self::getCriteriaInfo(),
                'weights'         => $weights,
                'preset'          => $preset,
                'decision_matrix' => [$this->labelScores($scores)],
            ];
        }

        // ── TOPSIS ──────────────────────────────────────────────
        $topsisScores = $this->topsis($matrix, $weights, $criteriaTypes);

        // ── SAW ─────────────────────────────────────────────────
        $sawScores = $this->saw($matrix, $weights, $criteriaTypes);

        // ── Gabungkan skor akhir ─────────────────────────────────
        $results = [];
        foreach ($filtered as $idx => $campaign) {
            $results[] = [
                'campaign'     => $campaign,
                'scores'       => $this->labelScores($matrix[$idx]),
                'topsis_score' => round($topsisScores[$idx], 6),
                'saw_score'    => round($sawScores[$idx], 6),
                'final_score'  => round(($topsisScores[$idx] + $sawScores[$idx]) / 2, 6),
            ];
        }

        // Hitung ranking per metode
        $results = $this->assignRanks($results, 'topsis_score', 'topsis_rank');
        $results = $this->assignRanks($results, 'saw_score', 'saw_rank');
        $results = $this->assignRanks($results, 'final_score', 'final_rank');

        // Urutkan berdasarkan skor akhir (terbaik di atas)
        usort($results, fn ($a, $b) => $b['final_score'] <=> $a['final_score']);

        // Decision matrix berlabel
        $decisionMatrix = array_map(fn ($row) => $this->labelScores($row), $matrix);

        return [
            'ranking'         => $results,
            'criteria'        => self::getCriteriaInfo(),
            'weights'         => $weights,
            'preset'          => $preset,
            'decision_matrix' => $decisionMatrix,
        ];
    }

    /**
     * Daftar preset bobot yang tersedia.
     */
    public static function getPresets(): array
    {
        return [
            'default' => [
                'label'       => 'Rekomendasi Panti',
                'icon'        => 'star',
                'description' => 'Bobot seimbang yang direkomendasikan oleh pihak panti',
            ],
            'urgent' => [
                'label'       => 'Paling Mendesak',
                'icon'        => 'clock',
                'description' => 'Prioritaskan campaign yang deadline-nya semakin dekat',
            ],
            'almost_done' => [
                'label'       => 'Sedikit Lagi Terkumpul',
                'icon'        => 'target',
                'description' => 'Prioritaskan campaign yang hampir mencapai target',
            ],
            'popular' => [
                'label'       => 'Paling Banyak Didukung',
                'icon'        => 'heart',
                'description' => 'Prioritaskan campaign dengan donatur terbanyak',
            ],
        ];
    }

    /**
     * Informasi detail setiap kriteria (C1–C5).
     */
    public static function getCriteriaInfo(): array
    {
        $info = [];

        foreach (self::CRITERIA as $code => $meta) {
            $info[] = [
                'code' => $code,
                'name' => $meta['name'],
                'type' => $meta['type'],
            ];
        }

        return $info;
    }

    // ────────────────────────────────────────────────────────────
    //  Decision Matrix
    // ────────────────────────────────────────────────────────────

    /**
     * Bangun matriks keputusan X[i][j] dari collection campaign.
     *
     * @param  Collection  $campaigns  Campaign yang sudah difilter (target > 0)
     * @return array<int, array<int, float>>
     */
    private function buildDecisionMatrix(Collection $campaigns): array
    {
        $matrix = [];

        foreach ($campaigns as $campaign) {
            $collected = (float) $campaign->collected_amount;
            $target    = (float) $campaign->target_amount;

            // C1: Persentase ketercapaian (clamp di 100%)
            $c1 = min(($collected / $target) * 100, 100);

            // C2: Urgensi waktu — sisa hari sampai deadline
            $c2 = $this->daysRemaining($campaign->deadline);

            // C3: Jumlah donatur (status approved)
            $c3 = $campaign->donations
                ->where('status', 'approved')
                ->count();

            // C4: Sisa kebutuhan dana
            $c4 = max($target - $collected, 0);

            // C5: Skala campaign (target_amount)
            $c5 = $target;

            $matrix[] = [$c1, $c2, (float) $c3, $c4, $c5];
        }

        return $matrix;
    }

    /**
     * Hitung sisa hari menuju deadline. Null = 999 (tanpa batas waktu).
     */
    private function daysRemaining(?string $deadline): float
    {
        if ($deadline === null) {
            return 999.0;
        }

        $days = Carbon::now()->startOfDay()->diffInDays(
            Carbon::parse($deadline)->startOfDay(),
            false // bisa negatif kalau sudah lewat
        );

        return max((float) $days, 0);
    }

    // ────────────────────────────────────────────────────────────
    //  TOPSIS
    // ────────────────────────────────────────────────────────────

    /**
     * Metode TOPSIS — Technique for Order Preference by Similarity
     * to Ideal Solution.
     *
     * @param  array  $matrix        Matriks keputusan X[i][j]
     * @param  array  $weights       Bobot [w1..w5]
     * @param  array  $criteriaTypes ['benefit','cost',...]
     * @return array<int, float>     Skor preferensi Ci tiap alternatif
     */
    private function topsis(array $matrix, array $weights, array $criteriaTypes): array
    {
        $n = count($matrix);        // jumlah alternatif
        $m = count($matrix[0]);     // jumlah kriteria

        // Langkah 1-2: Normalisasi Euclidean
        $normalized = $this->euclideanNormalize($matrix, $n, $m);

        // Langkah 3: Matriks ternormalisasi terbobot (V = W × R)
        $weighted = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $weighted[$i][$j] = $weights[$j] * $normalized[$i][$j];
            }
        }

        // Langkah 4: Tentukan PIS (A+) dan NIS (A-)
        $aPlus  = [];
        $aMinus = [];

        for ($j = 0; $j < $m; $j++) {
            $column = array_column($weighted, $j);

            if ($criteriaTypes[$j] === self::BENEFIT) {
                $aPlus[$j]  = max($column);
                $aMinus[$j] = min($column);
            } else {
                // COST: ideal = min, anti-ideal = max
                $aPlus[$j]  = min($column);
                $aMinus[$j] = max($column);
            }
        }

        // Langkah 5: Hitung D+ dan D-
        $dPlus  = [];
        $dMinus = [];

        for ($i = 0; $i < $n; $i++) {
            $sumPlus  = 0;
            $sumMinus = 0;

            for ($j = 0; $j < $m; $j++) {
                $sumPlus  += ($weighted[$i][$j] - $aPlus[$j]) ** 2;
                $sumMinus += ($weighted[$i][$j] - $aMinus[$j]) ** 2;
            }

            $dPlus[$i]  = sqrt($sumPlus);
            $dMinus[$i] = sqrt($sumMinus);
        }

        // Langkah 6: Skor preferensi Ci = D- / (D+ + D-)
        $scores = [];

        for ($i = 0; $i < $n; $i++) {
            $denominator = $dPlus[$i] + $dMinus[$i];
            $scores[$i]  = $denominator > 0 ? $dMinus[$i] / $denominator : 0;
        }

        return $scores;
    }

    /**
     * Normalisasi Euclidean: rij = xij / sqrt(Σ xkj²)
     *
     * Jika semua nilai di kolom = 0, normalisasi = 0.
     */
    private function euclideanNormalize(array $matrix, int $n, int $m): array
    {
        $normalized = [];

        for ($j = 0; $j < $m; $j++) {
            $sumSq = 0;
            for ($i = 0; $i < $n; $i++) {
                $sumSq += $matrix[$i][$j] ** 2;
            }
            $divisor = sqrt($sumSq);

            for ($i = 0; $i < $n; $i++) {
                $normalized[$i][$j] = $divisor > 0
                    ? $matrix[$i][$j] / $divisor
                    : 0;
            }
        }

        return $normalized;
    }

    // ────────────────────────────────────────────────────────────
    //  SAW
    // ────────────────────────────────────────────────────────────

    /**
     * Metode SAW — Simple Additive Weighting.
     *
     * @param  array  $matrix        Matriks keputusan X[i][j]
     * @param  array  $weights       Bobot [w1..w5]
     * @param  array  $criteriaTypes ['benefit','cost',...]
     * @return array<int, float>     Skor preferensi Vi tiap alternatif
     */
    private function saw(array $matrix, array $weights, array $criteriaTypes): array
    {
        $n = count($matrix);
        $m = count($matrix[0]);

        // Normalisasi SAW
        $normalized = $this->sawNormalize($matrix, $n, $m, $criteriaTypes);

        // Hitung Vi = Σ wj * rij
        $scores = [];

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $m; $j++) {
                $sum += $weights[$j] * $normalized[$i][$j];
            }
            $scores[$i] = $sum;
        }

        return $scores;
    }

    /**
     * Normalisasi SAW:
     * - BENEFIT: rij = xij / max(xj)
     * - COST:    rij = min(xj) / xij   (semakin kecil semakin baik)
     *
     * ⚠️ PENTING: Untuk COST criteria, jika ada nilai 0 (ideal):
     * - Campaign dengan nilai 0 mendapat skor 1.0 (paling ideal)
     * - minVal di-exclude nilai 0, hanya dari non-zero values
     * - Ini memastikan campaign yang sudah penuhi target (sisa = 0) 
     *   mendapat skor tertinggi untuk C4 (Sisa Kebutuhan Dana)
     */
    private function sawNormalize(array $matrix, int $n, int $m, array $criteriaTypes): array
    {
        $normalized = [];

        for ($j = 0; $j < $m; $j++) {
            $column = array_column($matrix, $j);
            $maxVal = max($column);
            $minVal = min($column);

            // Untuk COST criteria dengan minVal = 0:
            // Hitung minVal hanya dari nilai > 0 (exclude ideal values)
            $minNonZero = null;
            if ($criteriaTypes[$j] === self::COST && $minVal == 0) {
                $nonZeroValues = array_filter($column, fn($v) => $v > 0);
                $minNonZero = !empty($nonZeroValues) ? min($nonZeroValues) : 0;
            }

            for ($i = 0; $i < $n; $i++) {
                $xij = $matrix[$i][$j];

                if ($criteriaTypes[$j] === self::BENEFIT) {
                    // BENEFIT: rij = xij / max(xj)
                    $normalized[$i][$j] = $maxVal > 0
                        ? $xij / $maxVal
                        : 0;
                } else {
                    // COST: rij = min(non-zero) / xij
                    // Logika: semakin kecil nilai semakin baik, 0 = ideal
                    if ($xij == 0) {
                        // Nilai 0 adalah ideal untuk cost → skor maksimal 1.0
                        $normalized[$i][$j] = 1.0;
                    } elseif ($minVal == 0) {
                        // Ada campaign dengan nilai ideal (0), gunakan minNonZero
                        $normalized[$i][$j] = $minNonZero > 0 
                            ? $minNonZero / $xij 
                            : 0;
                    } else {
                        // Case normal: semua nilai > 0
                        $normalized[$i][$j] = $minVal / $xij;
                    }
                }
            }
        }

        return $normalized;
    }

    // ────────────────────────────────────────────────────────────
    //  Helpers
    // ────────────────────────────────────────────────────────────

    /**
     * Beri label C1–C5 pada array skor mentah.
     *
     * @param  array<int, float>  $scores  [val1, val2, ..., val5]
     * @return array<string, float>        ['C1' => val1, ...]
     */
    private function labelScores(array $scores): array
    {
        $keys = array_keys(self::CRITERIA);

        return array_combine($keys, $scores);
    }

    /**
     * Hitung dan tambahkan ranking ke array hasil berdasarkan kolom skor tertentu.
     *
     * Ranking 1 = skor tertinggi (terbaik).
     *
     * @param  array   $results   Array hasil perhitungan
     * @param  string  $scoreKey  Kunci skor (misal 'topsis_score')
     * @param  string  $rankKey   Kunci ranking (misal 'topsis_rank')
     * @return array
     */
    private function assignRanks(array $results, string $scoreKey, string $rankKey): array
    {
        // Ambil skor dan urutkan descending
        $scores = array_column($results, $scoreKey);
        arsort($scores);

        $rank = 1;
        foreach ($scores as $idx => $score) {
            $results[$idx][$rankKey] = $rank++;
        }

        return $results;
    }

    /**
     * Dapatkan bobot kriteria untuk preset tertentu (C1-C5).
     * Mengecek ke database `spk_weights`, dengan fallback ke konstanta statis.
     */
    public function getPresetWeights(string $preset): array
    {
        try {
            $record = \Illuminate\Support\Facades\DB::table('spk_weights')
                ->where('preset', $preset)
                ->first();

            if ($record) {
                return [
                    (float) $record->c1,
                    (float) $record->c2,
                    (float) $record->c3,
                    (float) $record->c4,
                    (float) $record->c5,
                ];
            }
        } catch (\Exception $e) {
            // Fallback jika terjadi error
        }

        return self::WEIGHT_PRESETS[$preset] ?? self::WEIGHT_PRESETS['default'];
    }
}
