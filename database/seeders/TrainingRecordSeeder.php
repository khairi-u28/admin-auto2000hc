<?php

namespace Database\Seeders;

use App\Models\CompetencyTrack;
use App\Models\Employee;
use App\Models\TrainingRecord;
use Illuminate\Database\Seeder;

class TrainingRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve helpers
        $emp   = fn (string $nrp)  => Employee::where('nrp', $nrp)->value('id');
        $track = fn (string $code) => CompetencyTrack::where('code', $code)->value('id');

        $records = [];

        // ── DADI SUPRIYADI (NRP 6018) ─────────────────────────────────────
        // Sales tracks: OBP, PSST, ASAP, ESAP all lulus (level 3 / certified)
        foreach (['OBP', 'PSST', 'ASAP', 'ESAP'] as $i => $code) {
            $records[] = [
                'employee_id'          => $emp('6018'),
                'competency_track_id'  => $track($code),
                'level_achieved'       => 3,
                'completion_date'      => '202' . ($i + 0) . '-0' . ($i + 1) . '-15',
                'source'               => 'import',
            ];
        }

        // ── JUNAIDI (NRP 6027) ────────────────────────────────────────────
        // OBP and PSST certified; ASAP in progress (level 2)
        $records[] = ['employee_id' => $emp('6027'), 'competency_track_id' => $track('OBP'),  'level_achieved' => 3, 'completion_date' => '2010-03-20', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6027'), 'competency_track_id' => $track('PSST'), 'level_achieved' => 3, 'completion_date' => '2012-06-10', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6027'), 'competency_track_id' => $track('ASAP'), 'level_achieved' => 2, 'completion_date' => '2018-11-05', 'source' => 'import'];

        // ── WILLIAM HARIANTO (NRP 10626) ──────────────────────────────────
        // ASP, ESP, BOMT all lulus (level 3)
        foreach (['OBP', 'PSST', 'ASP', 'ESP', 'BOMT'] as $i => $code) {
            $records[] = [
                'employee_id'          => $emp('10626'),
                'competency_track_id'  => $track($code),
                'level_achieved'       => in_array($code, ['OBP', 'PSST']) ? 3 : 3,
                'completion_date'      => '20' . (12 + $i) . '-0' . ($i + 1) . '-22',
                'source'               => 'import',
            ];
        }

        // ── SUBAGYO (NRP 5666) – Service Head T009 ───────────────────────
        // TSMT_LV1 (1995), TSMT_LV2 (1996), TT+PT+Engine+Electrical+Chassis completed
        $subagyoCerts = [
            ['TSMT_LV1',               '1995-06-01', '1995-12-31', 'CERT-SH-5666-TSMT1'],
            ['TSMT_LV2',               '1996-06-01', '1996-12-31', 'CERT-SH-5666-TSMT2'],
            ['MECH_TT_TRAIN_CERT',     '1994-03-01', null,         'CERT-TT-5666'],
            ['MECH_PT_TRAIN_CERT',     '1994-09-01', null,         'CERT-PT-5666'],
            ['MECH_ENGINE_TRAIN_CERT', '1995-02-01', null,         'CERT-ENG-5666'],
            ['MECH_ELECTRICAL_TRAIN_CERT', '1997-05-01', null,     'CERT-ELEC-5666'],
            ['MECH_CHASSIS_TRAIN_CERT','1998-08-01', null,         'CERT-CHS-5666'],
        ];
        foreach ($subagyoCerts as [$code, $date, $expiry, $certNo]) {
            $records[] = [
                'employee_id'          => $emp('5666'),
                'competency_track_id'  => $track($code),
                'level_achieved'       => 3,
                'completion_date'      => $date,
                'certification_number' => $certNo,
                'certification_expiry' => $expiry,
                'source'               => 'import',
            ];
        }

        // ── MUHAMAD MULYADI (NRP 5971) – Service Head T156 ───────────────
        $records[] = ['employee_id' => $emp('5971'), 'competency_track_id' => $track('TSMT_LV1'), 'level_achieved' => 3, 'completion_date' => '1997-05-01', 'certification_number' => 'CERT-SH-5971-TSMT1', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('5971'), 'competency_track_id' => $track('TSMT_LV2'), 'level_achieved' => 3, 'completion_date' => '1999-08-01', 'certification_number' => 'CERT-SH-5971-TSMT2', 'source' => 'import'];
        foreach (['MECH_TT_TRAIN_CERT', 'MECH_PT_TRAIN_CERT', 'MECH_ENGINE_TRAIN_CERT', 'MECH_ELECTRICAL_TRAIN_CERT'] as $code) {
            $records[] = ['employee_id' => $emp('5971'), 'competency_track_id' => $track($code), 'level_achieved' => 3, 'completion_date' => '1996-01-01', 'source' => 'import'];
        }

        // ── KEN ERIC (NRP 29002) – Service Head T012 ─────────────────────
        $records[] = ['employee_id' => $emp('29002'), 'competency_track_id' => $track('TSMT_LV1'), 'level_achieved' => 3, 'completion_date' => '2005-03-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('29002'), 'competency_track_id' => $track('TSMT_LV2'), 'level_achieved' => 3, 'completion_date' => '2007-11-01', 'source' => 'import'];
        foreach (['MECH_TT_TRAIN_CERT', 'MECH_PT_TRAIN_CERT', 'MECH_ENGINE_TRAIN_CERT'] as $code) {
            $records[] = ['employee_id' => $emp('29002'), 'competency_track_id' => $track($code), 'level_achieved' => 3, 'completion_date' => '2004-06-01', 'source' => 'import'];
        }

        // ── AQSHAL WILDAN (NRP 83567) – Mechanic Trainee (all level 0) ───
        $mechGrTracks = ['MECH_TT_TRAIN_CERT', 'MECH_PT_TRAIN_CERT', 'MECH_ENGINE_TRAIN_CERT', 'MECH_ELECTRICAL_TRAIN_CERT', 'MECH_CHASSIS_TRAIN_CERT', 'MECH_DMT_TRAIN_CERT', 'MECH_LD_TRAIN_CERT', 'TEAM_GP_G4'];
        foreach ($mechGrTracks as $code) {
            $records[] = ['employee_id' => $emp('83567'), 'competency_track_id' => $track($code), 'level_achieved' => 0, 'notes' => 'Belum mengikuti pelatihan', 'source' => 'import'];
        }

        // ── JEHEZKIEL PRAMARTI (NRP 84185) – Mechanic Trainee ────────────
        foreach (['MECH_TT_TRAIN_CERT', 'MECH_PT_TRAIN_CERT', 'TEAM_GP_G4'] as $code) {
            $records[] = ['employee_id' => $emp('84185'), 'competency_track_id' => $track($code), 'level_achieved' => 0, 'notes' => 'Belum mengikuti pelatihan', 'source' => 'import'];
        }

        // ── VANESSA ANDINI (NRP 81485) – HC Analyst ───────────────────────
        $records[] = ['employee_id' => $emp('81485'), 'competency_track_id' => $track('HAV_ASSESSMENT'), 'level_achieved' => 3, 'completion_date' => '2024-04-01', 'notes' => 'HAV Score 8 – Strong Performers', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('81485'), 'competency_track_id' => $track('DEV_PROGRAM'),    'level_achieved' => 2, 'completion_date' => '2025-01-01', 'notes' => 'MDP SH 2025 – Status: Failed', 'source' => 'import'];

        // ── VIOLINA SETIAWAN (NRP 75773) – HC Analyst ─────────────────────
        $records[] = ['employee_id' => $emp('75773'), 'competency_track_id' => $track('HAV_ASSESSMENT'), 'level_achieved' => 2, 'completion_date' => '2023-04-01', 'notes' => 'HAV Score 6 – Candidate', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('75773'), 'competency_track_id' => $track('DEV_PROGRAM'),    'level_achieved' => 2, 'completion_date' => '2024-06-01', 'notes' => 'MDP FAH 2024 – Status: Pool of Cadre', 'source' => 'import'];

        // ── DEMO EMPLOYEES – plausible partial records ─────────────────────
        // BUDI SANTOSO (SC, T100) – OBP done, PSST partial
        $records[] = ['employee_id' => $emp('6098'), 'competency_track_id' => $track('OBP'),  'level_achieved' => 3, 'completion_date' => '2007-05-10', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6098'), 'competency_track_id' => $track('PSST'), 'level_achieved' => 2, 'completion_date' => '2010-09-20', 'source' => 'import'];

        // AHMAD RIFAI (SC, T102)
        $records[] = ['employee_id' => $emp('6112'), 'competency_track_id' => $track('OBP'),  'level_achieved' => 3, 'completion_date' => '2007-11-15', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('6112'), 'competency_track_id' => $track('PSST'), 'level_achieved' => 3, 'completion_date' => '2012-03-08', 'source' => 'import'];

        // DEWI KUSUMA (SA, T009) – SA_LV1 done, SA_LV2 partial
        $records[] = ['employee_id' => $emp('7321'), 'competency_track_id' => $track('SA_LV1'), 'level_achieved' => 3, 'completion_date' => '2010-08-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('7321'), 'competency_track_id' => $track('SA_LV2'), 'level_achieved' => 2, 'completion_date' => '2015-04-10', 'source' => 'import'];

        // RUDI HERMAWAN (SA, T012)
        $records[] = ['employee_id' => $emp('7456'), 'competency_track_id' => $track('SA_LV1'), 'level_achieved' => 3, 'completion_date' => '2011-02-14', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('7456'), 'competency_track_id' => $track('SA_LV2'), 'level_achieved' => 3, 'completion_date' => '2016-07-20', 'source' => 'import'];

        // AGUS PRIYONO (Mechanic 1, T002)
        $records[] = ['employee_id' => $emp('8234'), 'competency_track_id' => $track('MECH_TT_TRAIN_CERT'), 'level_achieved' => 3, 'completion_date' => '2013-10-01', 'certification_number' => 'CERT-TT-8234', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('8234'), 'competency_track_id' => $track('MECH_PT_TRAIN_CERT'), 'level_achieved' => 2, 'completion_date' => '2015-04-01', 'source' => 'import'];

        // DONI PRASETYO (Foreman, T002)
        $records[] = ['employee_id' => $emp('8789'), 'competency_track_id' => $track('FO_LV1'), 'level_achieved' => 3, 'completion_date' => '2010-06-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('8789'), 'competency_track_id' => $track('FO_LV2'), 'level_achieved' => 3, 'completion_date' => '2015-09-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('8789'), 'competency_track_id' => $track('FO_LV3'), 'level_achieved' => 2, 'completion_date' => '2020-11-01', 'source' => 'import'];

        // BAMBANG SUDARSONO (Foreman, T251 JABAR)
        $records[] = ['employee_id' => $emp('66123'), 'competency_track_id' => $track('FO_LV1'), 'level_achieved' => 3, 'completion_date' => '2009-03-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('66123'), 'competency_track_id' => $track('FO_LV2'), 'level_achieved' => 3, 'completion_date' => '2014-07-01', 'source' => 'import'];

        // SYAHRUL RAMADHAN (Mechanic Trainee BP, T004)
        $records[] = ['employee_id' => $emp('82001'), 'competency_track_id' => $track('BODY_STEP1'),  'level_achieved' => 0, 'notes' => 'Belum mengikuti pelatihan', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('82001'), 'competency_track_id' => $track('PAINT_STEP1'), 'level_achieved' => 0, 'notes' => 'Belum mengikuti pelatihan', 'source' => 'import'];

        // YEREMIA STEVEN (Account Supervisor, KALIMANTAN)
        $records[] = ['employee_id' => $emp('80197'), 'competency_track_id' => $track('OBP'),  'level_achieved' => 3, 'completion_date' => '2020-08-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('80197'), 'competency_track_id' => $track('BOMT'), 'level_achieved' => 3, 'completion_date' => '2022-03-01', 'source' => 'import'];

        // RINA FITRIANA (Service Head, T260 BOGOR)
        $records[] = ['employee_id' => $emp('66789'), 'competency_track_id' => $track('TSMT_LV1'), 'level_achieved' => 3, 'completion_date' => '2008-05-01', 'source' => 'import'];
        $records[] = ['employee_id' => $emp('66789'), 'competency_track_id' => $track('TSMT_LV2'), 'level_achieved' => 3, 'completion_date' => '2012-10-01', 'source' => 'import'];

        foreach ($records as $record) {
            // Avoid duplicate inserts on re-seed
            TrainingRecord::firstOrCreate(
                [
                    'employee_id'         => $record['employee_id'],
                    'competency_track_id' => $record['competency_track_id'],
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
