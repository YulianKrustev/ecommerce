<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    static public function addItemToCart($product_id, $quantity = 1, $selectedSize = null)
    {
        $cart_items = self::getCartItemsFromCookie();
        $size = Size::where('id', $selectedSize)->first();
        $existing_item_key = null;

        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size->name) {
                $existing_item_key = $key;
                break;
            }
        }

        if ($existing_item_key !== null) {
            $cart_items[$existing_item_key]['quantity'] += $quantity;

        } else {
                $cart_items[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'size' => $size->name
                ];

        }

        self::addCartItemsToCookie($cart_items);
        return count($cart_items);
    }

    static public function removeCartItem($product_id, $cart_items, $size)
    {
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size) {
                unset($cart_items[$key]);
            }
        }

        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    static public function addCartItemsToCookie($cart_items)
    {
        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 30);
    }

    static public function clearCartItems()
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    static public function getCartItemsFromCookie()
    {
        $cart_items = json_decode(Cookie::get('cart_items'), true);

        if (!$cart_items) {
            $cart_items = [];
        }

        return $cart_items;
    }

    static public function incrementQuantityToCartItem($product_id, $cart_items, $size, $availableSizeQuantity = null)
    {
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size) {

                if ($availableSizeQuantity !== null && $availableSizeQuantity >= ($cart_items[$key]['quantity'] + 1)) {
                    $cart_items[$key]['quantity']++;

                    $cart_items[$key]['total_units_price'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_price'];
                } else {
                    return false;
                }
            }
        }
        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    static public function decrementQuantityToCartItem($product_id, $cart_items, $size)
    {
        foreach ($cart_items as $key => $item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size) {
                if ($cart_items[$key]['quantity'] > 1) {
                    $cart_items[$key]['quantity']--;

                    $cart_items[$key]['total_units_price'] = $cart_items[$key]['quantity'] * $cart_items[$key]['unit_price'];
                }
            }
        }
        self::addCartItemsToCookie($cart_items);

        return $cart_items;
    }

    static public function calculateTotalPrice($items)
    {
        $items = collect($items);

        return $items->sum(function ($item) {
            return $item['unit_price'] * $item['quantity'];
        });
    }

    static public function fetchCartItems()
    {
        $productsId = CartManagement::getCartItemsFromCookie();

        $productQuantities = collect($productsId)
            ->groupBy(function ($item) {
                return $item['product_id'] . '-' . $item['size'];
            })
            ->map(function ($group) {
                return [
                    'product_id' => $group->first()['product_id'],
                    'size' => $group->first()['size'],
                    'total_quantity' => $group->sum('quantity'),
                ];
            });

        $cart_items = Product::select('id', 'name', 'price', 'images', 'slug', 'color_id', 'on_sale', 'on_sale_price')
            ->whereIn('id', $productQuantities->pluck('product_id')->unique())
            ->get()
            ->flatMap(function ($item) use ($productQuantities) {
                // Filter the product quantities by product_id and map them
                return $productQuantities->filter(function ($pq) use ($item) {
                    return $pq['product_id'] === $item->id;
                })->map(function ($pq) use ($item) {
                    return [
                        'product_id' => $item->id,
                        'name' => $item->name,
                        'unit_price' => $item->on_sale ? $item->on_sale_price : $item->price,
                        'images' => $item->images,
                        'slug' => $item->slug,
                        'color' => $item->color->name,
                        'quantity' => $pq['total_quantity'],
                        'size' => $pq['size'],
                        'total_units_price' => ($item->on_sale ? $item->on_sale_price : $item->price) * $pq['total_quantity'],
                    ];
                });
            })
            ->toArray();

        return $cart_items;
    }
}
