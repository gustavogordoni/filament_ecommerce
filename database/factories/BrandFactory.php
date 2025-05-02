<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company;  
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => 'brands/' . $this->faker->image('storage/app/public/brands', 400, 300, null, false),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
