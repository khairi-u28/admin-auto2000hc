<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sesi_peserta', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sesi_id');
            $table->foreign('sesi_id')->references('id')->on('sesi')->onDelete('cascade');
            $table->foreignUuid('employee_id')->nullable()->references('id')->on('employees');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('registered');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_peserta');
    }
};
