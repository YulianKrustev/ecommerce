<?php

namespace App\Helpers;

use App\Livewire\CartPage;
use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    // add item to cart
    static public function addItemToCart($product_id, $quantity = 1)
    {
        $cart_items = self::getCartItemsFromCookie();
        $existing_item_key = null;

        // Check if the item is already in the cart
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $existing_item_key = $key;
                break;
            }
        }

        if ($existing_item_key !== null) {
            // Update the quantity for the existing item
            $cart_items[$existing_item_key]['quantity'] += $quantity;
        } else {
            // Add a new item to the cart
            $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
            if ($product) {
                $cart_items[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                ];
            }
        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    // remove item from cart
    static public function removeCartItem($product_id, $cart_items)
    {

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    // add cart items to cookie
    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    // clear cart items from cookie
    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // get all cart items from cookie
    static public function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);
        if (!$cart_items) {
            $cart_items = [];
        }

        return $cart_items;
    }

    // increment item quantity
    static public function incrementQuantityToCartItem($product_id, $cart_items)
    {

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                // Increment the quantity
                $cart_items[$key]['quantity']++;

                // Update the total price for this item
                $cart_items[$key]['total_units_price'] = $cart_items[$key]['quantity'] * $cart_items[$key]['price'];
            }
        }

        // Save the updated cart items back to the cookie or session
        self::addCartItemsToCookie($cart_items);

        // Return the updated array of cart items
        return $cart_items;
    }

    // decrement item quantity
    static public function decrementQuantityToCartItem($product_id, $cart_items)
    {

        foreach ($cart_items as $key => $item) {
            if ($item['id'] == $product_id) {
                if ($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;

                    // Correctly update the total_units_price for the specific item
                    $cart_items[$key]['total_units_price'] = $cart_items[$key]['quantity'] * $cart_items[$key]['price'];
                }   else {
                    unset($cart_items[$key]);
                }
            }
        }

        // Save the updated cart items back to the cookie or session
        self::addCartItemsToCookie($cart_items);

        // Return the updated array of cart items
        return $cart_items;
    }

    // calculate total
    static public function calculateTotalPrice($items)
    {
        $items = collect($items);

        // Check the type of the first item
//        if ($items->isNotEmpty()) {
//            $firstItem = $items->first();
//
//        }

        return $items->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
}
