<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@garden.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'admin',
                'status' => 'active',
            ]
        );
        $this->command->info('Admin user verified/created: admin@garden.com / password');

        // 2. Customer User
        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'customer',
                'status' => 'active',
            ]
        );
        $this->command->info('Customer user verified/created: customer@example.com / password');

        // 3. Seller User
        User::updateOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'Seller User',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'role' => 'seller',
                'status' => 'active',
            ]
        );
        $this->command->info('Seller user verified/created: seller@example.com / password');

        // 4. Seed additional customers using factory
        User::factory()->count(10)->create([
            'role' => 'customer',
            'status' => 'active',
        ]);
        $this->command->info('Seeded 10 random customer users.');

        // 5. Seed additional sellers using factory
        User::factory()->count(5)->create([
            'role' => 'seller',
            'status' => 'active',
        ]);
        $this->command->info('Seeded 5 random seller users.');
    }
}
