<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitRequest extends Model
{
    protected $fillable = [
        'user_id',
        'kode_kunjungan',
        'nama_pengunjung',
        'jumlah_pengunjung',
        'tanggal_kunjungan',
        'jam_mulai',
        'jam_selesai',
        'tujuan_kunjungan',
        'status',
        'catatan_admin',
        'is_routine',
        'routine_days',
        'routine_end_date',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kunjungan' => 'date',
            'jumlah_pengunjung' => 'integer',
            'is_routine' => 'boolean',
            'routine_end_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk request kunjungan yang sudah disetujui.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
