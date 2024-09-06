<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Blog | Little Sailors Malta')]
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
