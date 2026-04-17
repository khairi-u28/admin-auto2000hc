<?php

namespace Database\Seeders;

use App\Models\DevelopmentProgram;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class DevelopmentProgramSeeder extends Seeder
{
    public function run(): void
    {
        $emp = fn (string $nrp) => Employee::where('nrp', $nrp)->value('id');

        $programs = [
            // ── VANESSA ANDINI (NRP 81485) ────────────────────────────────
            // MDP SH 2025 – failed
            [
                'employee_id'   => $emp('81485'),
                'program_name'  => 'MDP SH',
                'status'        => 'failed',
                'period_year'   => 2025,
                'kpi_mid_year'  => 3.20,
                'kpi_full_year' => 3.05,
                'pk_score'      => 'B',
            ],

            // ── VIOLINA SETIAWAN (NRP 75773) ──────────────────────────────
            // MDP FAH 2024 – pool of cadre
            [
                'employee_id'   => $emp('75773'),
                'program_name'  => 'MDP FAH',
                'status'        => 'pool_of_cadre',
                'period_year'   => 2024,
                'kpi_mid_year'  => 3.50,
                'kpi_full_year' => 3.45,
                'pk_score'      => 'B',
            ],

            // ── YEREMIA STEVEN (NRP 80197) ────────────────────────────────
            // ASDP 2024 – on going
            [
                'employee_id'   => $emp('80197'),
                'program_name'  => 'ASDP',
                'status'        => 'on_going',
                'period_year'   => 2024,
                'kpi_mid_year'  => 3.75,
                'kpi_full_year' => null,
                'pk_score'      => null,
            ],

            // ── NURUL HIDAYAH (NRP 82234) ─────────────────────────────────
            // MDP FAH 2025 – on going
            [
                'employee_id'   => $emp('82234'),
                'program_name'  => 'MDP FAH',
                'status'        => 'on_going',
                'period_year'   => 2025,
                'kpi_mid_year'  => null,
                'kpi_full_year' => null,
                'pk_score'      => null,
            ],

            // ── DADI SUPRIYADI (NRP 6018) ─────────────────────────────────
            // MDP BC 2022 – promoted
            [
                'employee_id'   => $emp('6018'),
                'program_name'  => 'MDP BC',
                'status'        => 'promoted',
                'period_year'   => 2022,
                'kpi_mid_year'  => 4.00,
                'kpi_full_year' => 3.90,
                'pk_score'      => 'A',
            ],

            // ── WILLIAM HARIANTO (NRP 10626) ──────────────────────────────
            // MDP BC 2023 – pool of cadre
            [
                'employee_id'   => $emp('10626'),
                'program_name'  => 'MDP BC',
                'status'        => 'pool_of_cadre',
                'period_year'   => 2023,
                'kpi_mid_year'  => 3.60,
                'kpi_full_year' => 3.55,
                'pk_score'      => 'B',
            ],

            // ── RINA FITRIANA (NRP 66789) ─────────────────────────────────
            // MDP SH 2021 – promoted
            [
                'employee_id'   => $emp('66789'),
                'program_name'  => 'MDP SH',
                'status'        => 'promoted',
                'period_year'   => 2021,
                'kpi_mid_year'  => 3.85,
                'kpi_full_year' => 3.90,
                'pk_score'      => 'A',
            ],
        ];

        foreach ($programs as $program) {
            DevelopmentProgram::firstOrCreate(
                [
                    'employee_id'  => $program['employee_id'],
                    'program_name' => $program['program_name'],
                    'period_year'  => $program['period_year'],
                ],
                $program
            );
        }
    }
}
