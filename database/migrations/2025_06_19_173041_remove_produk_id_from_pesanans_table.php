<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menghapus kolom.
     */
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Langkah 1: Hapus foreign key constraint terlebih dahulu
            // Nama constraint biasanya: nama_tabel_nama_kolom_foreign
            $table->dropForeign(['produk_id']);

            // Langkah 2: Hapus kolomnya
            $table->dropColumn('produk_id');
        });
    }

    /**
     * Balikkan migrasi (jika diperlukan).
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Tambahkan kembali kolomnya jika migrasi di-rollback
            $table->foreignId('produk_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
        });
    }
};
