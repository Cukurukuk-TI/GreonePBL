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
        Schema::table('alamats', function (Blueprint $table) {
            // Tambahkan dua kolom ini setelah kolom 'detail_alamat'
            $table->double('latitude')->nullable()->after('detail_alamat');
            $table->double('longitude')->nullable()->after('detail_alamat');
        });
    }

    public function down(): void
    {
        Schema::table('alamats', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
