<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // hubungan ke user
            $table->enum('label', ['rumah', 'kantor', 'other']); // enum dengan 3 opsi
            $table->string('nama_penerima');
            $table->string('nomor_hp');
            $table->string('provinsi');
            $table->string('kota');
            $table->text('detail_alamat');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamats');
    }
};
