<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE tagihans
            MODIFY COLUMN status
            ENUM(
                'belum_bayar',
                'menunggu_verifikasi',
                'lunas',
                'terlambat'
            )
            NOT NULL DEFAULT 'belum_bayar'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kalau ada data yang sedang menunggu verifikasi,
        // kembalikan dulu supaya ENUM lama bisa diterapkan.
        DB::table('tagihans')
            ->where('status', 'menunggu_verifikasi')
            ->update([
                'status' => 'belum_bayar',
            ]);

        DB::statement("
            ALTER TABLE tagihans
            MODIFY COLUMN status
            ENUM(
                'belum_bayar',
                'lunas',
                'terlambat'
            )
            NOT NULL DEFAULT 'belum_bayar'
        ");
    }
};