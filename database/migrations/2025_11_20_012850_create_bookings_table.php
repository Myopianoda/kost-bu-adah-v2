<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Relasi ke Unit
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');

            // Relasi ke Penyewa (Perhatikan nama tabelnya 'penyewas')
            $table->foreignId('penyewa_id')->constrained('penyewas')->onDelete('cascade');

            // Informasi Booking
            $table->date('tanggal_mulai'); // Kapan mau masuk
            // Status booking:
            // pending = Menunggu ACC Admin
            // approved = Di-ACC (Pindah ke tabel Sewa)
            // rejected = Ditolak Admin
            // expired = Telat bayar/konfirmasi (Otomatis batal)
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');

            // Batas waktu (Timer)
            $table->timestamp('expired_at'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
