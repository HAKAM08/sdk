<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SlideshowController;
use App\Http\Controllers\Admin\AdSpaceController;
use App\Http\Controllers\FishingMapController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

// Language switching removed - English only site

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Fishing Map route
Route::get('/fishing-map', [FishingMapController::class, 'index'])->name('fishing-map.index');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Review routes
Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('categories.show');

// Content routes
Route::get('/fishing-tips', [ContentController::class, 'index'])->name('content.index');
Route::get('/fishing-tips/{slug}', [ContentController::class, 'show'])->name('content.show');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Cart routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');

// Protected cart routes
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
});

// Checkout routes
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

// User Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\UserController::class, 'dashboard'])->name('user.dashboard');
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/orders', [\App\Http\Controllers\UserController::class, 'orders'])->name('user.orders');
    Route::get('/orders/{id}', [\App\Http\Controllers\UserController::class, 'showOrder'])->name('user.orders.show');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    
    // Users
    Route::resource('users', AdminUserController::class);
    
    // Orders
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store', 'destroy']);
    
    // Content (Fishing Tips)
    Route::resource('content', AdminContentController::class);
    
    // Slideshows
    Route::resource('slideshows', SlideshowController::class);
    
    // Ad Spaces
    Route::resource('adspaces', AdSpaceController::class);
});

// Debug Routes (only in local environment)
if (app()->environment('local') || app()->environment('development')) {
    Route::prefix('debug')->middleware(['web'])->group(function () {

        
        // Translation debug route removed - English only site
        
        // Simple email test
        Route::get('/email-test', function () {
            try {
                $user = auth()->user() ?? \App\Models\User::first();
                if (!$user) {
                    return 'No users found in the system to test email.';
                }
                
                \Illuminate\Support\Facades\Mail::raw('This is a test email from Fishing Tackle Shop.', function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Test Email');
                });
                
                return 'Test email sent to ' . $user->email . '. Check your email inbox or the mail logs.';
            } catch (\Exception $e) {
                return 'Error sending email: ' . $e->getMessage() . '<br><pre>' . $e->getTraceAsString() . '</pre>';
            }
        });
        
        // Advanced email testing
        Route::get('/email-test-ui', [\App\Http\Controllers\DebugController::class, 'index']);
        Route::get('/email', [\App\Http\Controllers\DebugController::class, 'testEmail']);
    });
}
