<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Sewa;
use App\Models\Tagihan; // Pastikan Model Tagihan di-import
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking yang masuk.
     */
    public function index()
    {
        $bookings = Booking::with(['unit', 'penyewa'])
                           ->orderByRaw("FIELD(status, 'pending') DESC")
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Menerima (ACC) Booking -> Jadi Sewa Aktif & Buat Tagihan
     */
    public function approve(Booking $booking)
    {
        DB::transaction(function () use ($booking) {

            // 1. Ubah status booking
            $booking->status = 'approved';
            $booking->save();

            // 2. Buat Data Sewa
            $sewa = Sewa::create([
                'unit_id'       => $booking->unit_id,
                'penyewa_id'    => $booking->penyewa_id,
                'tanggal_mulai' => $booking->tanggal_mulai,
                'status'        => 'aktif',
            ]);

            // 3. Ubah Status Unit
            $unit = $booking->unit;
            $unit->status = 'terisi';
            $unit->save();

            // 4. Buat Tagihan (VERSI FIX)
            $jatuhTempo = Carbon::parse($booking->tanggal_mulai);
            
            // Cek harga di model Unit (harga atau price)
            $hargaSewa = $unit->harga ?? $unit->price;

            Tagihan::create([
                'sewa_id'             => $sewa->id,
                // 'penyewa_id'       => ... // <--- HAPUS INI
                // 'unit_id'          => ... // <--- HAPUS INI
                'tanggal_tagihan'     => Carbon::now(),
                'tanggal_jatuh_tempo' => $jatuhTempo,          
                'bulan'               => $jatuhTempo->translatedFormat('F Y'), 
                'jumlah'              => $hargaSewa, 
                'status'              => 'belum_bayar',
                'keterangan'          => 'Tagihan sewa bulan pertama (via Booking)',
            ]);
        });

        return redirect()->back()->with('success', 'Booking diterima! Sewa aktif & Tagihan dibuat.');
    }

    /**
     * Menolak Booking -> Unit kembali Tersedia
     */
    public function reject(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            // 1. Ubah status booking jadi rejected
            $booking->status = 'rejected';
            $booking->save();

            // 2. Kembalikan status unit jadi 'tersedia'
            $unit = $booking->unit;
            $unit->status = 'tersedia';
            $unit->save();
        });

        return redirect()->back()->with('success', 'Booking ditolak. Unit kembali tersedia.');
    }
}