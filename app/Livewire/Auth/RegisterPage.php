<?php

namespace App\Livewire\Auth;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Register')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    use LivewireAlert;

    public function save() {
        $this->validate([
            'name' => 'required|max:100|min:2',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|max:60|confirmed',
        ]);

        $user = User::Create([
           'name' => $this->name,
           'email' => $this->email,
           'password' => Hash::make($this->password),
        ]);

        auth()->login($user, true);

        $this->reset();

        Mail::to($user->email)->send(new WelcomeEmail($user));

        $this->alert('success', 'You are now logged in!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
