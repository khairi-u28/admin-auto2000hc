<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignUuid('competency_track_id')->constrained('competency_tracks');
            $table->tinyInteger('level_achieved')->unsigned()->default(0)
                  ->comment('0=belum, 1=lv1, 2=lv2, 3=lv3/certified');
            $table->date('completion_date')->nullable();
            $table->string('certification_number')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->enum('source', ['import', 'manual', 'system'])->default('import');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_records');
    }
};
