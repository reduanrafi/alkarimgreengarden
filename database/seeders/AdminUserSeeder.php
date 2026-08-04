<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@garden.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@garden.com',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created: admin@garden.com / password');
    }
}
