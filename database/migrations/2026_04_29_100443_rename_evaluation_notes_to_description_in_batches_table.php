<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('batches')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'evaluation_notes') && ! Schema::hasColumn('batches', 'description')) {
                $table->renameColumn('evaluation_notes', 'description');
            }

            if (! Schema::hasColumn('batches', 'evaluation')) {
                $table->longText('evaluation')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('batches')) {
            return;
        }

        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'description') && ! Schema::hasColumn('batches', 'evaluation_notes')) {
                $table->renameColumn('description', 'evaluation_notes');
            }

            if (Schema::hasColumn('batches', 'evaluation')) {
                $table->dropColumn('evaluation');
            }
        });
    }
};
