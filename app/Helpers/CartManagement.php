<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{

    // add item to cart with qty
    static public function addItemToCartWithQty($productId, $quantity)
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
            $cartItems[$existingItem]['quantity'] = $quantity;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Product::where('id', $productId)->first(['id', 'name', 'price', 'images']);

            if ($product) {
                $mainImage = is_iterable($product->images) && count($product->images) > 0
                    ? url('storage', $product->images[0])
                    : asset('img/product-image.png');

                $cartItems[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'image_url' => $mainImage,
                    'quantity' => $quantity,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price * $quantity
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
