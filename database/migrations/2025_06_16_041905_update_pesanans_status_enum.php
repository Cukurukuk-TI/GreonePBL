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
            // Ubah kolom status untuk menambahkan 'dikirim' dan 'cancelled'
            $table->enum('status', ['pending', 'proses', 'dikirim', 'complete', 'cancelled'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            // Kembalikan ke enum original
            $table->enum('status', ['pending', 'proses', 'complete'])
                  ->default('pending')
                  ->change();
        });
    }
};