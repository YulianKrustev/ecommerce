<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class CategoriesPage extends Component
{
    #[Title('Categories')]
    public function render()
    {
        $categories = Category::where('is_active', 1)->orderBy("name", "ASC")->get();
        return view('livewire.categories-page', compact('categories'));
    }
}
