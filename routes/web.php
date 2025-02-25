<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::match(['get', 'post'], 'admin/cart', [ProductController::class, 'cart'])->name('cart');

Route::get('/dashboard', function () {
    return view('dashboard');

    
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

      
    
});

require __DIR__ . '/auth.php';
Route::middleware(['auth','roles:admin'])->group(function(){
    // Admin Dashboard
    Route::get('admin/dashboard', [AdminController::class, 'AdminDashboard'])->name("admin.dashboard");
    //LOGOUT
      Route::get('admin/logout', [AdminController::class, 'Adminlogout'])->name("admin.logout"); // Move logout route here


       // Product Management
        Route::match(['get', 'post'], 'admin/views', [ProductController::class, 'views'])->name('admin.views');
        Route::match(['get', 'post'], 'admin/cart', [ProductController::class, 'cart'])->name('cart');
        Route::match(['get', 'post'], 'add-to-cart/{id}', [ProductController::class, 'addToCart'])->name('admin.add.to.cart');
        Route::patch('update-cart', [ProductController::class, 'update'])->name('update.cart');
        Route::delete('/cart/remove/{id}', [ProductController::class, 'removeFromCart'])->name('admin.cart');
        Route::delete('remove-from-cart', [ProductController::class, 'remove'])->name('remove.from.cart');
        Route::post('/checkout', [ProductController::class, 'checkout'])->name('admin.checkout');

    // Product CRUD operations
        Route::match(['get', 'post'], '/products/product', [ProductController::class, 'views'])->name('products.product');
        Route::match(['get', 'post'], '/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::match(['get', 'post'], '/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::match(['get', 'post'], '/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');


        Route::get('/admin/names', [UserController::class, 'showUserNames'])->name('admin.names');

        Route::put('/admin/update', [UserController::class, 'update'])->name('admin.update');

        // Routes for managing users
        Route::put('/admin/update', [UserController::class, 'update'])->name('admin.update');

        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.delete');
        Route::match(['get', 'post'], '/users', [UserController::class, 'store'])->name('users.store'); // For adding new user

});


// User routes
Route::middleware(['auth', 'roles:user'])->prefix('user')->group(function () {
    // User Dashboard
    Route::get('user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

    // View products
    Route::match(['get', 'post'], 'user/product', [UserController::class, 'views'])->name('user.product');

     Route::get('/logout', [UserController::class, 'UserLogout'])->name('user.logout');


    // Add to cart
    Route::get('cart/add/{id}', [UserController::class, 'addToCart'])->name('cart.add');
   Route::get('add-to-cart/{id}', [UserController::class, 'addToCart'])->name('user.add.to.cart');
   Route::delete('remove-from-cart', [UserController::class, 'remove'])->name('user.remove.from.cart');

    Route::delete('/product/{id}', [UserController::class, 'destroy'])->name('product.destroy');

    // View user cart
    Route::get('user/cart', [UserController::class, 'cart'])->name('user.cart');

    // Checkout route for users
    Route::post('checkout', [UserController::class, 'checkout'])->name('user.checkout');
});


   

