@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush
@push('title')
    Login | {{ config('app.name') }}
@endpush

<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <h1 id="forgot-h1" class="nav-link nav-link_underscore active">Login</h1>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                <div class="login-form">
                    <form wire:submit.prevent="save">
                        @if(session('error'))
                            <div class="mt-2 bg-red-500 text-sm text-white border-1 border-black mb-4 p-3" role="alert"
                                 tabindex="-1" aria-labelledby="hs-solid-color-danger-label">
                                <span id="hs-solid-color-danger-label" class="font-bold"> {{ session('error') }}
                            </div>
                        @endif
                        <div class="form-floating mb-3">
                            <input wire:model="email" class="form-control form-control_gray " name="email" id="email"
                                   required="" autocomplete="email"
                                   autofocus="">
                            @error('email')
                            <div
                                class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3 mb-4">
                                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor"
                                     viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                            <label for="email">Email address *</label>
                            @error('email')
                            <p class=" text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pb-3"></div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control form-control_gray " name="password"
                                   required="" wire:model="password"
                                   autocomplete="current-password">
                            @error('password')
                            <div
                                class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor"
                                     viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                            <label for="customerPasswodInput">Password *</label>
                        </div>
                        @error('password')
                        <p class=" text-xs text-red-600 mt-2" id="password-error">{{ $message }}</p>
                        @enderror
                        <button class="btn btn-primary w-100 text-uppercase" type="submit">Log In</button>

                        <div class="customer-option mt-4 text-center">

                            <a wire:navigate href="/register" class="btn-text js-show-register">Create Account</a> | <a
                                wire:navigate href="/forgot-password"
                                class="btn-text js-show-register">Forgot Password</a> | <a class="btn-text js-show-register" href="{{ url('/auth/google') }}">
                                Sign in with Google
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>



