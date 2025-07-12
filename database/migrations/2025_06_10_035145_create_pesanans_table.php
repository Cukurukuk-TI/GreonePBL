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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('produk_id')->constrained()->onDelete('cascade');
            $table->foreignId('promo_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('ongkos_kirim', 15, 2)->default(10000);
            $table->decimal('pajak', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2);
            $table->text('alamat_pengiriman');
            $table->string('metode_pembayaran')->default('BNI Virtual Account');
            $table->string('metode_pengiriman')->default('SiCepat Ultimate');
            $table->enum('status', ['pending', 'proses', 'complete'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
