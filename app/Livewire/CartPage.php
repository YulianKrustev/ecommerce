<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - eCommerce')]
class CartPage extends Component
{
    public $cart_items = [];
    public $total_units_price;

    public function mount()
    {
        $this->fetchCartItems();
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }

    private function fetchCartItems()
    {
        $productsId = CartManagement::getCartItemsFromCookie();

        // Group the product IDs by product_id and sum the quantities
        $productQuantities = collect($productsId)
            ->groupBy('product_id')
            ->map(function ($group) {
                return $group->sum('quantity');
            });

        // Fetch the product details from the database, selecting only the fields you need
        $this->cart_items = Product::select('id', 'name', 'price', 'images')  // Select only the fields you need
        ->whereIn('id', $productQuantities->keys()->toArray()) // Fetch the products based on the product IDs
        ->get() // Execute the query
        ->map(function ($item) use ($productQuantities) {  // Map over the results
            // Return an array with the fields you need and the computed quantity
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'images' => $item->images,
                'quantity' => $productQuantities->get($item->id, 0),
                'total_units_price' => $item->price * $productQuantities->get($item->id, 0),  // Compute total price for the quantity
            ];
        })
            ->toArray(); // Convert the collection to an array
    }

    public function increaseQty($product_id)
    {

        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }
    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }
}
