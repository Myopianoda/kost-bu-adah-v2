<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PenyewaRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function create()
    {
        return view('auth.penyewa-register');
    }

    /**
     * Proses simpan data registrasi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'telepon'      => ['required', 'string', 'unique:penyewas,telepon'],
            'nomor_ktp'    => ['required', 'string', 'unique:penyewas,nomor_ktp'],
            'alamat_asal'  => ['required', 'string'],
            'foto_ktp'     => ['required', 'image', 'max:2048'], // Wajib Upload Image Max 2MB
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Upload Foto KTP
        $pathKtp = $request->file('foto_ktp')->store('foto_ktp', 'public');

        // 2. Buat Data Penyewa
        $penyewa = Penyewa::create([
            'nama_lengkap' => $request->nama_lengkap,
            'telepon'      => $request->telepon,
            'nomor_ktp'    => $request->nomor_ktp,
            'alamat_asal'  => $request->alamat_asal,
            'foto_ktp'     => $pathKtp,
            'password'     => Hash::make($request->password),
        ]);

        // 3. Auto Login setelah daftar
        Auth::guard('penyewa')->login($penyewa);

        // 4. Redirect ke Dashboard Penyewa (atau ke halaman Booking nanti)
        return redirect()->route('penyewa.dashboard');
    }
}