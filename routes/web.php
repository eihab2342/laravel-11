<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
// -------------------------

Route::post('/register/sendOtp', [RegisteredUserController::class, 'sendOtp'])->name('register.sendOtp');
Route::get('/verify-otp', [RegisteredUserController::class, 'showOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('register.verifyOtp');
Route::post('/resend-otp', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');



Route::get('/', function () {
    $products = Product::with('images')->get(); // جلب المنتجات مع الصور
    return view('user', compact('products'));
})->middleware([
    'auth',
    'verified',
    'role:user'
])->name('/');
// 
Route::get('/user', function () {
    $products = Product::with('images')->get(); // جلب المنتجات مع الصور
    return view('user', compact('products'));
})->middleware([
    'auth',
    'verified',
    'role:user'
])->name('user');
// 
// Route::get('/product/{name}', function ($name) {
//     $product = Product::with('images')->where('name', $name)->firstOrFail(); // جلب المنتج بناءً على الاسم
//     return view('user.product-details', compact('product')); // تمرير المتغير الصحيح إلى الـ View
// })->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('product.details');
Route::get('/product/{name}', [ProductController::class, 'ShowToUser'])->middleware([
    'auth',
    'verified',
    'role:user'
])->name('product.details');


Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart');
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
});
// checkout ROutes
Route::middleware('auth', 'role:user')->group(function () {
    Route::get('/checkout', function () {
        return view('user.check-out');
    })->name('checkout');
});





//Admin
Route::prefix('admin')->group(function () {
    // 
    Route::get('/products', [ProductController::class, 'index'])->name('products');  // عرض جميع المنتجات
    // -------------------------
    Route::get('/products/add', function () {
        return view('admin.products.add-products');
    })->name('products.create');  // عرض نموذج إضافة منتج
    // -------------------------
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');  // إضافة منتج جديد
    // -------------------------
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    // -------------------------
    Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');  // إضافة منتج جديد
    // -------------------------
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');  // تحديث منتج معين
    // -------------------------
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
    // -------------------------
    Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    // -------------------------
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.order');
    // -------------------------
    Route::get('accepted_orders', function () {
        return view('admin.orders.accepted_orders');
    })->name('accepted_orders');
    // -------------------------
    Route::get('orders', function () {
        return view('admin.orders.orders_summary');
    })->name('orders');
    // -------------------------
});

// 

// update order status route => Admin
Route::put('/order/{id}/update-status', [OrderController::class, 'updateOrderStatus']);



Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // 
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // 
});

Route::get('contact-us', function () {
    return view('user.contact-us');
})->middleware('role:user')->name('contact');



require __DIR__ . '/auth.php';
