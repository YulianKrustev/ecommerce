<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('About Us | Baby Clothing & Accessories | Little Sailors Malta')]
class AboutPage extends Component
{
    public function render()
    {
        return view('livewire.about-page');
    }
}
