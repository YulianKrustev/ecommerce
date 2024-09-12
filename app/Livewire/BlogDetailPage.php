<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Post | Little Sailors Malta')]
class BlogDetailPage extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.blog-detail-page', [
            'post' => Post::where('slug', $this->slug)->firstOrFail()
        ]);
    }
}
