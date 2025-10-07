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
            ['name' => 'Rau củ', 'slug' => 'rau-cu', 'description' => 'Các loại rau củ tươi ngon', 'image' => 'uploads/categories/rau-cu.jpg'],
            ['name' => 'Trái cây', 'slug' => 'trai-cay', 'description' => 'Các loại trái cây tươi ngon', 'image' => 'uploads/categories/trai-cay.jpg'],
            ['name' => 'Thịt cá', 'slug' => 'thit-ca', 'description' => 'Các loại thịt và hải sản tươi ngon', 'image' => 'uploads/categories/thit-ca.jpg'],
            ['name' => 'Đồ uống', 'slug' => 'do-uong', 'description' => 'Các loại đồ uống giải khát', 'image' => 'uploads/categories/do-uong.jpg'],
            ['name' => 'Đồ khô', 'slug' => 'do-kho', 'description' => 'Các loại thực phẩm khô', 'image' => 'uploads/categories/do-kho.jpg'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
