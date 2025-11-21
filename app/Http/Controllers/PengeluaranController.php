<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengeluaranExport;

class PengeluaranController extends Controller
{
    /**
     * Menampilkan daftar semua pengeluaran.
     */
    public function index()
    {
        $daftarPengeluaran = Pengeluaran::latest('tanggal')->get();
        return view('pengeluaran.index', compact('daftarPengeluaran'));
    }

    /**
     * Menampilkan form untuk menambah pengeluaran baru.
     */
    public function create()
    {
        return view('pengeluaran.create');
    }

    /**
     * Menyimpan pengeluaran baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah'     => 'required|integer|min:0',
            'tanggal'    => 'required|date',
        ]);

        Pengeluaran::create($validated);

        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil disimpan!');
    }

    public function edit(Pengeluaran $pengeluaran)
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah'     => 'required|integer|min:0',
            'tanggal'    => 'required|date',
        ]);

        $pengeluaran->update($validated);

        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil di-update!');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')
                         ->with('success', 'Data pengeluaran berhasil dihapus!');
    }
    
    /**
     * EXPORT EXCEL DENGAN FILTER TANGGAL
     */
    public function exportExcel(Request $request) 
    {
        // 1. Validasi input tanggal dari form
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required'     => 'Silakan pilih tanggal mulai.',
            'end_date.after_or_equal' => 'Tanggal sampai harus lebih besar dari tanggal mulai.',
        ]);

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // 2. Cek apakah ada data di rentang tanggal tersebut?
        // Kita pakai kolom 'tanggal' sesuai struktur databasemu
        $exists = Pengeluaran::whereDate('tanggal', '>=', $startDate)
                             ->whereDate('tanggal', '<=', $endDate)
                             ->exists();

        // 3. Jika data KOSONG, stop proses dan beri pesan error
        if (!$exists) {
            return back()->with('error', 'Tidak ada data pengeluaran pada periode tanggal tersebut.');
        }

        // 4. Jika data ADA, lakukan download
        $namaFile = 'laporan_pengeluaran_' . date('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new PengeluaranExport($startDate, $endDate), $namaFile);
    }
}