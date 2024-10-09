<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Category;
use App\Models\Color;
use App\Models\ContactMessage;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Wishlist;

#[Title('Products - Little Sailors Malta')]
class ProductsPage extends Component
{
    use LivewireAlert;
    use WithPagination;

    #[Url]
    public $selected_categories = [];
    #[Url]
    public $in_stock;
    #[Url]
    public $on_sale;
    #[Url]
    public $price_range = 500;
    #[Url]
    public $sort = 'latest';
    #[Url]
    public $selectedColor = null;

    #[Url]
    public $selectedSize = null;

    public $temp_selected_categories = [];

    public $temp_in_stock;

    public $temp_on_sale;

    public $temp_selectedColor = null;

    public $temp_selectedSize = null;


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

    public function removeFromWishlist($removeId)
    {
        Wishlist::where('user_id', Auth::id())->where('product_id', $removeId)->delete();

        $this->alert('success', 'Product removed successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function selectColor($colorId)
    {
        $this->selectedColor = $colorId;
    }

    public function tempSelectColor($colorId)
    {
        $this->temp_selectedColor = $colorId;
    }

    public function selectSize($sizeId)
    {
        $this->selectedSize = $sizeId;
    }

    public function tempSelectSize($sizeId)
    {
        $this->temp_selectedSize = $sizeId;
    }

    public function applyModalFilters()
    {
        $this->selected_categories = $this->temp_selected_categories;
        $this->in_stock = $this->temp_in_stock;
        $this->on_sale = $this->temp_on_sale;
        $this->selectedColor = $this->temp_selectedColor;
        $this->selectedSize = $this->temp_selectedSize;

    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1)->with('sizes');

        if ($this->in_stock) {
            $productQuery->where('in_stock', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->selectedColor) {
            $productQuery->where('color_id', $this->selectedColor);
        }

        if (!empty($this->selected_categories)) {
            $productQuery->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->selected_categories);
            });
        }

//        if ($this->price_range) {
//            $productQuery->whereBetween('price', [0, $this->price_range]);
//        }

        if ($this->selectedSize) {
            $productQuery->whereHas('sizes', function ($query) {
                $query->where('size_id', $this->selectedSize);
            });
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        } else {
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(12),
            'colors' => Color::all(),
            'sizes' => Size::all(),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
            'wishlistItems' => Wishlist::where('user_id', Auth::id())->get(),
        ]);
    }
}
