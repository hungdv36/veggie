<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Variant;

class VariantSeeder extends Seeder
{
    public function run(): void
    {
        $sizes  = ['S', 'M', 'L', 'XL'];
        $colors = ['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Hồng'];

        foreach (Product::all() as $product) {
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
