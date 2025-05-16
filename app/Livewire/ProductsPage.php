<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Products - Ecommerce')]
class ProductsPage extends Component
{
    use WithPagination;

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        $products = $productQuery->paginate(6)->through(
            function ($product) {
                $product->main_image = is_iterable($product->images) && count($product->images) > 0
                    ? url('storage', $product->images[0])
                    : "https://cdn-icons-png.flaticon.com/512/2652/2652218.png";

                return $product;
            }
        );

        return view('livewire.products-page', [
            'products' => $products,
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
