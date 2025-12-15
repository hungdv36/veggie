<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ================= Tạo dữ liệu test dashboard =================
        // Lưu ý: Chỉ chọn 1 seeder để tránh xóa dữ liệu trùng lặp
        $this->call(\Database\Seeders\FullTestSeeder::class);
        // Hoặc nếu muốn dùng DashboardTestSeeder thay thế
        // $this->call(\Database\Seeders\DashboardTestSeeder::class);

        // ================= Tạo role & permission =================
        $this->call([
            RolePermissionsTableSeeder::class,
        ]);

        // ================= Seeder khác (tùy chọn) =================
        // $this->call([
        //     RolesTableSeeder::class,
        //     PermissionsTableSeeder::class,
        //     AdminStaffTableSeeder::class,
        //     UsersTableSeeder::class,
        //     CategorySeeder::class,
        //     ProductSeeder::class,
        //     VariantSeeder::class,
        // ]);
    }
}
