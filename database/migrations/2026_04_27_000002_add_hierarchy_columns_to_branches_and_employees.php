<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->foreignUuid('region_id')->nullable()->after('id')->constrained('regions')->nullOnDelete();
            $table->foreignUuid('area_id')->nullable()->after('region_id')->constrained('areas')->nullOnDelete();
            $table->string('kode_cabang')->nullable()->after('code');
            $table->string('nama')->nullable()->after('name');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('nama_lengkap')->nullable()->after('full_name');
            $table->string('pos')->nullable()->after('position_name');
            $table->string('masa_bakti')->nullable()->after('entry_date');
        });

        $areas = DB::table('areas')
            ->join('regions', 'regions.id', '=', 'areas.region_id')
            ->select('areas.id as area_id', 'regions.id as region_id', 'areas.nama_area', 'regions.nama_region')
            ->get()
            ->keyBy(fn ($row) => $row->nama_region.'|'.$row->nama_area);

        $branches = DB::table('branches')->select('id', 'region', 'area', 'code', 'name')->get();

        foreach ($branches as $branch) {
            $match = $areas->get($branch->region.'|'.$branch->area);

            DB::table('branches')
                ->where('id', $branch->id)
                ->update([
                    'region_id' => $match?->region_id,
                    'area_id' => $match?->area_id,
                    'kode_cabang' => $branch->code,
                    'nama' => $branch->name,
                ]);
        }

        DB::table('employees')
            ->whereNull('nama_lengkap')
            ->update([
                'nama_lengkap' => DB::raw('full_name'),
                'pos' => DB::raw('employee_type'),
            ]);
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'pos', 'masa_bakti']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['region_id', 'area_id', 'kode_cabang', 'nama']);
        });
    }
};
