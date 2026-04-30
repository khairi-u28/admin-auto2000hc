<?php

namespace Database\Seeders;

use App\Models\JobRole;
use Illuminate\Database\Seeder;

class JobRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            // ── SALES ─────────────────────────────────────────────────────
            ['code' => 'SC001',  'name' => 'Sales Consultant',           'department' => 'Sales',      'level' => '1', 'golongan' => 'I'],
            ['code' => 'AC001',  'name' => 'Account Supervisor',         'department' => 'Sales',      'level' => '2', 'golongan' => 'II'],
            ['code' => 'BC001',  'name' => 'Branch Coordinator',         'department' => 'Sales',      'level' => '3', 'golongan' => 'III'],
            ['code' => 'PAS01',  'name' => 'Product Advisor Supervisor', 'department' => 'Sales',      'level' => '2', 'golongan' => 'II'],
            ['code' => 'SM001',  'name' => 'Sales Manager',             'department' => 'Sales',      'level' => '4', 'golongan' => 'IV'],
            ['code' => 'ABH01',  'name' => 'Area Business Head',        'department' => 'Sales',      'level' => '5', 'golongan' => 'V'],
            ['code' => 'RBH01',  'name' => 'Region Business Head',      'department' => 'Sales',      'level' => '6', 'golongan' => 'V'],

            // ── AFTERSALES GR ─────────────────────────────────────────────
            ['code' => 'MTR01',  'name' => 'Mechanic Trainee',           'department' => 'Aftersales', 'level' => '0', 'golongan' => 'I'],
            ['code' => 'MCH01',  'name' => 'Mechanic 1',                 'department' => 'Aftersales', 'level' => '1', 'golongan' => 'I'],
            ['code' => 'MCH02',  'name' => 'Mechanic 2',                 'department' => 'Aftersales', 'level' => '2', 'golongan' => 'II'],
            ['code' => 'MCH03',  'name' => 'Mechanic 3',                 'department' => 'Aftersales', 'level' => '3', 'golongan' => 'II'],
            ['code' => 'FO001',  'name' => 'Foreman',                    'department' => 'Aftersales', 'level' => '3', 'golongan' => 'III'],
            ['code' => 'SA001',  'name' => 'Service Advisor',            'department' => 'Aftersales', 'level' => '2', 'golongan' => 'II'],
            ['code' => 'PM001',  'name' => 'Partman',                    'department' => 'Aftersales', 'level' => '1', 'golongan' => 'I'],
            ['code' => 'CRC01',  'name' => 'CRC',                        'department' => 'Aftersales', 'level' => '2', 'golongan' => 'II'],
            ['code' => 'SH001',  'name' => 'Service Head',               'department' => 'Aftersales', 'level' => '4', 'golongan' => 'IV'],
            ['code' => 'SAM01',  'name' => 'Service Advisor Manager',    'department' => 'Aftersales', 'level' => '3', 'golongan' => 'III'],

            // ── AFTERSALES BP ─────────────────────────────────────────────
            ['code' => 'MTBP1',  'name' => 'Mechanic Trainee BP',        'department' => 'Aftersales', 'level' => '0', 'golongan' => 'I'],
            ['code' => 'MB001',  'name' => 'Mechanic 1 BP',              'department' => 'Aftersales', 'level' => '1', 'golongan' => 'I'],
            ['code' => 'MB002',  'name' => 'Mechanic 2 BP',              'department' => 'Aftersales', 'level' => '2', 'golongan' => 'II'],
            ['code' => 'MB003',  'name' => 'Mechanic 3 BP',              'department' => 'Aftersales', 'level' => '3', 'golongan' => 'II'],

            // ── AFTERSALES THS ────────────────────────────────────────────
            ['code' => 'MTHS1',  'name' => 'Mechanic THS',               'department' => 'Aftersales', 'level' => '2', 'golongan' => 'II'],

            // ── HC ────────────────────────────────────────────────────────
            ['code' => 'HCA01',  'name' => 'HC Analyst',                 'department' => 'HC',         'level' => '1', 'golongan' => 'I'],
            ['code' => 'HCS01',  'name' => 'HC Specialist',              'department' => 'HC',         'level' => '2', 'golongan' => 'II'],
            ['code' => 'HCH01',  'name' => 'HC Head',                    'department' => 'HC',         'level' => '4', 'golongan' => 'IV'],
            ['code' => 'HCM01',  'name' => 'HC Manager',                 'department' => 'HC',         'level' => '3', 'golongan' => 'III'],

            // ── PD ────────────────────────────────────────────────────────
            ['code' => 'PD001',  'name' => 'Product Development',        'department' => 'PD',         'level' => '2', 'golongan' => 'II'],
            ['code' => 'PDM01',  'name' => 'PD Manager',                 'department' => 'PD',         'level' => '4', 'golongan' => 'IV'],

            // ── GS ────────────────────────────────────────────────────────
            ['code' => 'GS001',  'name' => 'General Services',           'department' => 'GS',         'level' => '1', 'golongan' => 'I'],
            ['code' => 'GSM01',  'name' => 'GS Manager',                 'department' => 'GS',         'level' => '3', 'golongan' => 'III'],
        ];

        foreach ($roles as $role) {
            JobRole::firstOrCreate(['code' => $role['code']], $role);
        }
    }
}
