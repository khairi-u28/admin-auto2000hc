<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $admin = User::query()
            ->whereIn('email', ['admin@auto2000hc.id', 'admin@auto2000.co.id'])
            ->orderByRaw("FIELD(email, 'admin@auto2000hc.id', 'admin@auto2000.co.id')")
            ->first();

        if ($admin) {
            $admin->syncRoles([$role]);
        }
    }
}

