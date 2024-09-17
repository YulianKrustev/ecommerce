<?php

namespace App\Livewire\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Laravel\Socialite\Facades\Socialite;
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

        $this->ensureIsNotRateLimited(); // Check if the user is rate-limited

        if (!auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->rememberMe)) {
            RateLimiter::hit($this->throttleKey()); // Increment the failed login attempts
            session()->flash('error', 'Wrong email or password');
            return;
        }

        RateLimiter::clear($this->throttleKey()); // Clear the rate limit on successful login

        $this->reset();

        $this->alert('success', 'Welcome back! You have successfully logged in.', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);

        return redirect()->intended();
    }

    public function googlepage()
    {
        return socialite::driver('google')->redirect();
    }

    public function googlecallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $finduser = User::where('google_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($finduser) {
                // Update the google_id if it's not set
                if (!$finduser->google_id) {
                    $finduser->google_id = $user->id;
                    $finduser->save();
                }

                auth()->login($finduser, true);

                return redirect()->intended('/');
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt(now().$user->id),
                ]);

                auth()->login($newUser, true);

                return redirect()->intended('/home');
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong, please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }

    protected function ensureIsNotRateLimited()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            session()->flash('error', 'Too many login attempts.');
            throw ValidationException::withMessages([
                'email' => [__('Please try again in :seconds seconds.', ['seconds' => RateLimiter::availableIn($this->throttleKey())])],
            ]);
        }
    }

    protected function throttleKey()
    {
        return Str::lower($this->email) . '|' . request()->ip();
    }
}
