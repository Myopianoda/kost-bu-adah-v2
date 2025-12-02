<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Sewa;
use App\Models\Tagihan;
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
        // Menampilkan booking pending di paling atas
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
        // 1. CEK STATUS BOOKING (Sudah benar)
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Tindakan ditolak. Booking ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($booking) {
                
                // --- [TAMBAHAN PENTING DI SINI] ---
                // Ambil data unit terbaru & kunci barisnya biar aman (Lock For Update)
                // Kita cek: Jangan sampai kita meng-ACC booking untuk kamar yang SUDAH PENUH.
                $unit = \App\Models\Unit::where('id', $booking->unit_id)
                                        ->lockForUpdate()
                                        ->first();

                if ($unit->status === 'terisi') {
                    // Lempar error agar masuk ke catch dan membatalkan semua proses
                    throw new \Exception('Gagal! Unit ini SUDAH TERISI oleh penyewa lain (Mungkin baru saja di-ACC).');
                }
                // ----------------------------------

                // A. Ubah status booking
                $booking->status = 'approved';
                $booking->save();

                // B. Buat Data Sewa
                $sewa = Sewa::create([
                    'unit_id'       => $booking->unit_id,
                    'penyewa_id'    => $booking->penyewa_id,
                    'tanggal_mulai' => $booking->tanggal_mulai,
                    'status'        => 'aktif',
                ]);

                // C. Ubah Status Unit Menjadi Terisi
                // Kita pakai variabel $unit yang sudah kita lock di atas
                $unit->status = 'terisi';
                $unit->save();

                // D. Buat Tagihan Bulan Pertama
                $jatuhTempo = Carbon::parse($booking->tanggal_mulai);
                $hargaSewa = $unit->price; 

                Tagihan::create([
                    'sewa_id'             => $sewa->id,
                    'tanggal_tagihan'     => Carbon::now(),
                    'tanggal_jatuh_tempo' => $jatuhTempo,          
                    'bulan'               => $jatuhTempo->translatedFormat('F Y'), 
                    'jumlah'              => $hargaSewa, 
                    'status'              => 'belum_bayar',
                    'keterangan'          => 'Tagihan sewa bulan pertama (via Booking)',
                ]);
            });

            return redirect()->back()->with('success', 'Booking diterima! Sewa aktif & Tagihan bulan pertama telah dibuat.');

        } catch (\Exception $e) {
            // Pesan error dari "throw new Exception" di atas akan muncul di sini
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menolak Booking -> Unit kembali Tersedia
     */
    public function reject(Booking $booking)
    {
        // 1. CEK KEAMANAN: Jangan tolak jika sudah diproses
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Tindakan ditolak. Booking ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($booking) {
                // A. Ubah status booking jadi rejected
                $booking->status = 'rejected';
                $booking->save();

                // B. Kembalikan status unit jadi 'tersedia'
                // Hanya ubah jika status unit saat ini adalah 'booking'
                $unit = $booking->unit;
                if ($unit->status === 'booking') {
                    $unit->status = 'tersedia';
                    $unit->save();
                }
            });

            return redirect()->back()->with('success', 'Booking ditolak. Unit kembali tersedia untuk orang lain.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Booking $booking)
    {
        // Cek dulu, kalau statusnya masih 'pending', kita harus bebaskan unitnya
        if ($booking->status == 'pending') {
            $unit = $booking->unit;
            if ($unit) {
                $unit->status = 'tersedia';
                $unit->save();
            }
        }

        // Hapus data booking
        $booking->delete();

        return redirect()->back()->with('success', 'Data booking berhasil dihapus.');
    }
}