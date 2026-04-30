<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('batches') || ! Schema::hasTable('batch_participants')) {
            return;
        }

        // Add 'pendaftaran' and 'berjalan' to batches status
        DB::statement("ALTER TABLE batches MODIFY COLUMN status ENUM('draft', 'open', 'berlangsung', 'selesai', 'dibatalkan', 'pendaftaran', 'berjalan') DEFAULT 'draft'");
        
        // Update batch_participants status to be consistent with code expectations if any
        DB::statement("ALTER TABLE batch_participants MODIFY COLUMN status ENUM('menunggu_undangan', 'diundang', 'terdaftar', 'sedang_berjalan', 'selesai', 'terlambat', 'lulus', 'tidak_lulus', 'berlangsung') DEFAULT 'menunggu_undangan'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('batches') || ! Schema::hasTable('batch_participants')) {
            return;
        }

        DB::statement("ALTER TABLE batches MODIFY COLUMN status ENUM('draft', 'open', 'berlangsung', 'selesai', 'dibatalkan') DEFAULT 'draft'");
        DB::statement("ALTER TABLE batch_participants MODIFY COLUMN status ENUM('menunggu_undangan', 'diundang', 'terdaftar', 'sedang_berjalan', 'selesai', 'terlambat', 'lulus', 'tidak_lulus') DEFAULT 'menunggu_undangan'");
    }
};
