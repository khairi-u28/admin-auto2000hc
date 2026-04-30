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
        if (! Schema::hasTable('batches') || Schema::hasColumn('batches', 'area_penyelenggara')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            $table->string('area_penyelenggara')->nullable()->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('batches') || ! Schema::hasColumn('batches', 'area_penyelenggara')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            $table->dropColumn('area_penyelenggara');
        });
    }
};
