<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - eCommerce')]
class ProductsPage extends Component
{
    use WithPagination;
    public function render()
    {
        $productQuerry = Product::query()->where('is_active', 1);
        return view('livewire.products-page', [
            'products' => $productQuerry->paginate(2),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}
