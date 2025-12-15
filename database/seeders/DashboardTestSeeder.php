<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardTestSeeder extends Seeder
{
    public function run()
    {
        // Xóa dữ liệu cũ (tùy chọn)
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('users')->truncate();

        // ================= USERS =================
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('123456'),
                'role' => 'admin', // Nếu bạn có cột role
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // 10 khách hàng
        for ($i=1; $i<=10; $i++) {
            $users[] = [
                'name' => "Client $i",
                'email' => "client$i@example.com",
                'password' => bcrypt('123456'),
                'role' => 'client',
                'created_at' => now()->subDays(rand(1,30)),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);

        // ================= PRODUCTS =================
        $products = [];
        for ($i=1; $i<=20; $i++) {
            $products[] = [
                'name' => "Sản phẩm $i",
                'category_id' => rand(1,5), // giả sử có 5 category
                'price' => rand(10000, 500000),
                'created_at' => now()->subDays(rand(1,60)),
                'updated_at' => now(),
            ];
        }
        DB::table('products')->insert($products);

        // ================= ORDERS + ORDER ITEMS =================
        $orders = [];
        $orderItems = [];

        $statusList = ['pending','processing','completed','cancelled','received'];

        for ($i=1; $i<=30; $i++) {
            $user_id = rand(2,11); // khách hàng (tránh admin)
            $status = $statusList[array_rand($statusList)];
            $created_at = now()->subDays(rand(0,30));

            $total_amount = 0;
            $numItems = rand(1,5);

            for ($j=1; $j<=$numItems; $j++) {
                $product_id = rand(1,20);
                $quantity = rand(1,3);
                $price = DB::table('products')->where('id',$product_id)->value('price');
                $total_amount += $price * $quantity;

                $orderItems[] = [
                    'order_id' => $i,
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'created_at' => $created_at,
                    'updated_at' => $created_at
                ];
            }

            $orders[] = [
                'id' => $i,
                'user_id' => $user_id,
                'status' => $status,
                'total_amount' => $total_amount,
                'created_at' => $created_at,
                'updated_at' => $created_at
            ];
        }

        DB::table('orders')->insert($orders);
        DB::table('order_items')->insert($orderItems);

        $this->command->info('✅ Test data for dashboard created successfully!');
    }
}
