<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FullTestSeeder extends Seeder
{
    public function run()
    {
        // ================= RESET DỮ LIỆU AN TOÀN =================
        // Xóa bảng con trước bảng cha, dùng delete() thay vì truncate() để tránh FK
        DB::table('return_requests')->delete();   // nếu có dữ liệu
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('products')->delete();
        DB::table('users')->delete();
        DB::table('user_visits')->delete();

        // Truncate những bảng không bị FK
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_permission')->truncate();

        // ================= ROLE & PERMISSION =================
        $roleId = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissionId = DB::table('permissions')->insertGetId([
            'name' => 'full-access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_permission')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
        ]);

        // ================= USERS =================
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('123456'),
            'role_id' => $roleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $userIds[] = DB::table('users')->insertGetId([
                'name' => "Client $i",
                'email' => "client$i@example.com",
                'password' => bcrypt('123456'),
                'role_id' => null,
                'created_at' => now()->subDays(rand(1,30)),
                'updated_at' => now(),
            ]);
        }

        // ================= PRODUCTS =================
        $productIds = [];
        for ($i = 1; $i <= 20; $i++) {
            $productIds[] = DB::table('products')->insertGetId([
                'name' => "Sản phẩm $i",
                'category_id' => rand(1,5),
                'price' => rand(10000, 500000),
                'created_at' => now()->subDays(rand(0,60)),
                'updated_at' => now(),
            ]);
        }

        // ================= ORDERS + ORDER ITEMS =================
        $statusList = ['pending','processing','completed','cancelled','received'];

        for ($i = 1; $i <= 30; $i++) {
            $user_id = $userIds[array_rand($userIds)];
            $status = $statusList[array_rand($statusList)];
            $created_at = now()->subDays(rand(0,30));

            $total_amount = 0;
            $numItems = rand(1,5);
            $items = [];

            for ($j = 1; $j <= $numItems; $j++) {
                $product_id = $productIds[array_rand($productIds)];
                $quantity = rand(1,3);
                $price = DB::table('products')->where('id',$product_id)->value('price');
                $total_amount += $price * $quantity;

                $items[] = [
                    'order_id' => $i,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ];
            }

            DB::table('orders')->insert([
                'id' => $i,
                'user_id' => $user_id,
                'status' => $status,
                'total_amount' => $total_amount,
                'created_at' => $created_at,
                'updated_at' => $created_at
            ]);

            DB::table('order_items')->insert($items);
        }

        // ================= USER VISITS =================
        $devices = ['Desktop','Mobile','Tablet'];
        for($i=1;$i<=50;$i++){
            DB::table('user_visits')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'device' => $devices[array_rand($devices)],
                'visited_at' => now()->subDays(rand(0,30))
            ]);
        }

        $this->command->info('✅ Full test data created successfully!');
    }
}
