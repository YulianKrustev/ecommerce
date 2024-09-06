<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\ContactMessage; // Import the model
#[Title('Contact Us | Little Sailors Malta')]
class ContactForm extends Component
{
    use LivewireAlert;

    public $name;
    public $email;
    public $message;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:2000',
    ];

    public function create()
    {
        $this->validate();

        $this->message = strip_tags($this->message);

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);

        $this->reset();

        $this->alert('success', 'Thank you for contacting us!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}

