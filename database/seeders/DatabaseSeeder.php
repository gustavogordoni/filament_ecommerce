<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@email.com',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@email.com',
        ]);

        User::factory()->count(5)->create();
        Category::factory()->count(10)->create();
        Brand::factory()->count(10)->create();
        Product::factory()->count(50)->create();
        Order::factory()->count(100)->create();
    }
}
