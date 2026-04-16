<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\BecomeVendorController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\StoreCategoryController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')
    ->group(function () {
        // Home
        Route::get('/home', [HomeController::class, 'index'])->name('home.index');

        // Contact
        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

        // Become a vendor (public form: name + email)
        Route::post('/become-vendor', [BecomeVendorController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('become-vendor.store');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('stores/{store_slug}/products/{product_slug}', [ProductController::class, 'show'])->name('products.show');


        // Stores
        Route::prefix('stores')->group(function () {
            Route::get('/', [StoreController::class, 'index'])->name('stores.index');
            Route::get('/{slug}', [StoreController::class, 'show'])->name('stores.show');
        });

        // Store Categories
        Route::get('/store-categories', [StoreCategoryController::class, 'index'])->name('store-categories.index');

        //authenticated routes
        Route::middleware('auth:sanctum')->group(function () {

            // Customer Profile
            Route::get('/', [CustomerController::class, 'show'])->name('customer.show');
            Route::put('/profile', [CustomerController::class, 'update'])->name('customer.update');

            // Customer Orders (grouped by checkout / payment)
            Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders.index');

            // Addresses
            Route::prefix('addresses')->group(function () {
                Route::get('/', [AddressController::class, 'index'])->name('customer.addresses.index');
                Route::post('/', [AddressController::class, 'store'])->name('customer.addresses.store');
                Route::get('/{id}', [AddressController::class, 'show'])->name('customer.addresses.show');
                Route::put('/{id}', [AddressController::class, 'update'])->name('customer.addresses.update');
                Route::delete('/{id}', [AddressController::class, 'destroy'])->name('customer.addresses.destroy');
            });

            // Checkout
            Route::post('/checkout', [CheckoutController::class, 'store'])->name('customer.checkout');

            // Wishlist
            Route::get('/wishlist', [WishlistController::class, 'index'])->name('customer.wishlist.index');
            Route::post('/wishlist', [WishlistController::class, 'store'])->name('customer.wishlist.store');
            Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('customer.wishlist.destroy');
            Route::post('/wishlist/sync', [WishlistController::class, 'sync'])->name('customer.wishlist.sync');
        });
    });


Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');
