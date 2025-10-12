<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(2, true); // tạo tên sản phẩm

        return [
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1, // fallback nếu chưa có category
            'description' => $this->faker->sentence(10),
            'price'       => $this->faker->randomFloat(2, 150000, 1200000),
            'stock'       => $this->faker->numberBetween(10, 100),
            'status'      => $this->faker->randomElement(['in-stock', 'out-of-stock']),
            'unit'        => 'cái',
            'image'       => $this->faker->imageUrl(400, 400, 'clothes', true),
        ];
    }
}
