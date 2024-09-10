<?php

use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\CancelPage;
use App\Livewire\SuccessPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CategoriesPage;
use App\Livewire\OrderDetailPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\Auth\ForgotPasswordPage;

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);
Route::get('/cart', CartPage::class);

// Slug is for the info about the procut at the url
Route::get('/products', ProductsPage::class);
Route::get('products/{slug}', ProductDetailPage::class);



// Group of routes related to the user when he's not logged in
Route::middleware('guest')->group(function (){
  //Login receives a name to make redirection easier. It will redirect you if you access any route in the auth middleware
  Route::get('/login', LoginPage::class)->name('login');
  //This receives a name because like reset route, it facilitates the work with mailtrap
  Route::get('/forgot', ForgotPasswordPage::class)->name('password.request');
  Route::get('/register', RegisterPage::class);
  //this need a name and token so mailtrap is able to redirect the user to reset password page
  Route::get('/reset/{token}', ResetPasswordPage::class)->name('password.reset');
});


// Group of routes related to the user when he's logged in. 'auth' does the authentication automatically through laravel.
Route::middleware('auth')->group(function (){
  Route::get('/logout', function(){
    auth()->logout();
    return redirect('/');
  });
  Route::get('/checkout', CheckoutPage::class);
  Route::get('/my-orders', MyOrdersPage::class);
  Route::get('/my-orders/{order_id}', OrderDetailPage::class)->name('my-orders.show');
  Route::get('/success', SuccessPage::class)->name('success');
  Route::get('/cancel', CancelPage::class)->name('cancel');
});