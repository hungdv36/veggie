<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'size'       => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'color'      => $this->faker->randomElement(['Đen', 'Trắng', 'Xanh', 'Đỏ', 'Hồng']),
            'stock'      => $this->faker->numberBetween(5, 50),
            'price'      => $this->faker->randomFloat(2, 150000, 1200000),
        ];
    }
}
