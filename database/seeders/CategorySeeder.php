<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Áo nam', 'description' => 'Áo Nam thời trang, trẻ trung, nhiều màu sắc và size đa dạng, phù hợp mọi dịp.', 'image' => 'uploads/categories/rau-cu.jpg'],
            ['name' => 'Quần nam', 'description' => 'Quần Nam đa dạng kiểu dáng, vừa vặn, thoải mái và thời trang cho mọi hoạt động', 'image' => 'uploads/categories/trai-cay.jpg'],
            ['name' => 'Áo nữ','description' => 'Áo Nữ thanh lịch, nhiều màu sắc và size, phù hợp phong cách cá nhân.', 'image' => 'uploads/categories/thit-ca.jpg'],
            ['name' => 'Quần nữ', 'description' => 'Quần Nữ thời trang, dễ phối đồ, nhiều màu sắc và size đa dạng.', 'image' => 'uploads/categories/do-uong.jpg'],
            ['name' => 'Váy nữ', 'description' => 'Váy Nữ tinh tế, nữ tính, nhiều màu sắc và size, phù hợp mọi dịp.', 'image' => 'uploads/categories/do-kho.jpg'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
