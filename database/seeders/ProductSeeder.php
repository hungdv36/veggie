<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $productNames = [
            'Áo thun nam',
            'Áo sơ mi nam',
            'Quần jean nam',
            'Quần kaki nam',
            'Áo thun nữ',
            'Áo kiểu nữ',
            'Quần jean nữ',
            'Quần short nữ',
            'Váy đầm nữ',
            'Váy công sở nữ'
        ];

        foreach ($productNames as $name) {
            // Tạo product
            $product = Product::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . rand(1, 1000),
            ]);

            // Tạo biến thể size + màu cho product
            $sizes  = ['S', 'M', 'L', 'XL'];
            $colors = ['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Hồng'];

            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    Variant::factory()->create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'color'      => $color,
                        'stock'      => rand(5, 50),
                        'price'      => rand(150000, 1200000),
                    ]);
                }
            }
        }
    }
}
