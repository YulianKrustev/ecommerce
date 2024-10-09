<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use App\Models\Product;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $total_count = 0;

    public $searchTerm = ''; // The input term for the search
    public $searchResults = []; // Store search results
    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        self::updateCartCount();
    }
    #[On('update-cart-count')]
    public function updateCartCount()
    {
        $this->total_count = collect(CartManagement::getCartItemsFromCookie())
            ->sum('quantity');
    }

    public function updatedSearchTerm()
    {
        // If the search term is longer than 1 character, fetch results
        if (strlen($this->searchTerm) > 1) {
            $this->searchResults = Product::where('name', 'like', '%' . $this->searchTerm . '%')
                ->take(5) // Limit the number of results
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function render()
    {
        return view('livewire.partials.navbar', [
            'settings' => GeneralSetting::first()
        ]);
    }
}
