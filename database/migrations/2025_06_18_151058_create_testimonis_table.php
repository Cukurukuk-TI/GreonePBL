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
        Schema::create('testimonis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('rating')->unsigned()->default(1); // Rating 1-5 bintang
            $table->text('komentar');
            $table->string('foto_testimoni')->nullable(); // Foto opsional
            $table->string('status', 20)->default('pending'); // 'pending', 'approved', 'rejected'
            $table->index('status'); // Menambahkan index untuk performa query\
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonis');
    }
};
