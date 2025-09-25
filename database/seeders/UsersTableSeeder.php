<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(attributes: [
            'name' => 'Đào Văn Hùng',
            'email' => 'hung@example.com',
            'password' => bcrypt(value: '123456'),
            'phone_number' => '0123456789',
            'status' => 'pending',        
            'avatar' => '',
            'address' => 'Hanoi, Vietnam',
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create(attributes: [
            'name' => 'Lê Quang Tuyến',
            'email' => 'hong@example.com',
            'password' => bcrypt(value: '123456'),
            'phone_number' => '0123456789',
            'status' => 'pending',        
            'avatar' => '',
            'address' => 'Ho Chi Minh City, Vietnam',
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create(attributes: [
            'name' => 'Trần Đình Anh Quân',
            'email' => 'huong@example.com',
            'password' => bcrypt(value: '123456'),
            'phone_number' => '0123456789',
            'status' => 'pending',        
            'avatar' => '',
            'address' => 'Da Nang, Vietnam',
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
