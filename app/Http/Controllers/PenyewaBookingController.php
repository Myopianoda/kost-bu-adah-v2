<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenyewaBookingController extends Controller
{
    /**
     * Menampilkan form booking.
     */
    public function create($unitId)
    {
        $unit = Unit::findOrFail($unitId);

        // Cek jika unit sudah tidak tersedia (misal dibooking orang lain duluan)
        if ($unit->status != 'tersedia') {
            return redirect()->back()->with('error', 'Maaf, unit ini baru saja tidak tersedia.');
        }

        return view('portal.booking-create', compact('unit'));
    }

    /**
     * Menyimpan data booking.
     */
    public function store(Request $request, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        // 1. Validasi Input
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1', // Rencana sewa berapa bulan (opsional, buat info admin aja)
        ]);

        // 2. Gunakan Transaksi Database agar aman
        DB::transaction(function () use ($request, $unit) {

            // A. Buat Data Booking
            Booking::create([
                'unit_id' => $unit->id,
                'penyewa_id' => Auth::guard('penyewa')->id(),
                'tanggal_mulai' => $request->tanggal_mulai,
                'status' => 'pending', // Menunggu ACC Admin
                // Set batas waktu konfirmasi/bayar 24 jam dari sekarang
                'expired_at' => Carbon::now()->addHours(24), 
            ]);

            // B. Ubah Status Unit Jadi 'booking'
            // Agar orang lain tidak bisa membooking kamar ini sementara waktu
            $unit->status = 'booking';
            $unit->save();
        });

        // 3. Redirect ke Dashboard dengan pesan sukses
        return redirect()->route('penyewa.dashboard')
                         ->with('success', 'Booking berhasil diajukan! Mohon tunggu konfirmasi Admin atau hubungi WA Admin.');
    }
}