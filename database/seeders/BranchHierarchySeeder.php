<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchHierarchySeeder extends Seeder
{
    public function run(): void
    {
        // Explicit mappings per region/area from BA_Nama_Cabang (partial list)
        $mappings = [];

        // DKI,JABAR,PRIME FLEET - DKI1
        $mappings = array_merge($mappings, array_fill_keys([
            'T007','T008','T009','T011','T012','T015','T050','T052','T053','T055','T058','T059','T061','T062','T063','T064','T065','T066','T067','T068','T069','T156','T158','T160','T161','T162','T163','T164','T165','T168','T207','T209','T210','T211','T212','T213','T214','T215','T216'
        ], ['region' => 'DKI,JABAR,PRIME FLEET','area' => 'DKI1']));

        // DKI,JABAR,PRIME FLEET - DKI2
        $mappings = array_merge($mappings, array_fill_keys([
            'T002','T004','T005','T010','T018','T100','T102','T103','T105','T106','T110','T111','T112','T113','T114','T120','T151','T152','T153','T154','T155','T205','T252','T257','T258','T259','T260','T261','T262','T263','T264','T265','T266','T267','T268','T269','T270','T271','T272','T273','T274','T278','T279','T281','T282','T283','T284'
        ], ['region' => 'DKI,JABAR,PRIME FLEET','area' => 'DKI2']));

        // DKI,JABAR,PRIME FLEET - JABAR
        $mappings = array_merge($mappings, array_fill_keys([
            'T250','T251','T252','T253','T254','T255','T261','T262','T263','T264','T265','T266','T267'
        ], ['region' => 'DKI,JABAR,PRIME FLEET','area' => 'JABAR']));

        // DKI,JABAR,PRIME FLEET - FLEET
        $mappings['T168'] = ['region' => 'DKI,JABAR,PRIME FLEET','area' => 'FLEET'];

        // JATKALBAL - JATIM
        $mappings = array_merge($mappings, array_fill_keys([
            'T450','T451','T452','T453','T454','T455','T456','T457','T458','T459','T460','T461'
        ], ['region' => 'JATKALBAL','area' => 'JATIM']));

        // JATKALBAL - KALIMANTAN
        $mappings = array_merge($mappings, array_fill_keys([
            'T700','T701','T702','T703','T704','T705','T706','T707','T708','T709','T710','T711'
        ], ['region' => 'JATKALBAL','area' => 'KALIMANTAN']));

        // JATKALBAL - BALI (example range)
        $mappings = array_merge($mappings, array_fill_keys([
            'T540','T541','T542','T543','T544','T545','T546','T547','T548'
        ], ['region' => 'JATKALBAL','area' => 'BALI']));

        // SUMATERA - SUMBAGUT
        $mappings = array_merge($mappings, array_fill_keys([
            'T800','T801','T802','T803','T804','T805','T806','T807','T808','T809'
        ], ['region' => 'SUMATERA','area' => 'SUMBAGUT']));

        // SUMATERA - SUMBAGSEL
        $mappings = array_merge($mappings, array_fill_keys([
            'T900','T901','T902','T903','T904','T905','T906','T907','T908','T909'
        ], ['region' => 'SUMATERA','area' => 'SUMBAGSEL']));

        // HEAD OFFICE
        $mappings['T000'] = ['region' => 'HEAD OFFICE','area' => 'HEAD OFFICE'];

        foreach ($mappings as $code => $meta) {
            $branch = Branch::where('code', $code)->first();
            if ($branch) {
                $branch->region = $meta['region'];
                $branch->area = $meta['area'];
                $branch->save();
            }
        }
    }
}
