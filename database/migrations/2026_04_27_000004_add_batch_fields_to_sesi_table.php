<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sesi', function (Blueprint $table) {
            $table->string('kode_batch')->nullable()->after('id');
            $table->foreignUuid('curriculum_id')->nullable()->after('description')->constrained('curricula')->nullOnDelete();
            $table->foreignUuid('pic_employee_id')->nullable()->after('curriculum_id')->constrained('employees')->nullOnDelete();
            $table->enum('status', ['aktif', 'non_aktif', 'selesai', 'dibatalkan'])->default('non_aktif')->after('capacity');
        });

        DB::table('sesi')
            ->whereNull('kode_batch')
            ->update([
                'kode_batch' => DB::raw("CONCAT('BATCH-', UPPER(LEFT(REPLACE(id, '-', ''), 8)))"),
                'status' => 'non_aktif',
            ]);
    }

    public function down(): void
    {
        Schema::table('sesi', function (Blueprint $table) {
            $table->dropForeign(['curriculum_id']);
            $table->dropForeign(['pic_employee_id']);
            $table->dropColumn(['kode_batch', 'curriculum_id', 'pic_employee_id', 'status']);
        });
    }
};
