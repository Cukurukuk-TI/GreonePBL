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
            // Hapus foreign key constraint terlebih dahulu
            // Nama constraint biasanya: nama_tabel_nama_kolom_foreign
            $table->dropForeign(['produk_id']);

            // Hapus kolom yang tidak diperlukan lagi
            $table->dropColumn(['produk_id', 'jumlah', 'harga_satuan', 'subtotal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Jika perlu rollback, tambahkan kembali kolomnya
            $table->foreignId('produk_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
        });
    }
};
