<?php

namespace Database\Seeders;

use App\Models\CompetencyTrack;
use Illuminate\Database\Seeder;

class CompetencyTrackSeeder extends Seeder
{
    public function run(): void
    {
        $tracks = [
            // ── SALES ─────────────────────────────────────────────────────
            ['code' => 'OBP',                    'name' => 'Orientation & Basic Product',          'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 1, 'description' => 'Dasar produk dan orientasi wiraniaga baru'],
            ['code' => 'PSST',                   'name' => 'Product & Sales Skill Training',       'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 2, 'description' => 'Keterampilan penjualan dan pengetahuan produk'],
            ['code' => 'ASAP',                   'name' => 'Advanced Sales Approach Program',      'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 2, 'description' => 'Pendekatan penjualan tingkat lanjut'],
            ['code' => 'ESAP',                   'name' => 'Expert Sales Approach Program',        'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 3, 'description' => 'Program penjualan tingkat ahli'],
            ['code' => 'ASP',                    'name' => 'Advanced Sales Program',               'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 2, 'description' => 'Program penjualan lanjutan'],
            ['code' => 'ESP',                    'name' => 'Expert Sales Program',                 'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 3, 'description' => 'Program penjualan tingkat expert'],
            ['code' => 'BOMT',                   'name' => 'Basic Outlet Management Training',     'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 2, 'description' => 'Pelatihan manajemen outlet dasar untuk supervisor'],
            ['code' => 'BSMT',                   'name' => 'Basic Sales Management Training',     'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 3, 'description' => 'Pelatihan manajemen penjualan dasar'],
            ['code' => 'DIGITAL_MARKETING',      'name' => 'Digital Marketing',                   'department' => 'Sales',      'category' => 'Sales',           'level_sequence' => 2, 'description' => 'Kompetensi pemasaran digital untuk wiraniaga'],

            // ── FOREMAN GR ────────────────────────────────────────────────
            ['code' => 'FO_LV1',                 'name' => 'Foreman Level 1',                     'department' => 'Aftersales',  'category' => 'Foreman',         'level_sequence' => 1, 'description' => 'Kompetensi dasar foreman bengkel GR'],
            ['code' => 'FO_LV2',                 'name' => 'Foreman Level 2',                     'department' => 'Aftersales',  'category' => 'Foreman',         'level_sequence' => 2, 'description' => 'Kompetensi menengah foreman bengkel GR'],
            ['code' => 'FO_LV3',                 'name' => 'Foreman Level 3 / New Foreman',       'department' => 'Aftersales',  'category' => 'Foreman',         'level_sequence' => 3, 'description' => 'Sertifikasi foreman dan calon foreman baru'],

            // ── PARTMAN ───────────────────────────────────────────────────
            ['code' => 'PART_LV1',               'name' => 'Partman Level 1',                     'department' => 'Aftersales',  'category' => 'Partman',         'level_sequence' => 1, 'description' => 'Kompetensi dasar partman'],
            ['code' => 'PART_LV2',               'name' => 'Partman Level 2',                     'department' => 'Aftersales',  'category' => 'Partman',         'level_sequence' => 2, 'description' => 'Kompetensi menengah partman'],
            ['code' => 'PART_LV3',               'name' => 'Partman Level 3 / New Partman',       'department' => 'Aftersales',  'category' => 'Partman',         'level_sequence' => 3, 'description' => 'Sertifikasi partman baru'],

            // ── SERVICE ADVISOR ───────────────────────────────────────────
            ['code' => 'SA_LV1',                 'name' => 'Service Advisor Level 1',             'department' => 'Aftersales',  'category' => 'Service Advisor', 'level_sequence' => 1, 'description' => 'Kompetensi dasar service advisor'],
            ['code' => 'SA_LV2',                 'name' => 'Service Advisor Level 2',             'department' => 'Aftersales',  'category' => 'Service Advisor', 'level_sequence' => 2, 'description' => 'Kompetensi menengah service advisor'],
            ['code' => 'SA_LV3',                 'name' => 'Service Advisor Level 3',             'department' => 'Aftersales',  'category' => 'Service Advisor', 'level_sequence' => 3, 'description' => 'Sertifikasi service advisor'],

            // ── CRC ───────────────────────────────────────────────────────
            ['code' => 'CR_LV1',                 'name' => 'CRC Level 1',                         'department' => 'Aftersales',  'category' => 'CRC',             'level_sequence' => 1, 'description' => 'Kompetensi dasar Customer Relation Coordinator'],
            ['code' => 'CR_LV2',                 'name' => 'CRC Level 2',                         'department' => 'Aftersales',  'category' => 'CRC',             'level_sequence' => 2, 'description' => 'Kompetensi menengah CRC'],
            ['code' => 'CR_LV3',                 'name' => 'CRC Level 3',                         'department' => 'Aftersales',  'category' => 'CRC',             'level_sequence' => 3, 'description' => 'Sertifikasi CRC'],

            // ── MECHANIC GR – TECHNICAL CERTIFICATION ─────────────────────
            ['code' => 'MECH_TT_TRAIN_CERT',     'name' => 'Mechanic TT Training & Certification','department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 1, 'description' => 'Toyota Technical Training dasar'],
            ['code' => 'MECH_PT_TRAIN_CERT',     'name' => 'Mechanic PT Training & Certification','department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 2, 'description' => 'Periodic Maintenance training & sertifikasi'],
            ['code' => 'MECH_ENGINE_TRAIN_CERT', 'name' => 'Engine Training & Certification',     'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 2, 'description' => 'Pelatihan mesin dan sertifikasi'],
            ['code' => 'MECH_ELECTRICAL_TRAIN_CERT','name' => 'Electrical Training & Certification','department' => 'Aftersales','category' => 'Mechanic GR',    'level_sequence' => 3, 'description' => 'Pelatihan kelistrikan kendaraan dan sertifikasi'],
            ['code' => 'MECH_CHASSIS_TRAIN_CERT','name' => 'Chassis Training & Certification',    'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 3, 'description' => 'Pelatihan chasis dan sertifikasi'],
            ['code' => 'MECH_DMT_TRAIN_CERT',    'name' => 'DMT Training & Certification',        'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 3, 'description' => 'Diagnostic & Maintenance Technology training'],
            ['code' => 'MECH_LD_TRAIN_CERT',     'name' => 'LD Training & Certification',         'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 3, 'description' => 'Line Diagnostics training & sertifikasi'],

            // ── MECHANIC GR – TEAM GP GRADE ───────────────────────────────
            ['code' => 'TEAM_GP_G4',             'name' => 'Team GP Grade 4',                     'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 1, 'description' => 'Team GP masuk grade G4 (entry level)'],
            ['code' => 'TEAM_GP_G3',             'name' => 'Team GP Grade 3',                     'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 2, 'description' => 'Team GP grade G3 (menengah)'],
            ['code' => 'TEAM_GP_G2',             'name' => 'Team GP Grade 2',                     'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 3, 'description' => 'Team GP grade G2 (mahir)'],
            ['code' => 'TEAM_GP_G1',             'name' => 'Team GP Grade 1',                     'department' => 'Aftersales',  'category' => 'Mechanic GR',     'level_sequence' => 4, 'description' => 'Team GP grade G1 (expert/master)'],

            // ── AFTERSALES BP – BODY ──────────────────────────────────────
            ['code' => 'BODY_STEP1',             'name' => 'Body Repair Step 1',                  'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 1, 'description' => 'Dasar perbaikan bodi kendaraan'],
            ['code' => 'BODY_STEP2',             'name' => 'Body Repair Step 2',                  'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 2, 'description' => 'Perbaikan bodi tingkat pemahiran'],
            ['code' => 'BODY_STEP3',             'name' => 'Body Repair Step 3',                  'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 3, 'description' => 'Sertifikasi perbaikan bodi kendaraan'],

            // ── AFTERSALES BP – PAINT ─────────────────────────────────────
            ['code' => 'PAINT_STEP1',            'name' => 'Paint Step 1',                        'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 1, 'description' => 'Dasar pengecatan kendaraan'],
            ['code' => 'PAINT_STEP2',            'name' => 'Paint Step 2',                        'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 2, 'description' => 'Pengecatan tingkat pemahiran'],
            ['code' => 'PAINT_STEP3',            'name' => 'Paint Step 3',                        'department' => 'Aftersales',  'category' => 'Mechanic BP',     'level_sequence' => 3, 'description' => 'Sertifikasi pengecatan kendaraan'],

            // ── AFTERSALES THS ────────────────────────────────────────────
            ['code' => 'THS_NEW',                'name' => 'THS New Member',                      'department' => 'Aftersales',  'category' => 'Mechanic THS',    'level_sequence' => 1, 'description' => 'Onboarding mekanik Toyota Home Service baru'],
            ['code' => 'THS_AMBASSADOR',         'name' => 'THS Ambassador',                      'department' => 'Aftersales',  'category' => 'Mechanic THS',    'level_sequence' => 2, 'description' => 'THS ambassador – pelayanan prima di lapangan'],
            ['code' => 'THS_MARKET_PENETRATION', 'name' => 'THS Market Penetration',              'department' => 'Aftersales',  'category' => 'Mechanic THS',    'level_sequence' => 3, 'description' => 'Strategi pelayanan dan penetrasi pasar THS'],

            // ── SERVICE HEAD ──────────────────────────────────────────────
            ['code' => 'TSMT_LV1',               'name' => 'Toyota Service Management Training Level 1', 'department' => 'Aftersales', 'category' => 'Service Head', 'level_sequence' => 1, 'description' => 'TSMT dasar untuk service head'],
            ['code' => 'TSMT_LV2',               'name' => 'Toyota Service Management Training Level 2', 'department' => 'Aftersales', 'category' => 'Service Head', 'level_sequence' => 2, 'description' => 'TSMT lanjutan untuk service head'],
            ['code' => 'TBS',                    'name' => 'Toyota Business School',               'department' => 'Aftersales',  'category' => 'Service Head',    'level_sequence' => 3, 'description' => 'Program Toyota Business School untuk pemimpin bengkel'],

            // ── PD ────────────────────────────────────────────────────────
            ['code' => 'HAV_ASSESSMENT',         'name' => 'HAV Assessment',                      'department' => 'PD',          'category' => 'PD',              'level_sequence' => 1, 'description' => 'Human Asset Value assessment (skor 1–11)'],
            ['code' => 'DEV_PROGRAM',            'name' => 'Development Program Enrollment',      'department' => 'PD',          'category' => 'PD',              'level_sequence' => 2, 'description' => 'MDP / ASDP / program pengembangan bakat'],
            ['code' => 'KPI_REVIEW',             'name' => 'KPI Annual Review',                   'department' => 'PD',          'category' => 'PD',              'level_sequence' => 3, 'description' => 'Review KPI mid-year dan full-year'],
        ];

        foreach ($tracks as $track) {
            CompetencyTrack::firstOrCreate(['code' => $track['code']], $track);
        }
    }
}
