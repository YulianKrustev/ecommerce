<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\ContactMessage;
class ContactForm extends Component
{
    use LivewireAlert;

    public $name;
    public $email;
    public $message;
    #[Url]
    public $order;
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

        return $this->redirect('/');
    }

    public function render()
    {
        if(Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }

        if($this->order){
            $this->message = "We are sorry to hear that you want to return a product. Please provide the necessary details below to help us process your return as quickly as possible." . PHP_EOL .
                PHP_EOL . "Order ID: $this->order" . PHP_EOL .
                "Product SKU: " . PHP_EOL .
                "(Please provide the SKU of the product you wish to return)" . PHP_EOL .
                "Product size: " . PHP_EOL .
                "Product quantity: " . PHP_EOL .
                "Reason for Return: " . PHP_EOL .
                "(Let us know why you're returning this product, so we can assist you better)" . PHP_EOL .
                PHP_EOL . "Once we receive your request, our team will get back to you with further instructions on how to complete your return.";
        }
        return view('livewire.contact-form', ['message' => $this->message]);
    }
}
