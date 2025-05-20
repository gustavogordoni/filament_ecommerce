<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Success - Ecommerce')]
class SuccessPage extends Component
{   
    public function render()
    {
        $latestOrder = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        return view('livewire.success-page', [
            'order' => $latestOrder
        ]);
    }
}
