<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('promo_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('ongkos_kirim', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2);
            $table->text('alamat_pengiriman');
            $table->string('metode_pembayaran');
            $table->string('metode_pengiriman');
            $table->enum('status', ['unpaid', 'menunggu_konfirmasi', 'diproses', 'dikirim', 'selesai', 'dibatalkan', 'expired'])->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
