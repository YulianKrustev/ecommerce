<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Post::paginate(12);

        return view('livewire.blog', [
            'posts' => $posts,
        ]);
    }
}
