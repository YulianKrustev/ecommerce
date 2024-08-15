<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Register')]
class RegisterPage extends Component
{
    public $name;
    public $email;
    public $password;

    public function save() {
        $this->validate([
            'name' => 'required|max:100|min:2',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|max:60',
        ]);

        $user = User::Create([
           'name' => $this->name,
           'email' => $this->email,
           'password' => Hash::make($this->password),
        ]);

        auth()->login($user);

        $this->reset('name');
        $this->reset('email');
        $this->reset('password');

        return redirect()->intended();
    }
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
