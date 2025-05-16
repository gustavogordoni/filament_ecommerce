<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{

    // add item to cart
    static public function addItemToCart($productId)
    {
        $cartItems = self::getCartItemsFromCookie();
        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }

        if ($existingItem !== null) {
            $cartItems[$existingItem]['quantity']++;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Product::where('id', $productId)->first(['id', 'name', 'price', 'images']);

            if ($product) {
                $cartItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'images' => $product->images[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price
                ];
            }
        }

        self::addCartItemsToCookie($cartItems);
        return count($cartItems);
    }

    // remove item from cart
    static public function removeCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($cartItems[$key]);
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    // add cart items to cookie
    static public function addCartItemsToCookie($cartItems)
    {
        Cookie::queue('cartItems', json_encode($cartItems, 60 * 24 * 30));
    }

    // clear cart items frm cookie
    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cartItems'));
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie()
    {
        $cartItems = json_decode(Cookie::get('cartItems'), true);

        if (!$cartItems) {
            $cartItems = [];
        }

        return $cartItems;
    }

    // increment item quantity
    static public function incrementQuantityToCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $cartItems[$key]['quantity']++;
                $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
            }
        }

        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    }

    // decrement item quantity
    static public function decrementQuantityToCartItem($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                if ($cartItems[$key]['quantity'] > 1) {
                    $cartItems[$key]['quantity']--;
                    $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                }
            }
        }

        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    }


    // calculate grand total
    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}