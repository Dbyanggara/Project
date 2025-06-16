<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $sellerRole = Role::create(['name' => 'seller']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        $permissions = [
            'manage users',
            'manage sellers',
            'manage menus',
            'manage orders',
            'view orders',
            'manage kantin',
            'view kantin',
            'place orders',
            'manage own menu',
            'manage own orders',
            'view own orders',
            'chat with seller',
            'chat with customer'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo($permissions); // Admin gets all permissions

        $sellerRole->givePermissionTo([
            'manage own menu',
            'manage own orders',
            'view own orders',
            'chat with customer'
        ]);

        $userRole->givePermissionTo([
            'view kantin',
            'place orders',
            'view own orders',
            'chat with seller'
        ]);
    }
}
