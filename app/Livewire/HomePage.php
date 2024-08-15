<?php

namespace App\Livewire;

use App\Models\CarouselItem;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    public $carouselImages = [];
    public function mount() {
        $this->carouselImages = CarouselItem::all()->toArray();
    }
    #[Title('Home Page - eCommerce')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
