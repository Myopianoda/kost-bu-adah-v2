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

        // Cek awal di halaman depan
        if ($unit->status != 'tersedia') {
            return redirect()->back()->with('error', 'Maaf, unit ini baru saja tidak tersedia.');
        }

        return view('portal.booking-create', compact('unit'));
    }

    /**
     * Menyimpan data booking dengan perlindungan Race Condition.
     */
    public function store(Request $request, $unitId)
    {
        // 1. Validasi Input
        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi'        => 'required|integer|min:1', 
        ]);

        try {
            // 2. Gunakan Transaksi Database Manual untuk kontrol penuh
            DB::beginTransaction();

            // A. AMBIL DAN KUNCI DATA UNIT (PENTING!)
            // 'lockForUpdate()' akan menahan baris data ini. 
            // User lain harus menunggu sampai transaksi ini selesai (commit/rollback).
            $unit = Unit::where('id', $unitId)->lockForUpdate()->first();

            // Jika unit tidak ditemukan
            if (!$unit) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Unit tidak ditemukan.');
            }

            // B. CEK ULANG STATUS (CRITICAL POINT)
            // Meskipun di halaman sebelumnya status 'tersedia', 
            // bisa jadi sepersekian detik lalu sudah dibooking orang lain.
            if ($unit->status !== 'tersedia') {
                DB::rollBack(); // Batalkan proses
                return redirect()->route('penyewa.dashboard')
                                 ->with('error', 'Gagal! Unit ini baru saja dibooking oleh orang lain.');
            }

            // C. Buat Data Booking
            Booking::create([
                'unit_id'       => $unit->id,
                'penyewa_id'    => Auth::guard('penyewa')->id(),
                'tanggal_mulai' => $request->tanggal_mulai,
                'status'        => 'pending', 
                'expired_at'    => Carbon::now()->addHours(24), 
            ]);

            // D. Ubah Status Unit Jadi 'booking'
            $unit->status = 'booking';
            $unit->save();

            // Simpan perubahan permanen ke database
            DB::commit();

            // 3. Redirect Sukses
            return redirect()->route('penyewa.dashboard')
                             ->with('success', 'Booking berhasil diajukan! Mohon tunggu konfirmasi Admin.');

        } catch (\Exception $e) {
            // Jika ada error teknis lain, batalkan semua perubahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}