<?php

namespace App\Livewire;

use App\Models\CarouselItem;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomePage extends Component
{
    public $carouselImages = [];
    public function mount() {
        $this->carouselImages = CarouselItem::all()->toArray();
    }
    #[Title('Home Page - Little Sailors Malta')]
    public function render()
    {
        $settings = GeneralSetting::first();

        return view('livewire.home-page', ['settings' => $settings]);
    }
}
