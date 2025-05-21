<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Title('My Orders - Ecommerce')]
class MyOrdersPage extends Component
{
    use WithPagination;

    public function render()
    {
        $myOrders = Order::where('user_id', Auth::id())->latest()->paginate(5);        

        return view('livewire.my-orders-page', [
            'orders' => $myOrders
        ]);
    }
}
