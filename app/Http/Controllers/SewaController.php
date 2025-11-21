<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use App\Models\Sewa;
use App\Models\Unit;
use App\Models\Tagihan; // <-- PENTING: Jangan lupa import model Tagihan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SewaController extends Controller
{
    public function create(Request $request)
    {
        $unit = Unit::findOrFail($request->query('unit'));
        $daftar_penyewa = Penyewa::orderBy('nama_lengkap')->get();

        return view('sewa.create', compact('unit', 'daftar_penyewa'));
    }

    public function store(Request $request)
{
    $request->validate([
        'unit_id'       => 'required|exists:units,id',
        'penyewa_id'    => 'required|exists:penyewas,id',
        'tanggal_mulai' => 'required|date',
    ]);

    DB::transaction(function () use ($request) {
        
        // 1. Ambil Data Unit (untuk tahu harganya)
        $unit = Unit::findOrFail($request->unit_id);
        
        // Pastikan kita pakai harga yang benar. Cek model Unit kamu, 
        // apakah pakai 'harga' atau 'price'? Saya pakai 'harga' di sini.
        // Jika error, ganti jadi $unit->price
        $hargaSewa = $unit->harga ?? $unit->price; 

        // 2. Buat Sewa
        $sewa = Sewa::create([
            'unit_id'       => $request->unit_id,
            'penyewa_id'    => $request->penyewa_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status'        => 'aktif', 
        ]);

        // 3. Update Unit
        $unit->status = 'terisi';
        $unit->save();

        // 4. Buat Tagihan (VERSI FIX: Tanpa penyewa_id & unit_id)
        $jatuhTempo = Carbon::parse($request->tanggal_mulai);
        
        Tagihan::create([
            'sewa_id'             => $sewa->id,
            // 'penyewa_id'       => $request->penyewa_id, // <--- HAPUS INI
            // 'unit_id'          => $request->unit_id,    // <--- HAPUS INI
            'tanggal_tagihan'     => Carbon::now(),
            'tanggal_jatuh_tempo' => $jatuhTempo,
            'bulan'               => $jatuhTempo->translatedFormat('F Y'),
            
            'jumlah'              => $hargaSewa, // Pastikan kolom di DB tagihan adalah 'jumlah'
            
            'status'              => 'belum_bayar',
            'keterangan'          => 'Tagihan sewa bulan pertama',
        ]);
    });

    return redirect()->route('units.index')->with('success', 'Unit berhasil disewakan dan Tagihan dibuat!');
}

    public function stop(Sewa $sewa)
    {
        DB::transaction(function () use ($sewa) {
            
            // 1. Update status unit kembali menjadi "tersedia"
            $unit = $sewa->unit;
            $unit->status = 'tersedia';
            $unit->save();

            // 2. Update status sewa menjadi "selesai"
            $sewa->status = 'selesai';
            $sewa->tanggal_selesai = Carbon::now();
            $sewa->save();
        });

        return redirect()->route('units.index')
                         ->with('success', 'Sewa telah berhasil dihentikan dan unit kembali tersedia.');
    }
}