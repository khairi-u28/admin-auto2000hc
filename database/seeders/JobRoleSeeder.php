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
            ['code' => 'SC001',  'name' => 'Sales Consultant',           'department' => 'Sales',      'level' => 'Consultant',    'category' => 'Sales Consultant'],
            ['code' => 'AC001',  'name' => 'Account Supervisor',         'department' => 'Sales',      'level' => 'Supervisor',    'category' => 'Account Supervisor'],
            ['code' => 'BC001',  'name' => 'Branch Coordinator',         'department' => 'Sales',      'level' => 'Coordinator',   'category' => 'Branch Coordinator'],
            ['code' => 'PAS01',  'name' => 'Product Advisor Supervisor', 'department' => 'Sales',      'level' => 'Supervisor',    'category' => 'Product Advisor Supervisor'],

            // ── AFTERSALES GR ─────────────────────────────────────────────
            ['code' => 'MTR01',  'name' => 'Mechanic Trainee',           'department' => 'Aftersales', 'level' => 'Trainee',       'category' => 'Mechanic GR'],
            ['code' => 'MCH01',  'name' => 'Mechanic 1',                 'department' => 'Aftersales', 'level' => 'Level 1',       'category' => 'Mechanic GR'],
            ['code' => 'MCH02',  'name' => 'Mechanic 2',                 'department' => 'Aftersales', 'level' => 'Level 2',       'category' => 'Mechanic GR'],
            ['code' => 'MCH03',  'name' => 'Mechanic 3',                 'department' => 'Aftersales', 'level' => 'Level 3',       'category' => 'Mechanic GR'],
            ['code' => 'FO001',  'name' => 'Foreman',                    'department' => 'Aftersales', 'level' => 'Foreman',       'category' => 'Foreman'],
            ['code' => 'SA001',  'name' => 'Service Advisor',            'department' => 'Aftersales', 'level' => 'Advisor',       'category' => 'Service Advisor'],
            ['code' => 'PM001',  'name' => 'Partman',                    'department' => 'Aftersales', 'level' => 'Partman',       'category' => 'Partman'],
            ['code' => 'CRC01',  'name' => 'CRC',                        'department' => 'Aftersales', 'level' => 'Specialist',    'category' => 'CRC'],
            ['code' => 'SH001',  'name' => 'Service Head',               'department' => 'Aftersales', 'level' => 'Head',          'category' => 'Service Head'],

            // ── AFTERSALES BP ─────────────────────────────────────────────
            ['code' => 'MTBP1',  'name' => 'Mechanic Trainee BP',        'department' => 'Aftersales', 'level' => 'Trainee',       'category' => 'Mechanic BP'],
            ['code' => 'MB001',  'name' => 'Mechanic 1 BP',              'department' => 'Aftersales', 'level' => 'Level 1',       'category' => 'Mechanic BP'],
            ['code' => 'MB002',  'name' => 'Mechanic 2 BP',              'department' => 'Aftersales', 'level' => 'Level 2',       'category' => 'Mechanic BP'],
            ['code' => 'MB003',  'name' => 'Mechanic 3 BP',              'department' => 'Aftersales', 'level' => 'Level 3',       'category' => 'Mechanic BP'],

            // ── AFTERSALES THS ────────────────────────────────────────────
            ['code' => 'MTHS1',  'name' => 'Mechanic THS',               'department' => 'Aftersales', 'level' => 'Specialist',    'category' => 'Mechanic THS'],

            // ── HC ────────────────────────────────────────────────────────
            ['code' => 'HCA01',  'name' => 'HC Analyst',                 'department' => 'HC',         'level' => 'Analyst',       'category' => 'HC Analyst'],
            ['code' => 'HCS01',  'name' => 'HC Specialist',              'department' => 'HC',         'level' => 'Specialist',    'category' => 'HC Specialist'],
            ['code' => 'HCH01',  'name' => 'HC Head',                    'department' => 'HC',         'level' => 'Head',          'category' => 'HC Head'],
        ];

        foreach ($roles as $role) {
            JobRole::firstOrCreate(['code' => $role['code']], $role);
        }
    }
}
