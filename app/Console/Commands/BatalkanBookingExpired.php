<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BatalkanBookingExpired extends Command
{
    // Nama perintah untuk dipanggil robot
    protected $signature = 'app:batalkan-booking-expired';

    // Penjelasan perintah
    protected $description = 'Membatalkan booking yang sudah melewati batas waktu pembayaran/konfirmasi';

    public function handle()
    {
        $this->info('Memeriksa booking yang kadaluwarsa...');

        // 1. Cari semua booking yang statusnya 'pending' DAN waktu expired-nya sudah lewat dari sekarang
        $expiredBookings = Booking::where('status', 'pending')
                                  ->where('expired_at', '<', Carbon::now())
                                  ->with('unit') // Ambil data unitnya sekalian
                                  ->get();

        $jumlah = 0;

        foreach ($expiredBookings as $booking) {
            DB::transaction(function () use ($booking) {
                // A. Ubah status booking jadi 'expired'
                $booking->status = 'expired';
                $booking->save();

                // B. Kembalikan status unit jadi 'tersedia'
                $unit = $booking->unit;
                if ($unit) {
                    $unit->status = 'tersedia';
                    $unit->save();
                }
            });

            $this->info("- Booking ID {$booking->id} (Unit: {$booking->unit->name}) telah dibatalkan.");
            $jumlah++;
        }

        $this->info("Selesai. Total booking dibatalkan: {$jumlah}");
    }
}