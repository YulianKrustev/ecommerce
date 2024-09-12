<div class="mb-14 pb-14">
    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <h1 class="nav-link nav-link_underscore active">
                    Register
                </h1>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-register" role="tabpanel" aria-labelledby="register-tab">
                <div class="register-form">
                    <form wire:submit.prevent="save">
                        <div class="form-floating mb-3">
                            <input class="form-control form-control_gray" type="text" id="name" wire:model="name" required="" autocomplete="name"
                                   autofocus="">
                            @error('name')
                            <div class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor"
                                     viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                            <label for="name">Name</label>
                        </div>
                        @error('name')
                        <p class=" text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                        @enderror
                        <div class="pb-3"></div>
                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control form-control_gray " name="email" wire:model="email" required=""
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

                        <div class="pb-3"></div>

                        <div class="form-floating mb-3">
                            <input wire:model="password" id="password" type="password" class="form-control form-control_gray " name="password" required=""
                                   autocomplete="new-password">
                            @error('password')
                            <div class=" absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                <svg class="h-5 w-5 text-red-500" width="16" height="16" fill="currentColor"
                                     viewBox="0 0 16 16" aria-hidden="true">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                </svg>
                            </div>
                            @enderror
                            <label for="password">Password *</label>
                        </div>
                        @error('password')
                        <p class=" text-xs text-red-600 mt-2" id="email-error">{{ $message }}</p>
                        @enderror
                        <div class="form-floating mb-3">
                            <input wire:model="password_confirmation" id="password-confirm" type="password" class="form-control form-control_gray"
                                   name="password_confirmation" required="" autocomplete="new-password">
                            <label for="password">Confirm Password *</label>
                        </div>

                        <div class="flex items-center mb-3 pb-2">
                            <p class="m-0">Your personal data will be used to support your experience throughout this website, to
                                manage access to your account, and for other purposes described in our privacy policy.</p>
                        </div>

                        <button class="btn btn-primary w-100 uppercase" type="submit">Register</button>

                        <div class="customer-option mt-4 text-center">
                            <span class="text-secondary">Have an account?</span>
                            <a wire:navigate href="/login" class="btn-text js-show-register">Login to your Account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
