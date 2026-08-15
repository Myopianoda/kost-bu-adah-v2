<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Penyewa;
use App\Models\Sewa;
use App\Models\Tagihan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin Demo
        |--------------------------------------------------------------------------
        */
        User::updateOrCreate(
            ['email' => 'admin@kostbuadah.test'],
            [
                'name' => 'Admin Kost Bu Adah',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Unit Demo
        |--------------------------------------------------------------------------
        */
        $unitTersedia = Unit::updateOrCreate(
            ['name' => 'Kamar Mawar 01'],
            [
                'description' => 'Kamar demo dengan fasilitas dasar.',
                'price' => 850000,
                'status' => 'tersedia',
                'gambar' => null,
            ]
        );

        $unitBooking = Unit::updateOrCreate(
            ['name' => 'Kamar Mawar 02'],
            [
                'description' => 'Kamar demo yang sedang dalam proses booking.',
                'price' => 900000,
                'status' => 'booking',
                'gambar' => null,
            ]
        );

        $unitTerisi = Unit::updateOrCreate(
            ['name' => 'Kamar Melati 01'],
            [
                'description' => 'Kamar demo dengan penyewa aktif.',
                'price' => 1000000,
                'status' => 'terisi',
                'gambar' => null,
            ]
        );

        $unitRiwayat = Unit::updateOrCreate(
            ['name' => 'Kamar Melati 02'],
            [
                'description' => 'Kamar demo dengan riwayat penyewaan selesai.',
                'price' => 1100000,
                'status' => 'tersedia',
                'gambar' => null,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Penyewa Demo
        |--------------------------------------------------------------------------
        */
        $penyewaBooking = Penyewa::updateOrCreate(
            ['telepon' => '081200000001'],
            [
                'nama_lengkap' => 'Raka Pratama',
                'nomor_ktp' => '3201010101010001',
                'alamat_asal' => 'Purwakarta, Jawa Barat',
                'foto_ktp' => null,
                'password' => Hash::make('password'),
            ]
        );

        $penyewaAktif = Penyewa::updateOrCreate(
            ['telepon' => '081200000002'],
            [
                'nama_lengkap' => 'Siti Aulia',
                'nomor_ktp' => '3201010101010002',
                'alamat_asal' => 'Karawang, Jawa Barat',
                'foto_ktp' => null,
                'password' => Hash::make('password'),
            ]
        );

        $penyewaSelesai = Penyewa::updateOrCreate(
            ['telepon' => '081200000003'],
            [
                'nama_lengkap' => 'Dimas Saputra',
                'nomor_ktp' => '3201010101010003',
                'alamat_asal' => 'Subang, Jawa Barat',
                'foto_ktp' => null,
                'password' => Hash::make('password'),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Booking Demo
        |--------------------------------------------------------------------------
        */
        Booking::updateOrCreate(
            [
                'unit_id' => $unitBooking->id,
                'penyewa_id' => $penyewaBooking->id,
                'status' => 'pending',
            ],
            [
                'tanggal_mulai' => now()->addDays(7)->toDateString(),
                'expired_at' => now()->addDays(2),
            ]
        );

        Booking::updateOrCreate(
            [
                'unit_id' => $unitTerisi->id,
                'penyewa_id' => $penyewaAktif->id,
                'status' => 'approved',
            ],
            [
                'tanggal_mulai' => now()->subDays(30)->toDateString(),
                'expired_at' => now()->subDays(28),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Sewa Aktif
        |--------------------------------------------------------------------------
        */
        $sewaAktif = Sewa::updateOrCreate(
            [
                'penyewa_id' => $penyewaAktif->id,
                'unit_id' => $unitTerisi->id,
            ],
            [
                'tanggal_mulai' => now()->subMonth()->toDateString(),
                'tanggal_selesai' => null,
                'status' => 'aktif',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Riwayat Sewa Selesai
        |--------------------------------------------------------------------------
        */
        $sewaSelesai = Sewa::updateOrCreate(
            [
                'penyewa_id' => $penyewaSelesai->id,
                'unit_id' => $unitRiwayat->id,
            ],
            [
                'tanggal_mulai' => now()->subMonths(4)->toDateString(),
                'tanggal_selesai' => now()->subMonth()->toDateString(),
                'status' => 'selesai',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Tagihan Demo
        |--------------------------------------------------------------------------
        */
        Tagihan::updateOrCreate(
            [
                'sewa_id' => $sewaAktif->id,
                'keterangan' => 'Demo - Tagihan bulan berjalan',
            ],
            [
                'jumlah' => $unitTerisi->price,
                'tanggal_tagihan' => now()->toDateString(),
                'tanggal_jatuh_tempo' => now()->addDays(10)->toDateString(),
                'bulan' => now()->locale('id')->translatedFormat('F Y'),
                'status' => 'belum_bayar',
                'bukti_bayar' => null,
                'midtrans_order_id' => null,
            ]
        );

        Tagihan::updateOrCreate(
            [
                'sewa_id' => $sewaAktif->id,
                'keterangan' => 'Demo - Tagihan terlambat',
            ],
            [
                'jumlah' => $unitTerisi->price,
                'tanggal_tagihan' => now()->subMonth()->toDateString(),
                'tanggal_jatuh_tempo' => now()->subDays(15)->toDateString(),
                'bulan' => now()->subMonth()->locale('id')->translatedFormat('F Y'),
                'status' => 'terlambat',
                'bukti_bayar' => null,
                'midtrans_order_id' => null,
            ]
        );

        Tagihan::updateOrCreate(
            [
                'sewa_id' => $sewaSelesai->id,
                'keterangan' => 'Demo - Tagihan lunas',
            ],
            [
                'jumlah' => $unitRiwayat->price,
                'tanggal_tagihan' => now()->subMonths(2)->toDateString(),
                'tanggal_jatuh_tempo' => now()->subMonths(2)->addDays(10)->toDateString(),
                'bulan' => now()->subMonths(2)->locale('id')->translatedFormat('F Y'),
                'status' => 'lunas',
                'bukti_bayar' => null,
                'midtrans_order_id' => null,
            ]
        );
    }
}