<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kita ubah kolom ENUM untuk menambahkan 'booking'
        DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('tersedia', 'terisi', 'booking') NOT NULL DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        // Kembalikan ke kondisi semula jika di-rollback
        DB::statement("ALTER TABLE units MODIFY COLUMN status ENUM('tersedia', 'terisi') NOT NULL DEFAULT 'tersedia'");
    }
};
