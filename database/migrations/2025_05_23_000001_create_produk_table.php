<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->integer('harga');
            $table->string('gambar_url');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('produk');
    }
};