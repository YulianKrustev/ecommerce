<?php

namespace App\Livewire\Auth;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public $rememberMe = true;

    use LivewireAlert;

    public function save()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email|max:100',
            'password' => 'required|min:8|max:60',
        ]);

        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->rememberMe)) {
            session()->flash('error', 'Wrong email or password');
            return;
        }

        $this->reset();

        $this->alert('success', 'You are now logged in!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
