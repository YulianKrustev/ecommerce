<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Search | Little Sailors Malta')]
class SearchPage extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $results = Product::where('name', 'like', '%' . $this->search . '%')
            ->paginate(12);

        // If no results and search is less than 1 character, create an empty paginator
        if (strlen($this->search) < 1) {
            $results = new LengthAwarePaginator([], 0, 12);
        }

        return view('livewire.search-page', [
            'results' => $results,
        ]);
    }
}
