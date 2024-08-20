<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public $total_count = 0;

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
    public function render()
    {
        return view('livewire.partials.navbar', ['settings' => GeneralSetting::first()]);
    }
}
