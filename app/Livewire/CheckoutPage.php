<?php

namespace App\Livewire;

use Exception;
use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Mail\OrderPlaced;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

#[Title('Checkout - Ecommerce')]
class CheckoutPage extends Component
{
    public $firstName;
    public $lastName;
    public $phone;
    public $streetAddress;
    public $city;
    public $state;
    public $zipCode;
    public $paymentMethod;

    public function mount()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();

        if (count($cartItems) == 0) {
            return redirect('/products');
        }

        $lastAddress = Address::whereHas('order', function ($query) {
            $query->where('user_id', Auth::id());
        })->latest()->first();

        if ($lastAddress) {
            $this->firstName = $lastAddress->first_name;
            $this->lastName = $lastAddress->last_name;
            $this->phone = $lastAddress->phone;
            $this->streetAddress = $lastAddress->street_address;
            $this->city = $lastAddress->city;
            $this->state = $lastAddress->state;
            $this->zipCode = $lastAddress->zip_code;
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'streetAddress' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipCode' => 'required',
            'paymentMethod' => 'required',
        ]);

        $cartItems = CartManagement::getCartItemsFromCookie();
        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'BRL',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // try{
        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->grand_total = CartManagement::calculateGrandTotal($cartItems);
        $order->payment_method = $this->paymentMethod;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'brl';
        $order->shipping_amount = 0;
        $order->shipping_method = 'correios';
        $order->notes = 'Order placed by ' . Auth::user()->name;

        $address = new Address;
        $address->first_name = $this->firstName;
        $address->last_name = $this->lastName;
        $address->phone = $this->phone;
        $address->street_address = $this->streetAddress;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zipCode;

        $order->save();
        $address->order_id = $order->id;
        $address->save();
        $order->items()->createMany($cartItems);
        CartManagement::clearCartItems();

        Mail::to(request()->user())->send(new OrderPlaced($order));

        return redirect()->route('success');

        // }catch(Exception $e){            
        //     return redirect()->route('canceled');
        // }
    }

    public function render()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();
        $grandTotal = CartManagement::calculateGrandTotal($cartItems);

        return view('livewire.checkout-page', [
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal
        ]);
    }
}
