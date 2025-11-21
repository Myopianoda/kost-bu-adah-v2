<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TagihanExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TagihanController extends Controller
{
    public function index()
    {
        $semua_tagihan = Tagihan::with(['sewa.penyewa', 'sewa.unit'])
                            ->latest()
                            ->get();

        return view('tagihan.index', compact('semua_tagihan'));
    }

    // ... di dalam class TagihanController ...

    public function bayar(Tagihan $tagihan)
    {
        // Hapus semua logika Midtrans/Snap Token di sini.
        // Kita hanya perlu menampilkan view, data tagihan sudah otomatis dikirim.
        
        return view('tagihan.bayar', compact('tagihan'));
    }

    public function exportExcel() 
    {
        // Perintah ini akan memicu download file
        return Excel::download(new TagihanExport, 'laporan_tagihan_kost_bu_adah.xlsx');
    }

    /**
     * Penyewa mengupload bukti bayar
     */
    public function upload(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:2048',
        ]);

        // Simpan file
        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        // Update database
        $tagihan->bukti_bayar = $path;
        // Status tetap 'belum_bayar', tapi nanti di view kita cek kalau ada bukti = 'menunggu verifikasi'
        $tagihan->save();

        $redirect = Auth::guard('web')->check() ? 'tagihan.index' : 'penyewa.dashboard';
        return redirect()->route($redirect)->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi Admin.');
    }

    /**
     * Admin mengonfirmasi pembayaran (Lunas)
     */
    public function konfirmasi(Tagihan $tagihan)
    {
        $tagihan->status = 'lunas';
        $tagihan->save();

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS.');
    }

    public function tolak(Tagihan $tagihan)
    {
        // 1. Hapus file foto lama dari penyimpanan
        if ($tagihan->bukti_bayar) {
            Storage::disk('public')->delete($tagihan->bukti_bayar);
        }

        // 2. Kosongkan kolom bukti_bayar di database
        $tagihan->bukti_bayar = null;
        // Status otomatis tetap/kembali ke 'belum_bayar'
        $tagihan->save();

        return redirect()->back()->with('error', 'Bukti pembayaran ditolak. Penyewa diminta upload ulang.');
    }
}