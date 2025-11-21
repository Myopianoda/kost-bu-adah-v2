<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'penyewa_id',
        'tanggal_mulai',
        'status',
        'expired_at',
    ];

    // Relasi ke Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi ke Penyewa
    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class);
    }
}