<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'sewa_id',
        'jumlah',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'bulan',        // <-- PENTING: Diperlukan untuk controller baru
        'status',
        'bukti_bayar',
        'keterangan',   // <-- PENTING: Diperlukan untuk controller baru
        'midtrans_order_id',
    ];

    /**
     * Konversi otomatis tipe data.
     * Ini membuat tanggal langsung jadi objek Carbon (mudah diformat).
     */
    protected $casts = [
        'tanggal_tagihan'     => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'jumlah'              => 'integer',
    ];

    /**
     * Relasi ke model Sewa.
     */
    public function sewa()
    {
        return $this->belongsTo(Sewa::class);
    }
}