<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenyewaExport;

class PenyewaController extends Controller
{
    /**
     * Menampilkan daftar semua penyewa.
     */
    public function index()
    {
        // Mengambil data terbaru tanpa relasi yang bikin error (N+1 fixed)
        $daftar_penyewa = Penyewa::latest()->get();
        return view('penyewa.index', compact('daftar_penyewa'));
    }

    /**
     * Menampilkan form untuk menambah penyewa baru.
     */
    public function create()
    {
        return view('penyewa.create');
    }

    /**
     * Menyimpan penyewa baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon',
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp',
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'        => 'required|string|min:8',
        ]);

        // 2. Handle Upload Foto
        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }

        // 3. Hash Password & Simpan
        $validated['password'] = Hash::make($validated['password']);
        Penyewa::create($validated);

        return redirect()->route('penyewa.index')->with('success', 'Penyewa baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data penyewa.
     */
    public function edit(Penyewa $penyewa)
    {
        return view('penyewa.edit', compact('penyewa'));
    }

    /**
     * Mengupdate data penyewa di database.
     */
    public function update(Request $request, Penyewa $penyewa)
    {
        // 1. Validasi (Perhatikan pengecualian unique ID di sini)
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'telepon'         => 'required|string|unique:penyewas,telepon,' . $penyewa->id,
            'nomor_ktp'       => 'required|string|unique:penyewas,nomor_ktp,' . $penyewa->id,
            'alamat_asal'     => 'required|string',
            'foto_ktp'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'        => 'nullable|string|min:8',
        ]);

        // 2. Handle Upload Foto Baru & Hapus Foto Lama
        if ($request->hasFile('foto_ktp')) {
            if ($penyewa->foto_ktp) {
                Storage::disk('public')->delete($penyewa->foto_ktp);
            }
            $path = $request->file('foto_ktp')->store('foto_ktp', 'public');
            $validated['foto_ktp'] = $path;
        }

        // 3. Cek Password (Update jika diisi, abaikan jika kosong)
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // 4. Update Data
        $penyewa->update($validated);

        return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil diupdate!');
    }

    /**
     * Menghapus data penyewa.
     */
    public function destroy(Penyewa $penyewa)
    {
        // Hapus file foto fisik di storage
        if ($penyewa->foto_ktp) {
            Storage::disk('public')->delete($penyewa->foto_ktp);
        }
        
        // Hapus data di database
        $penyewa->delete();

        return redirect()->route('penyewa.index')->with('success', 'Data penyewa berhasil dihapus!');
    }

    /**
     * Export Excel dengan Validasi & Filter Tanggal
     */
    public function exportExcel(Request $request) 
    {
        // 1. Validasi Tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'end_date.after_or_equal' => 'Tanggal sampai tidak boleh lebih kecil dari tanggal mulai!',
            'start_date.required'     => 'Silakan pilih tanggal mulai terlebih dahulu.',
        ]);

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // 2. Cek Data Kosong Sebelum Download
        $exists = Penyewa::whereDate('created_at', '>=', $startDate)
                         ->whereDate('created_at', '<=', $endDate)
                         ->exists();

        if (!$exists) {
            return back()->with('error', 'Tidak ada data penyewa pada periode tanggal tersebut.');
        }

        // 3. Download Excel
        $namaFile = 'laporan_penyewa_' . date('d-m-Y_H-i') . '.xlsx';
        return Excel::download(new PenyewaExport($startDate, $endDate), $namaFile);
    }
}