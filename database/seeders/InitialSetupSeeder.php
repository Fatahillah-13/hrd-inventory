<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InitialSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $atkMasterRole = Role::firstOrCreate(['name' => 'atk_master']);
        $adminDivisiRole = Role::firstOrCreate(['name' => 'admin_divisi']);

        // Divisions HRD (contoh)
        $divisions = [
            ['name' => 'Payroll', 'code' => 'PAY'],
            ['name' => 'Rekrutmen', 'code' => 'REC'],
            ['name' => 'Training', 'code' => 'TRN'],
            ['name' => 'HR-IT', 'code' => 'HRIT'],
        ];

        foreach ($divisions as $d) {
            Division::firstOrCreate(['code' => $d['code']], $d);
        }

        // Admin default (ubah email/password sesuai kebutuhan)
        $admin = User::firstOrCreate(
            ['email' => 'admin@hrd.local'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'division_id' => null,
            ]
        );
        $admin->assignRole($adminRole);
    }
}
