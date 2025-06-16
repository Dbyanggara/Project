<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Buat User Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => \Str::random(10),
        ]);

        // Assign role admin ke user admin
        $admin->assignRole('admin');

        // Buat User Seller
        $seller = User::create([
            'name' => 'Seller',
            'email' => 'seller@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => \Str::random(10),
        ]);

        // Assign role seller ke user seller
        $seller->assignRole('seller');

        // Buat User Regular
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => \Str::random(10),
        ]);

        // Assign role user ke user regular
        $user->assignRole('user');
    }
}
