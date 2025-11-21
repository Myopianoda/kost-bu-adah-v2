<?php

namespace App\Exports;

use App\Models\Penyewa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PenyewaExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        // FIX: Hapus with('unit'), kembalikan ke query standar
        $query = Penyewa::query(); 

        // Filter Tanggal
        if ($this->startDate && $this->endDate) {
            $query->whereDate('created_at', '>=', $this->startDate)
                  ->whereDate('created_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID', 
            'Nama Lengkap', 
            'Telepon', 
            'Nomor KTP', 
            'Alamat Asal', 
            'Tgl Daftar'
        ];
    }

    public function map($penyewa): array
    {
        return [
            $penyewa->id,
            $penyewa->nama_lengkap,
            $penyewa->telepon,
            // Tanda kutip satu (') biar excel baca sebagai teks (nol di depan aman)
            "'" . $penyewa->nomor_ktp, 
            $penyewa->alamat_asal,
            $penyewa->created_at->format('d-m-Y'),
        ];
    }
}