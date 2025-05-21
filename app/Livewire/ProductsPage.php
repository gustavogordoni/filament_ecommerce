<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;


#[Title('Products - Ecommerce')]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $price_range = 30000;

    #[Url]
    public $sort = 'latest';

    public function addToCart($productId)
    {
        $totalCount = CartManagement::addItemToCartWithQty($productId, 1);
        $this->dispatch('update-cart-count', totalCount: $totalCount)->to(Navbar::class);

        $this->alert(
            'success',
            'Product added to the cart successfully!',
            [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]
        );
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('price', [0, $this->price_range]);
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        }

        if ($this->sort == 'price') {
            $productQuery->orderBy('price');
        }

        $products = $productQuery->paginate(9)->through(
            function ($product) {
                $product->main_image = is_iterable($product->images) && count($product->images) > 0
                    ? url('storage', $product->images[0])
                    : asset('img/product-image.png');

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
