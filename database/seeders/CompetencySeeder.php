<?php

namespace Database\Seeders;

use App\Models\Competency;
use Illuminate\Database\Seeder;

class CompetencySeeder extends Seeder
{
    public function run(): void
    {
        $competencies = [
            // ── Aftersales / Mechanic GR ─────────────────────────────────────
            [
                'code'        => 'COMP-MCH-001',
                'name'        => 'Mekanik GR Level 1',
                'description' => 'Kompetensi dasar teknis mekanik General Repair untuk kendaraan Toyota.',
                'tags'        => ['Aftersales'],
            ],
            [
                'code'        => 'COMP-MCH-002',
                'name'        => 'Mekanik GR Level 2',
                'description' => 'Kompetensi menengah mekanik GR meliputi diagnosis dan perbaikan lanjutan.',
                'tags'        => ['Aftersales'],
            ],
            [
                'code'        => 'COMP-MCH-003',
                'name'        => 'Mekanik GR Level 3',
                'description' => 'Kompetensi expert mekanik GR termasuk troubleshooting kompleks.',
                'tags'        => ['Aftersales'],
            ],

            // ── Aftersales / Service Advisor ─────────────────────────────────
            [
                'code'        => 'COMP-SA-001',
                'name'        => 'Service Advisor Fundamental',
                'description' => 'Kompetensi dasar pelayanan pelanggan dan proses service order.',
                'tags'        => ['Aftersales'],
            ],

            // ── Sales ─────────────────────────────────────────────────────────
            [
                'code'        => 'COMP-SC-001',
                'name'        => 'Sales Consultant Fundamental',
                'description' => 'Kompetensi dasar penjualan, product knowledge, dan customer handling.',
                'tags'        => ['Sales'],
            ],
            [
                'code'        => 'COMP-SC-002',
                'name'        => 'Sales Consultant Advanced',
                'description' => 'Kompetensi lanjutan negosiasi, closing, dan after-sales relationship.',
                'tags'        => ['Sales'],
            ],

            // ── HO / People Development ──────────────────────────────────────
            [
                'code'        => 'COMP-HC-001',
                'name'        => 'HC Analyst Fundamental',
                'description' => 'Kompetensi dasar analisis SDM, pelaporan, dan administrasi HC.',
                'tags'        => ['HO', 'People Development'],
            ],
            [
                'code'        => 'COMP-PD-001',
                'name'        => 'People Development Core',
                'description' => 'Kompetensi inti pengembangan SDM meliputi TNA, desain program, dan evaluasi.',
                'tags'        => ['HO', 'People Development'],
            ],

            // ── General ──────────────────────────────────────────────────────
            [
                'code'        => 'COMP-GEN-001',
                'name'        => 'Soft Skills & Leadership',
                'description' => 'Kompetensi umum komunikasi, teamwork, dan kepemimpinan.',
                'tags'        => ['General'],
            ],
        ];

        foreach ($competencies as $item) {
            Competency::firstOrCreate(['code' => $item['code']], $item);
        }
    }
}
