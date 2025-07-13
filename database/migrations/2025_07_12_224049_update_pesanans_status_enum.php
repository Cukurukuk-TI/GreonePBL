<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum status untuk menyesuaikan dengan yang digunakan di controller
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('unpaid', 'pending', 'paid', 'proses', 'dikirim', 'complete', 'cancelled', 'expired') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        // Kembalikan ke enum status lama
        DB::statement("ALTER TABLE pesanans MODIFY COLUMN status ENUM('unpaid', 'pending', 'paid', 'proses', 'dikirim', 'selesai', 'dibatalkan', 'expired') DEFAULT 'unpaid'");
    }
};