<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

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
