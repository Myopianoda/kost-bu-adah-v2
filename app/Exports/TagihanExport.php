<?php

namespace App\Exports;

use App\Models\Tagihan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable; // Tambahan helper
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Agar kolom otomatis lebar
use Carbon\Carbon;

class TagihanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    // 1. BAGIAN INI YANG KURANG TADI (Constructor)
    // Kita siapkan 'pintu' untuk menerima tanggal dari Controller
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Database\Query\Builder
    */
    public function query()
    {
        // Ambil data tagihan beserta relasi sewa (untuk dapat nama penyewa & unit)
        $query = Tagihan::query()->with(['sewa.penyewa', 'sewa.unit']);

        // 2. LOGIKA FILTER
        // Jika tanggal diisi, kita filter berdasarkan created_at (atau tanggal_tagihan)
        if ($this->startDate && $this->endDate) {
            $query->whereDate('created_at', '>=', $this->startDate)
                  ->whereDate('created_at', '<=', $this->endDate);
        }

        return $query;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID Tagihan',
            'Nama Penyewa',
            'Unit',
            'Bulan', // Tambahan biar jelas
            'Jumlah',
            'Status',
            'Tanggal Tagihan',
            'Tanggal Jatuh Tempo',
        ];
    }

    /**
    * @param mixed $tagihan
    * @return array
    */
    public function map($tagihan): array
    {
        // Kita pakai optional(...) atau null coalescing (??) biar tidak error kalau data dihapus
        return [
            $tagihan->id,
            
            // Mengambil nama dari relasi: Tagihan -> Sewa -> Penyewa
            $tagihan->sewa->penyewa->nama_lengkap ?? 'Data Terhapus',
            
            // Mengambil unit dari relasi: Tagihan -> Sewa -> Unit
            $tagihan->sewa->unit->name ?? 'Data Terhapus',
            
            $tagihan->bulan ?? '-',
            
            // Format uang (Rp ...)
            'Rp ' . number_format($tagihan->jumlah, 0, ',', '.'),
            
            ucfirst($tagihan->status),
            
            Carbon::parse($tagihan->tanggal_tagihan)->format('d-m-Y'),
            Carbon::parse($tagihan->tanggal_jatuh_tempo)->format('d-m-Y'),
        ];
    }
}