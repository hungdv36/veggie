<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class RolePermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where(column: 'name', operator: 'admin')->first();
        $staffRole = Role::where(column: 'name', operator: 'staff')->first();

        $permissions = Permission::all();

        // Admin have all permissions
        $adminRole->permissions()->sync(ids: $permissions);

        // Staff have limited permissions
        $staffPermissions = $permissions->whereIn(key: 'name', values: [
            'manage_products',
            'manage_contacts',
        ]);

        $staffRole->permissions()->sync(ids: $staffPermissions);
    }
}
