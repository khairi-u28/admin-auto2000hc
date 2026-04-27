<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_region')->unique();
            $table->string('nama_rbh')->nullable();
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('nama_area');
            $table->string('nama_abh')->nullable();
            $table->timestamps();
            $table->unique(['region_id', 'nama_area']);
        });

        $now = now();

        $branches = DB::table('branches')
            ->select('region', 'area')
            ->whereNotNull('region')
            ->whereNotNull('area')
            ->distinct()
            ->get();

        $regionIds = [];
        $areaIds = [];

        foreach ($branches->pluck('region')->unique() as $regionName) {
            $regionId = (string) Str::uuid();

            DB::table('regions')->insert([
                'id' => $regionId,
                'nama_region' => $regionName,
                'nama_rbh' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $regionIds[$regionName] = $regionId;
        }

        foreach ($branches as $branch) {
            $areaKey = $branch->region.'|'.$branch->area;

            if (isset($areaIds[$areaKey])) {
                continue;
            }

            $areaId = (string) Str::uuid();

            DB::table('areas')->insert([
                'id' => $areaId,
                'region_id' => $regionIds[$branch->region],
                'nama_area' => $branch->area,
                'nama_abh' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $areaIds[$areaKey] = $areaId;
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('regions');
    }
};
