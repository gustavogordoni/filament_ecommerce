<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productMap = [
            'Apple' => [
                'Smartphones' => ['iPhone 14 Pro', 'iPhone 13', 'iPhone SE 3'],
                'Laptops' => ['MacBook Pro 14"', 'MacBook Air M2'],
                'Smartwatches' => ['Apple Watch Series 9', 'Apple Watch SE'],
                'Headphones' => ['AirPods Pro', 'AirPods Max'],
                'Accessories' => ['MagSafe Charger', 'Apple Pencil'],
            ],
            'Samsung' => [
                'Smartphones' => ['Galaxy S23 Ultra', 'Galaxy A54', 'Galaxy Z Fold 5'],
                'Tablets' => ['Galaxy Tab S9'],
                'Smartwatches' => ['Galaxy Watch 6', 'Galaxy Watch 5 Pro'],
                'Headphones' => ['Galaxy Buds2 Pro', 'Galaxy Buds Live'],
                'Accessories' => ['S Pen', 'Fast Charging Travel Adapter'],
            ],
            'Dell' => [
                'Laptops' => ['Dell XPS 13', 'Dell Inspiron 15'],
                'Monitors' => ['Dell UltraSharp U2723QE', 'Dell SE2422HX'],
                'Accessories' => ['Dell USB-C Dock', 'Dell Premier Wireless Mouse'],
            ],
            'Lenovo' => [
                'Laptops' => ['ThinkPad X1 Carbon', 'Ideapad Flex 5'],
                'Monitors' => ['Lenovo ThinkVision T27h', 'Lenovo L24q'],
                'Accessories' => ['Lenovo USB-C Hub', 'Lenovo Precision Mouse'],
            ],
            'Asus' => [
                'Laptops' => ['ROG Strix G15', 'VivoBook 15'],
                'Monitors' => ['Asus TUF Gaming VG27AQ', 'Asus ProArt Display'],
                'Accessories' => ['Asus USB-C Dock', 'Asus Gaming Headset'],
            ],
            'Xiaomi' => [
                'Smartphones' => ['Xiaomi 13', 'Redmi Note 12'],
                'Tablets' => ['Xiaomi Pad 6'],
                'Smartwatches' => ['Xiaomi Watch S1', 'Redmi Watch 3'],
                'Headphones' => ['Redmi Buds 4 Pro', 'Xiaomi Mi Earphones'],
                'Accessories' => ['Xiaomi Power Bank 20000mAh', 'Smart Band 7 Charger'],
            ],
            'HP' => [
                'Laptops' => ['HP Envy x360', 'HP Pavilion 14'],
                'Monitors' => ['HP M27fw', 'HP X24ih'],
                'Accessories' => ['HP Wireless Keyboard and Mouse', 'HP Docking Station'],
            ],
            'Motorola' => [
                'Smartphones' => ['Edge 40', 'Moto G73'],
                'Accessories' => ['Motorola TurboPower Charger', 'Moto G Stylus Pen'],
            ],
            'Acer' => [
                'Laptops' => ['Acer Swift 3', 'Acer Chromebook 514'],
                'Monitors' => ['Acer Nitro VG271', 'Acer Predator XB273'],
                'Accessories' => ['Acer Wireless Mouse', 'Acer Backpack'],
            ],
        ];

        $productsToCreate = [];

        foreach ($productMap as $brandName => $categories) {
            $brand = Brand::where('name', $brandName)->first();

            if (!$brand) continue;

            foreach ($categories as $category => $products) {
                $category = Category::where('name', $category)->first();

                if (!$category) continue;

                foreach ($products as $product) {
                    $productsToCreate[] = [
                        'name' => $product,
                        'slug' => Str::slug($product),
                        'description' => fake()->paragraph(),
                        'price' => fake()->randomFloat(2, 1000, 30000),
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'images' => null,
                        'is_active' => true,
                        'is_featured' => fake()->boolean(30),
                        'on_sale' => fake()->boolean(25),
                        'in_stock' => fake()->boolean(90),
                    ];
                }
            }
        }

        Product::insert($productsToCreate);
    }
}
