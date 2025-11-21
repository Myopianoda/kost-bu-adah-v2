<?php

namespace App\Exports;

use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;     // Tambahan helper
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Agar kolom otomatis lebar
use Carbon\Carbon;

class PengeluaranExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    // 1. Constructor untuk menangkap data dari Controller
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    // 2. Query dengan Filter Tanggal
    public function query() 
    { 
        $query = Pengeluaran::query();

        if ($this->startDate && $this->endDate) {
            // Filter berdasarkan kolom 'tanggal' di database
            $query->whereDate('tanggal', '>=', $this->startDate)
                  ->whereDate('tanggal', '<=', $this->endDate);
        }

        // Urutkan berdasarkan tanggal transaksi
        return $query->orderBy('tanggal', 'asc');
    }

    public function headings(): array 
    { 
        return [
            'ID', 
            'Tanggal Transaksi', 
            'Keterangan', 
            'Jumlah (Rp)',
            'Dicatat Pada'
        ]; 
    }

    public function map($pengeluaran): array 
    {
        return [
            $pengeluaran->id,
            Carbon::parse($pengeluaran->tanggal)->format('d-m-Y'),
            $pengeluaran->keterangan,
            'Rp ' . number_format($pengeluaran->jumlah, 0, ',', '.'), // Format Rupiah
            $pengeluaran->created_at->format('d-m-Y H:i'), // Kapan data diinput admin
        ];
    }
}