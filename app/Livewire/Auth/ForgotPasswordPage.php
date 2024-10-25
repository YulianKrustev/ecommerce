<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPasswordPage extends Component
{
    public $email;

    public function save()
    {
        $this->validate([
           'email' => 'required|email|exists:users,email|max:100',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', 'Password reset link has been sent to your email address!');
            $this->reset('email');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
