<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Exports\TagihanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TagihanController extends Controller
{
    /**
     * Menampilkan daftar semua tagihan (Admin).
     */
    public function index()
    {
        $semua_tagihan = Tagihan::with(['sewa.penyewa', 'sewa.unit'])
            ->latest()
            ->get();

        return view('tagihan.index', compact('semua_tagihan'));
    }

    /**
     * Menampilkan halaman pembayaran untuk Penyewa.
     */
    public function bayar(Tagihan $tagihan)
    {
        return view('tagihan.bayar', compact('tagihan'));
    }

    /**
     * Proses Penyewa mengupload bukti bayar.
     */
    public function upload(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:2048',
        ]);

        // Hapus bukti lama jika ada (untuk menghindari file sampah)
        if ($tagihan->bukti_bayar) {
            Storage::disk('public')->delete($tagihan->bukti_bayar);
        }

        // Simpan file baru
        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        // Update database & ubah status
        $tagihan->update([
            'bukti_bayar' => $path,
            'status'      => 'menunggu_verifikasi' // Status berubah agar Admin tahu
        ]);

        // Redirect cerdas: Kembali ke halaman asal user (Admin atau Penyewa)
        $route = Auth::guard('web')->check() ? 'tagihan.index' : 'penyewa.dashboard';

        return redirect()->route($route)
            ->with('success', 'Bukti pembayaran berhasil diupload! Mohon tunggu verifikasi Admin.');
    }

    /**
     * Admin menyetujui pembayaran (LUNAS).
     */
    public function konfirmasi(Tagihan $tagihan)
    {
        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan ini sudah lunas sebelumnya.');
        }

        $tagihan->update(['status' => 'lunas']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS.');
    }

    /**
     * Admin menolak bukti pembayaran.
     */
    public function tolak(Tagihan $tagihan)
    {
        // Hapus file fisik bukti bayar
        if ($tagihan->bukti_bayar) {
            Storage::disk('public')->delete($tagihan->bukti_bayar);
        }

        // Reset data di database
        $tagihan->update([
            'bukti_bayar' => null,
            'status'      => 'belum_bayar' // Kembalikan status agar penyewa upload ulang
        ]);

        return back()->with('error', 'Bukti pembayaran ditolak. Data telah direset.');
    }

    /**
     * Download Laporan Excel dengan Filter Tanggal.
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required'     => 'Silakan pilih tanggal mulai.',
            'end_date.after_or_equal' => 'Tanggal sampai tidak boleh lebih kecil dari tanggal mulai.',
        ]);

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Cek ketersediaan data sebelum download
        $exists = Tagihan::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->exists();

        if (!$exists) {
            return back()->with('error', 'Tidak ada data tagihan pada periode tanggal tersebut.');
        }

        $namaFile = 'laporan_tagihan_' . date('d-m-Y_H-i') . '.xlsx';

        return Excel::download(new TagihanExport($startDate, $endDate), $namaFile);
    }

    /**
     * Download Kuitansi / Nota PDF.
     */
    public function downloadNota($id)
    {
        $tagihan = Tagihan::with(['sewa.penyewa', 'sewa.unit'])->findOrFail($id);

        if ($tagihan->status !== 'lunas') {
            return back()->with('error', 'Nota hanya tersedia untuk tagihan yang sudah lunas.');
        }

        $pdf = Pdf::loadView('pdf.nota', compact('tagihan'));
        
        return $pdf->download('Kuitansi-Kost-' . $tagihan->id . '.pdf');
    }
}