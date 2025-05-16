<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;

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
        $name = $this->faker->sentence(3);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'images' => json_encode([
                'products/' . $this->faker->image('storage/app/public/products', 400, 300, null, false),
            ]),
            'is_active' => $this->faker->boolean(90),
            'is_featured' => $this->faker->boolean(40),
            'on_sale' => $this->faker->boolean(30),
            'in_stock' => $this->faker->boolean(85),
        ];
    }
}
