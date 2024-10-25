<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\CarouselItem;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Size;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class HomePage extends Component
{
    use LivewireAlert;
    use WithPagination;
    public $carouselImages = [];
    public $settings;
    public $categories;
    public $wishlistItems;

    public function mount()
    {
        $this->carouselImages = CarouselItem::all()->toArray();
        $this->settings = GeneralSetting::first();
        $this->categories = Category::all();
    }

    #[Title('Home Page | Little Sailors Malta')]
    public function render()
    {
        $this->wishlistItems = Wishlist::where('user_id', Auth::id())->get();
        return view('livewire.home-page',[
            'products' => Product::where('is_active', 1)->paginate(12)
        ]);
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
}
