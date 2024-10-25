<?php

use App\Livewire\AboutPage;
use App\Livewire\AccountDetailPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Blog;
use App\Livewire\BlogDetailPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\ContactForm;
use App\Livewire\GiftCardPage;
use App\Livewire\HomePage;
use App\Livewire\MyAccountPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\OrderDetailPage;
use App\Livewire\PaymentsPage;
use App\Livewire\PrivacyPolicyPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ReturnPolicyPage;
use App\Livewire\SearchPage;
use App\Livewire\SizeGuidePage;
use App\Livewire\SuccessPage;
use App\Livewire\TermsAndConditionsPage;
use App\Livewire\WishlistPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/', HomePage::class)->name('home');
//Route::get('/categories', CategoriesPage::class);
Route::get('/shop', ProductsPage::class);
Route::get('/cart', CartPage::class)->name('cart.route.name');
Route::get('/wishlist', WishlistPage::class);
Route::get('/about', AboutPage::class);
Route::get('/blog', Blog::class);
Route::get('/contact', ContactForm::class);
Route::get('/privacy-policy', PrivacyPolicyPage::class);
Route::get('/terms-and-conditions', TermsAndConditionsPage::class);
Route::get('/size-guide', SizeGuidePage::class);
Route::get('/gift-card', GiftCardPage::class);
Route::get('/payments', PaymentsPage::class);
Route::get('/return-policy', ReturnPolicyPage::class);
Route::get('/contact/return', ContactForm::class);
Route::get('/blog/{slug}', BlogDetailPage::class);



Route::middleware('guest')->group(function () {
    Route::get('/login', LoginPage::class)->name('login');
    Route::get('/auth/google',[LoginPage::class, 'googlepage']);
    Route::get('/auth/google/callback',[LoginPage::class, 'googlecallback']);
    Route::get('/register', RegisterPage::class);
    Route::get('/forgot-password', ForgotPasswordPage::class)->name('password.request');
    Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function (){
    Route::get('/logout', function (){
        auth()->logout();
        return redirect('/');
    });
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrdersPage::class);
    Route::get('/my-orders/{order_id}', OrderDetailPage::class)->name('my-orders.show');
    Route::get('/success', SuccessPage::class)->name('success');
    Route::get('/cancel', CancelPage::class)->name('cancel');
    Route::get('/my-account', MyAccountPage::class);
//    Route::get('/account-details', AccountDetailPage::class);
});

Route::get('/{slug}', ProductDetailPage::class)->name('product.show');
