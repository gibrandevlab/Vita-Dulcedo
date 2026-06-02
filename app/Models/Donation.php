<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'campaign_id',
        'nama_lengkap',
        'nomor_telepon',
        'kode_donasi',
        'jumlah_donasi',
        'pesan',
        'bukti_transfer',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_donasi' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Scope untuk donasi yang sudah diverifikasi admin.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
