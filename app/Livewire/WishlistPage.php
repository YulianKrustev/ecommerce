<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\ContactMessage;
use App\Models\Size;
use App\Models\Wishlist as WishlistModel;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Wishlist | Little Sailors Malta')]
class WishlistPage extends Component
{
    use LivewireAlert;
    use WithPagination;

    public function removeFromWishlist($wishlistId)
    {
        WishlistModel::where('id', $wishlistId)->delete();

        $this->alert('success', 'Product removed successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

        $this->resetPage();
    }

    public function addToCart($product_id, $size)
    {
        $total_count = CartManagement::addItemToCart($product_id, $quantity = 1, $size);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Product added to the cart successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function notifyMeWhenAvailable($product_id, $size)
    {
        if (!Auth::id()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $sizeName = Size::where('id', $size)->first()->name;

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
        $wishlistItems = WishlistModel::where('user_id', Auth::id())
            ->with('product')
            ->paginate(12);

        return view('livewire.wishlist-page', [
            'wishlistItems' => $wishlistItems,
        ]);
    }
}
