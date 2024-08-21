<?php

namespace App\Livewire\Partials;

use App\Models\Category;
use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Component;

class Footer extends Component
{
    public function render()
    {
        $settings = GeneralSetting::first();
        $categories = Category::whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->take(5)
            ->get();

        return view('livewire.partials.footer', [
            'settings' => $settings,
            'categories' => $categories,
        ]);
    }
}
