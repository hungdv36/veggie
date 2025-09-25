<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(attributes: [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt(value: '123456'),
            'phone_number' => '0999999999',
            'status' => 'active',        
            'avatar' => '',
            'address' => 'Hanoi, Vietnam',
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create(attributes: [
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'password' => bcrypt(value: '123456'),
            'phone_number' => '0888888888',
            'status' => 'active',        
            'avatar' => '',
            'address' => 'Ho Chi Minh City, Vietnam',
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
