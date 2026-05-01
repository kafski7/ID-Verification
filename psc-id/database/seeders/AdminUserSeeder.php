<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::firstOrCreate(
            ['email' => 'admin@psc.gov'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'role'     => 'SUPER_ADMIN',
            ]
        );

        AdminUser::firstOrCreate(
            ['email' => 'hr@psc.gov'],
            [
                'name'     => 'HR Admin',
                'password' => Hash::make('password'),
                'role'     => 'HR_ADMIN',
            ]
        );
    }
}
