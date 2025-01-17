@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush
@push('title')
    Forgot Password | {{ config('app.name') }}
@endpush

<div id="forgot-container" class="min-h-96">
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <h1 id="forgot-h1" class="nav-link nav-link_underscore active ">
                    Forgot password?
                </h1>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel"
                 aria-labelledby="register-tab">
                <div class="register-form">
                    <form wire:submit.prevent="save">
                        @if(session('success'))
                            <div class="mt-2 bg-green-500 text-sm text-white rounded-lg mb-4 p-4" role="alert"
                                 tabindex="-1" aria-labelledby="hs-solid-color-danger-label">
                                <span id="hs-solid-color-danger-label" class="font-bold"> {{ session('success') }}
                            </div>
                        @endif
                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control form-control_gray " name="email"
                                   wire:model="email" required=""
                                   autocomplete="email">
                            @error('email')
                            <div class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor"
                                     viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                            <label for="email">Email address *</label>
                        </div>
                        @error('email')
                        <p class=" text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                        @enderror
                        <button class="btn btn-primary w-100 uppercase" type="submit">Reset password</button>
                        <div class="customer-option mt-4 text-center">
                            <span class="text-secondary">Remember your password?</span>
                            <a wire:navigate href="/login" class="btn-text js-show-register">Login to your Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


