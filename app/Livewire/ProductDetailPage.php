<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\LivewireAlert;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{
    use LivewireAlert;
    
    public $slug;
    public $product;
    public $mainImage;
    public $quantity = 1;


    public function mount($slug)
    {
        $this->slug = $slug;
        $this->product = Product::where('slug', $slug)->firstOrFail();

        if (is_iterable($this->product->images) && count($this->product->images) > 0) {
            $this->mainImage = url('storage', $this->product->images[0]);
        } else {
            $this->mainImage = "https://cdn-icons-png.flaticon.com/512/2652/2652218.png";
        }
    }

    public function increaseQty()
    {
        $this->quantity++;
    }

    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart($productId)
    {
        $totalCount = CartManagement::addItemToCartWithQty($productId, $this->quantity);
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
        return view('livewire.product-detail-page', [
            'product' => $this->product
        ]);
    }
}
