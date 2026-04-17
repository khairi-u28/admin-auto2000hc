<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('department', ['Sales', 'Aftersales', 'PD', 'HC', 'GS', 'Other']);
            $table->string('category')->comment('e.g. Foreman, Mechanic GR, Sales');
            $table->tinyInteger('level_sequence')->unsigned()->comment('1=entry, 2=intermediate, 3=expert');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_tracks');
    }
};
