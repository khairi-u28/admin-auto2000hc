<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->renameColumn('evaluation_notes', 'description');
            $table->longText('evaluation')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->renameColumn('description', 'evaluation_notes');
            $table->dropColumn('evaluation');
        });
    }
};
