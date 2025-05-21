<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Apple',
            'Samsung',
            'Dell',
            'Lenovo',
            'Asus',
            'Xiaomi',
            'HP',
            'Motorola',
            'Acer',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'image' => null,
                'is_active' => true,
            ]);
        }
    }
}
