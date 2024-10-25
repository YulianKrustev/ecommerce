<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Size;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class ProductDetailPage extends Component
{
    use LivewireAlert;

    public $slug;
    public $quantity = 1;
    public $selectedSize = null;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function increaseQty()
    {
        $this->quantity++;
    }

    public function decreaseQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function selectSize($sizeId)
    {
        $this->selectedSize = $sizeId;
    }

    public function addToWishlist($productId)
    {
        if (Auth::check()) {

            $wishlist = Wishlist::firstOrCreate([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);

            $this->alert('success', 'Product added to the wishlist!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    public function removeFromWishlist($removeId)
    {
        Wishlist::where('user_id', Auth::id())->where('product_id', $removeId)->delete();

        $this->alert('success', 'Product removed successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function addToCart($product_id)
    {
        if (is_null($this->selectedSize)) {
            $this->resetErrorBag('selectedSize');
            $this->addError('selectedSize', 'Please select a size.');
            return;
        }

        $sizeName = Size::where('id', $this->selectedSize)->first()->name;

        $availableSizeQuantity = Product::where('id', $product_id)->first()->sizes->where('size_id', $this->selectedSize)->first()->quantity;

        $cart_items = CartManagement::fetchCartItems();

        $cart_items = $this->checkAvailbaleSizeQuantity($product_id, $cart_items, $sizeName, $availableSizeQuantity);

        if ($cart_items) {
            $this->resetErrorBag('selectedSize');
            $this->addError('selectedSize', "There are only $availableSizeQuantity units available in this size.");
            return;
        }

        CartManagement::addItemToCart($product_id, $this->quantity, $this->selectedSize);

        $this->dispatch('update-cart-count')->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

        $this->selectedSize = null;
    }

    public function notifyMeWhenAvailable($product_id)
    {
        if (!Auth::id()) {
            return redirect('/login');
        }

        if (is_null($this->selectedSize)) {
            $this->resetErrorBag('selectedSize');
            $this->addError('selectedSize', 'Please select a size.');
            return;
        }

        $user = Auth::user();

        $sizeName = Size::where('id', $this->selectedSize)->first()->name;

        ContactMessage::create([
            'name' => $user->name,
            'email' => $user->email,
            'message' => "Product ID: $product_id, Size: $sizeName"
        ]);

        $this->alert('success', 'You will be notified when this product is back in stock!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::with(['sizes.size'])->where('slug', $this->slug)->firstOrFail(),
            'wishlistItems' => Wishlist::where('user_id', Auth::id())->get(),
        ]);
    }

    public function checkAvailbaleSizeQuantity($product_id, $cart_items, $size, $availableSizeQuantity = null)
    {
        foreach ($cart_items as $item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size) {
                if ($availableSizeQuantity >= $item['quantity'] + $this->quantity) {
                    return false;
                } else {
                    return true;
                }
            }
        }

        if ($availableSizeQuantity >= $this->quantity) {
            return false;
        }

        return true;
    }
}
