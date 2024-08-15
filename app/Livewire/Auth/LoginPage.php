<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class LoginPage extends Component
{
    public $email;
    public $password;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:100',
            'password' => 'required|min:8|max:60',
        ]);

        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Wrong email or password');
            return;
        }

        $this->reset('email');
        $this->reset('password');

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
