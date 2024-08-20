<?php

namespace App\Livewire\Partials;

use Joaopaulolndev\FilamentGeneralSettings\Models\GeneralSetting;
use Livewire\Component;

class Footer extends Component
{
    public function render()
    {
        $settings = GeneralSetting::first();
        return view('livewire.partials.footer', compact('settings'));
    }
}
