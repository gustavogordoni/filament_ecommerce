<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;

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
