<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Branch;
use App\Models\Region;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            // ── HEAD OFFICE ──────────────────────────────────────────────
            ['code' => 'T000', 'name' => 'HEAD OFFICE',           'area' => 'HEAD OFFICE',  'region' => 'HEAD OFFICE',              'type' => 'HO'],

            // ── DKI 1 ─────────────────────────────────────────────────────
            ['code' => 'T009', 'name' => 'PLUIT',                 'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T012', 'name' => 'KAPUK',                 'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T052', 'name' => 'DAAN MOGOT',            'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T055', 'name' => 'PURI KEMBANGAN',        'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T059', 'name' => 'SLIPI',                 'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T156', 'name' => 'JAYAKARTA',             'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T207', 'name' => 'CILANDAK',              'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            // DKI1 – 10 additional
            ['code' => 'T210', 'name' => 'LEBAK BULUS',           'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T211', 'name' => 'FATMAWATI',             'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T212', 'name' => 'CINERE',                'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T213', 'name' => 'PAMULANG',              'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T214', 'name' => 'CIPUTAT',               'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T215', 'name' => 'SERPONG',               'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T216', 'name' => 'BINTARO',               'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T217', 'name' => 'TANGERANG',             'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T218', 'name' => 'KARAWACI',              'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T219', 'name' => 'GADING SERPONG',        'area' => 'DKI1',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],

            // ── DKI 2 ─────────────────────────────────────────────────────
            ['code' => 'T002', 'name' => 'SUNTER',                'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T004', 'name' => 'BP SUNTER',             'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'BP'],
            ['code' => 'T100', 'name' => 'BEKASI',                'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T102', 'name' => 'KRAMAT JATI',           'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T103', 'name' => 'KALIMALANG',            'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T105', 'name' => 'KELAPA GADING',         'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T110', 'name' => 'BEKASI BARAT',          'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T151', 'name' => 'JUANDA',                'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T154', 'name' => 'ANGKASA',               'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T155', 'name' => 'CEMPAKA PUTIH',         'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T260', 'name' => 'BOGOR',                 'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            // DKI2 – 10 additional
            ['code' => 'T106', 'name' => 'CIBUBUR',               'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T107', 'name' => 'DEPOK',                 'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T108', 'name' => 'CILEUNGSI',             'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T109', 'name' => 'CIKARANG',              'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T111', 'name' => 'HARAPAN INDAH',         'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T112', 'name' => 'GALAXY',                'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T113', 'name' => 'PONDOK GEDE',           'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T114', 'name' => 'JATIASIH',              'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T115', 'name' => 'BEKASI TIMUR',          'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T116', 'name' => 'TAMBUN',                'area' => 'DKI2',         'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],

            // ── JABAR ──────────────────────────────────────────────────────
            ['code' => 'T251', 'name' => 'BDG S.H.',              'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T252', 'name' => 'BANDUNG PASTEUR',       'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            // JABAR – 10 additional
            ['code' => 'T253', 'name' => 'BANDUNG KOPO',          'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T254', 'name' => 'BANDUNG METRO',         'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T255', 'name' => 'BANDUNG SOEKARNO HATTA','area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T261', 'name' => 'BOGOR JUANDA',          'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T262', 'name' => 'CIBINONG',              'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T263', 'name' => 'SUKABUMI',              'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T264', 'name' => 'CIREBON',               'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T265', 'name' => 'TASIKMALAYA',           'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T266', 'name' => 'KARAWANG',              'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],
            ['code' => 'T267', 'name' => 'PURWAKARTA',            'area' => 'JABAR',        'region' => 'DKI JABAR PRIME FLEET',    'type' => 'GR'],

            // ── JATIM ──────────────────────────────────────────────────────
            ['code' => 'T451', 'name' => 'SBY SUNGKONO',          'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            // JATIM – 10 additional
            ['code' => 'T452', 'name' => 'SBY MERCEDES',          'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T453', 'name' => 'WARU',                  'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T454', 'name' => 'SIDOARJO',              'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T455', 'name' => 'MOJOKERTO',             'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T456', 'name' => 'MALANG',                'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T457', 'name' => 'SBY PANJANG JIWO',      'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T458', 'name' => 'SURABAYA RAYA',         'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T459', 'name' => 'GRESIK',                'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T460', 'name' => 'PASURUAN',              'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T461', 'name' => 'JEMBER',                'area' => 'JATIM',        'region' => 'JATKALBAL',                'type' => 'GR'],

            // ── KALIMANTAN ─────────────────────────────────────────────────
            ['code' => 'T700', 'name' => 'BALIKPAPAN',            'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T701', 'name' => 'BALIKPAPAN 2',          'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            // KALIMANTAN – 10 additional
            ['code' => 'T702', 'name' => 'SAMARINDA',             'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T703', 'name' => 'BANJARMASIN',           'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T704', 'name' => 'PONTIANAK',             'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T705', 'name' => 'PALANGKARAYA',          'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T706', 'name' => 'BONTANG',               'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T707', 'name' => 'TARAKAN',               'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T708', 'name' => 'SAMBAS',                'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T709', 'name' => 'KETAPANG',              'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T710', 'name' => 'SINGKAWANG',            'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],
            ['code' => 'T711', 'name' => 'SUKAMARA',              'area' => 'KALIMANTAN',   'region' => 'JATKALBAL',                'type' => 'GR'],

            // ── SUMBAGUT ───────────────────────────────────────────────────
            ['code' => 'T800', 'name' => 'MEDAN ISKANDAR MUDA',   'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T801', 'name' => 'MEDAN GATSU',           'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T802', 'name' => 'MEDAN SISINGAMANGARAJA','area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T803', 'name' => 'PEKANBARU',             'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T804', 'name' => 'PADANG',                'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T805', 'name' => 'BANDA ACEH',            'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T806', 'name' => 'BINJAI',                'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T807', 'name' => 'TEBING TINGGI',         'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T808', 'name' => 'BATAM',                 'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T809', 'name' => 'TANJUNG PINANG',        'area' => 'SUMBAGUT',     'region' => 'SUMATERA',                 'type' => 'GR'],

            // ── SUMBAGSEL ──────────────────────────────────────────────────
            ['code' => 'T900', 'name' => 'PALEMBANG',             'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T901', 'name' => 'LAMPUNG',               'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T902', 'name' => 'JAMBI',                 'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T903', 'name' => 'BENGKULU',              'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T904', 'name' => 'BANDAR LAMPUNG',        'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T905', 'name' => 'LUBUKLINGGAU',          'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T906', 'name' => 'BATURAJA',              'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T907', 'name' => 'PRINGSEWU',             'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T908', 'name' => 'MUARA DUA',             'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
            ['code' => 'T909', 'name' => 'METRO',                 'area' => 'SUMBAGSEL',    'region' => 'SUMATERA',                 'type' => 'GR'],
        ];

        foreach ($branches as $branch) {
            $region = Region::firstOrCreate(
                ['nama_region' => $branch['region']],
                ['nama_rbh' => null]
            );

            $area = Area::firstOrCreate(
                [
                    'region_id' => $region->id,
                    'nama_area' => $branch['area'],
                ],
                ['nama_abh' => null]
            );

            Branch::updateOrCreate([
                'code' => $branch['code'],
            ], [
                'name' => $branch['name'],
                'area' => $branch['area'],
                'region' => $branch['region'],
                'type' => $branch['type'],
                'region_id' => $region->id,
                'area_id' => $area->id,
                'kode_cabang' => $branch['code'],
                'nama' => $branch['name'],
            ]);
        }
    }
}
