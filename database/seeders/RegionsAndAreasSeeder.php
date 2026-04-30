<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsAndAreasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'DKI JABAR PRIME FLEET' => ['DKI1', 'DKI2', 'JABAR'],
            'JATKALBAL' => ['JATIM', 'KALIMANTAN', 'BALI'],
            'SUMATERA' => ['SUMATERA1', 'SUMATERA2'],
            'HEAD OFFICE' => ['HEAD OFFICE'],
        ];

        foreach ($data as $regionName => $areas) {
            $region = Region::firstOrCreate(['nama_region' => $regionName], [
                'nama_rbh' => 'HENDRIK SETIAWAN'
            ]);

            foreach ($areas as $areaName) {
                Area::firstOrCreate([
                    'region_id' => $region->id,
                    'nama_area' => $areaName,
                    'nama_abh' => 'BAMBANG SUTRISNO'
                ]);
            }
        }
    }
}
