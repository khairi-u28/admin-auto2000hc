<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\Employee;
use App\Models\TrainingRecord;
use Illuminate\Database\Seeder;

class TrainingRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve helpers
        $emp   = fn (string $nrp)  => Employee::where('nrp', $nrp)->value('id');
        $comp = fn (string $code) => Competency::where('code', $code)->value('id');

        $records = [];

        // ── DADI SUPRIYADI (NRP 6018) ─────────────────────────────────────
        foreach (['COMP-SC-001', 'COMP-SC-002', 'COMP-GEN-001'] as $i => $code) {
            $records[] = [
                'employee_id'          => $emp('6018'),
                'competency_id'  => $comp($code),
                'level_achieved'       => 3,
                'completion_date'      => '202' . ($i + 0) . '-0' . ($i + 1) . '-15',
                'source'               => 'import',
            ];
        }

        // ── JUNAIDI (NRP 6027) ────────────────────────────────────────────
        $records[] = ['employee_id' => $emp('6027'), 'competency_id' => $comp('COMP-SC-001'),  'level_achieved' => 3, 'completion_date' => '2010-03-20', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6027'), 'competency_id' => $comp('COMP-SC-002'), 'level_achieved' => 3, 'completion_date' => '2012-06-10', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6027'), 'competency_id' => $comp('COMP-GEN-001'), 'level_achieved' => 2, 'completion_date' => '2018-11-05', 'source' => 'import'];

        // ── SUBAGYO (NRP 5666) – Service Head T009 ───────────────────────
        $subagyoCerts = [
            ['COMP-MCH-001', '1995-06-01', '1995-12-31', 'CERT-SH-5666-MCH1'],
            ['COMP-MCH-002', '1996-06-01', '1996-12-31', 'CERT-SH-5666-MCH2'],
            ['COMP-MCH-003', '1994-03-01', null,         'CERT-MCH3-5666'],
        ];
        foreach ($subagyoCerts as [$code, $date, $expiry, $certNo]) {
            $records[] = [
                'employee_id'          => $emp('5666'),
                'competency_id'  => $comp($code),
                'level_achieved'       => 3,
                'completion_date'      => $date,
                'certification_number' => $certNo,
                'certification_expiry' => $expiry,
                'source'               => 'import',
            ];
        }

        // ── VANESSA ANDINI (NRP 81485) – HC Analyst ───────────────────────
        $records[] = ['employee_id' => $emp('81485'), 'competency_id' => $comp('COMP-HC-001'), 'level_achieved' => 3, 'completion_date' => '2024-04-01', 'notes' => 'Selesai HC Analyst Fundamental', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('81485'), 'competency_id' => $comp('COMP-PD-001'),    'level_achieved' => 2, 'completion_date' => '2025-01-01', 'notes' => 'On Progress People Development Core', 'source' => 'import'];

        foreach ($records as $record) {
            // Check if both employee_id and competency_id exist before creating
            if ($record['employee_id'] && $record['competency_id']) {
                TrainingRecord::firstOrCreate(
                    [
                        'employee_id'         => $record['employee_id'],
                        'competency_id' => $record['competency_id'],
                    ],
                    array_merge([
                        'level_achieved'       => 0,
                        'source'               => 'import',
                        'certification_number' => null,
                        'certification_expiry' => null,
                        'completion_date'      => null,
                        'notes'                => null,
                        'recorded_by'          => null,
                    ], $record)
                );
            }
        }
    }
}
