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
        $adminRole = Role::where('name', 'admin')->first();
        $staffRole = Role::where('name', 'staff')->first();

        $permissions = Permission::all();

        // Admin có tất cả quyền
        $adminRole->permissions()->sync($permissions->pluck('id')->toArray());

        // Staff có quyền giới hạn
        $staffPermissions = $permissions->whereIn('name', [
            'manage_products',
            'manage_contacts',
        ]);

        $staffRole->permissions()->sync($staffPermissions->pluck('id')->toArray());
    }
}
