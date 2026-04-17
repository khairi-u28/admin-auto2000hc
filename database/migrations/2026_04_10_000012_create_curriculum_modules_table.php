<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculum_modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('curriculum_id')->constrained('curricula')->cascadeOnDelete();
            $table->foreignUuid('module_id')->constrained('modules')->cascadeOnDelete();
            $table->tinyInteger('order_index')->unsigned()->default(0);
            $table->boolean('is_mandatory')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_modules');
    }
};
