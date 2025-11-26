<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Tagihan;
use App\Models\Sewa;
use App\Models\Booking; // <--- WAJIB ADA INI
use Carbon\Carbon;

class PortalController extends Controller
{
    public function index()
    {
        $penyewa = Auth::guard('penyewa')->user();

        // --- [BAGIAN YANG HILANG TADI] ---
        // 1. Cek apakah ada Booking yang masih Pending?
        $bookingPending = Booking::with('unit')
            ->where('penyewa_id', $penyewa->id)
            ->where('status', 'pending')
            ->first();

        // 2. Ambil Data Sewa Aktif
        $sewaAktif = Sewa::with(['unit', 'tagihan'])
            ->where('penyewa_id', $penyewa->id)
            ->where('status', 'aktif')
            ->first();

        // 3. Ambil Riwayat Tagihan
        $riwayatTagihan = $sewaAktif ? $sewaAktif->tagihan->sortBy('tanggal_jatuh_tempo') : collect();

        // 4. GENERATE TIMELINE (3 Bulan)
        $timeline = collect();

        if ($sewaAktif) {
            $tglMasuk = Carbon::parse($sewaAktif->tanggal_mulai)->day;

            for ($i = 0; $i < 3; $i++) {
                $bulanCek = Carbon::now()->addMonths($i);
                
                try {
                    $tanggalJatuhTempo = $bulanCek->copy()->day($tglMasuk);
                } catch (\Exception $e) {
                    $tanggalJatuhTempo = $bulanCek->copy()->endOfMonth();
                }

                $existingTagihan = $riwayatTagihan->filter(function($tagihan) use ($tanggalJatuhTempo) {
                    return Carbon::parse($tagihan->tanggal_jatuh_tempo)->isSameMonth($tanggalJatuhTempo)
                        && Carbon::parse($tagihan->tanggal_jatuh_tempo)->isSameYear($tanggalJatuhTempo);
                })->first();

                // Logika Status Pintar
                $statusTampil = 'estimasi'; 
                if ($existingTagihan) {
                    if ($existingTagihan->status == 'lunas' || $existingTagihan->status == 'menunggu_verifikasi') {
                        $statusTampil = $existingTagihan->status;
                    } elseif ($existingTagihan->status == 'belum_bayar' && !empty($existingTagihan->bukti_bayar)) {
                        $statusTampil = 'menunggu_verifikasi';
                    } else {
                        $statusTampil = 'belum_bayar';
                    }
                }

                $timeline->push([
                    'bulan'               => $tanggalJatuhTempo->translatedFormat('F Y'),
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'status'              => $statusTampil,
                    'tagihan_id'          => $existingTagihan ? $existingTagihan->id : null,
                    'jumlah'              => $existingTagihan ? ($existingTagihan->jumlah ?? $existingTagihan->total_tagihan) : ($sewaAktif->unit->harga ?? $sewaAktif->unit->price),
                ]);
            }
        }

        // PENTING: Kirim variabel $bookingPending ke view
        return view('portal.dashboard', compact('penyewa', 'timeline', 'bookingPending'));
    }

    public function editProfil()
    {
        return view('penyewa.profil');
    }

    public function updateProfil(Request $request)
    {
        $penyewa = Auth::guard('penyewa')->user();
        
        /** @var \App\Models\Penyewa $penyewa */

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', Password::min(8), 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $penyewa->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ])->onlyInput('current_password');
        }

        $penyewa->password = Hash::make($validated['password']);
        $penyewa->save();

        return redirect()->route('penyewa.profil.edit')
            ->with('success', 'Password berhasil diperbarui!');
    }
}