<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Default admin user (used as created_by / recorded_by FK for content)
        User::firstOrCreate(
            ['email' => 'admin@auto2000hc.id'],
            [
                'name'     => 'Admin Ruang Kompetensi',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            FullDemoSeeder::class,
        ]);
    }
}
