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
        Schema::table('pesanans', function (Blueprint $table) {
            // Kolom-kolom ini berkaitan dengan detail item tunggal dan tidak
            // diperlukan lagi di tabel pesanan utama untuk sistem keranjang.
            $columnsToDrop = [
                'produk_id',
                'promo_id',
                'jumlah',
                'harga_satuan',
                'subtotal',
                'diskon',
                'ongkos_kirim',
                'pajak',
                'metode_pembayaran',
                'metode_pengiriman'
            ];

            // Hapus foreign key constraint terlebih dahulu sebelum menghapus kolomnya
            if (Schema::hasColumn('pesanans', 'produk_id')) {
                $table->dropForeign(['produk_id']);
            }
            if (Schema::hasColumn('pesanans', 'promo_id')) {
                $table->dropForeign(['promo_id']);
            }

            // Loop untuk menghapus semua kolom yang tidak perlu
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('pesanans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kode untuk mengembalikan kolom jika diperlukan (opsional)
        Schema::table('pesanans', function (Blueprint $table) {
            $table->foreignId('produk_id')->nullable();
            $table->foreignId('promo_id')->nullable();
            $table->integer('jumlah')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->decimal('diskon', 15, 2)->nullable();
            $table->decimal('ongkos_kirim', 15, 2)->nullable();
            $table->decimal('pajak', 15, 2)->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('metode_pengiriman')->nullable();
        });
    }
};
