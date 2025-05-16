<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{
    public $slug;
    public $product;
    public $mainImage;


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

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => $this->product
        ]);
    }
}
