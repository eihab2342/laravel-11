<?php

use App\Http\Controllers\AdminControl;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;




// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\{PaymentController, RegisteredUserController, ProductController, UserController, CartController, CouponController, OrderController, CategoryController, ImageController, PackageController, AdminControl, DashboardController, ProfileController, CheckOutController};

// ---------------- Payment Routes ----------------
Route::prefix('payment')->group(function () {
    Route::get('/callback', [PaymentController::class, 'handleCallback']);
    Route::get('/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');
    Route::get('/response', [PaymentController::class, 'handleResponse'])->name('payment.response');
});

// ---------------- OTP Routes ----------------
Route::prefix('register')->group(function () {
    Route::post('/sendOtp', [RegisteredUserController::class, 'sendOtp'])->name('register.sendOtp');
    Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('register.verifyOtp');
});

Route::get('/verify-otp', [RegisteredUserController::class, 'showOtpForm'])->name('verify.otp');
Route::post('/resend-otp', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');

// ---------------- User Routes ----------------
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/', [ProductController::class, 'DisplayDataToUser'])->middleware('clear.discount')->name('/');
    Route::get('/user', [ProductController::class, 'DisplayDataToUser'])->middleware('clear.discount')->name('user');
    Route::get('/category/{category_id}', [ProductController::class, 'ShowProductsToUser'])->name('category.details');
    Route::get('/product/{name}', [ProductController::class, 'ShowToUser'])->name('product.details');
    Route::get('/package/{name}', [PackageController::class, 'ShowPackageToUser'])->name('package.details');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/contact-us', function () {
        return view('user.contact-us');
    })->name('contact');
});

// ---------------- Cart & Checkout Routes ----------------
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart');
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

    Route::prefix('cart')->group(function () {




        // Route::get('/', [CartController::class, 'index'])->name('user.cart');
        // Route::post('/add', [CartController::class, 'addToCart']);
        // Route::post('/remove', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
        // Route::post('/update', [CartController::class, 'updateCart']);
        // Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');
    });
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
    Route::post('/checkout', [PaymentController::class, 'processPayment'])->name('order.place');
    Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout');
    Route::get('/checkout/success', function () {
        return view('user.checkout-success');
    })->name('checkout.success');
    Route::get('/MyOrders', [OrderController::class, 'UserOrders'])->name('MyOrders');
});

// ---------------- Admin Routes ----------------
Route::prefix('admin')->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products', [ProductController::class, 'index'])->name('products');  // عرض جميع المنتجات
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
    Route::get('admin/products/import', [ProductController::class, 'showImportForm'])->name('products.import');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/notification/{notification}', [OrderController::class, 'showId'])->name('orders.show.notification');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::get('/category/products/{category_id}', [CategoryController::class, 'showProducts'])->name('categories.products');
    Route::get('/category/import/{categoryName}', [CategoryController::class, 'showImportFormForCategory'])->name('categories.import');
    Route::post('/category/import', [CategoryController::class, 'importproducts'])->name('categories.import.store');
    Route::resource('coupons', CouponController::class);
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::resource('packages', PackageController::class)->except(['index']);
    Route::get('/control', [AdminControl::class, 'index'])->name('control');
    Route::post('/control/store', [AdminControl::class, 'store'])->name('control.store');
    Route::delete('/control/{id}', [AdminControl::class, 'destroy'])->name('control.destroy');
    Route::delete('/images/{id}', [ImageController::class, 'deleteImage'])->name('carousel.image.delete');
    Route::get('/add-image', [ImageController::class, 'index'])->name('add-image');
    Route::post('/store-image', [
        ImageController::class,
        'store'
    ])->name('images.store');
});

// ---------------- Order Management ----------------
Route::put('/order/{id}/update-status', [OrderController::class, 'updateOrderStatus']);
Route::post('/orders/{id}', [OrderController::class, 'showId'])->name('order.order');
Route::get('accepted_orders', function () {
    return view('admin.orders.accepted_orders');
})->name('accepted_orders');
Route::get('orders', function () {
    return view('admin.orders.orders_summary');
})->name('orders');


// ---------------- Profile Routes ----------------
Route::middleware('auth')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// ---------------- Dashboard Route ----------------
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// ---------------- Notifications ----------------
Route::middleware('auth')->prefix('notifications')->group(function () {
    Route::post('/read/{id}', function ($id) {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.markOneAsRead');

    Route::post('/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.markAllAsRead');
});

// ---------------- Team Work ----------------
Route::get('/team-work', function () {
    return view('team-work');
})->name('team-work');

































// Route::get('/payment/callback', [PaymentController::class, 'handleCallback']);
// // --------------------------------------------
// Route::get('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment/webhook');
// Route::get('/payment/response', [PaymentController::class, 'handleResponse'])->name('payment/response');




// Route::post('/register/sendOtp', [RegisteredUserController::class, 'sendOtp'])->name('register.sendOtp');
// Route::get('/verify-otp', [RegisteredUserController::class, 'showOtpForm'])->name('verify.otp');
// Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('register.verifyOtp');
// Route::post('/resend-otp', [RegisteredUserController::class, 'resendOtp'])->name('resend.otp');

// Route::get('/', [ProductController::class, 'DisplayDataToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('/');
// Route::get('/', [UserController::class, 'IndexToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('/');

// Route::get('/user', [ProductController::class, 'DisplayDataToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user',
//     'clear.discount', // إضافة Middleware هنا
// ])->name('user');
// // -----------------------------------------------------------------

// Route::get('/category/{category_id}', [ProductController::class, 'ShowProductsToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('category.details');

// // 
// Route::get('/product/{name}', [ProductController::class, 'ShowToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('product.details');

// Route::get('/package/{name}', [PackageController::class, 'ShowPackageToUser'])->middleware([
//     'auth',
//     'verified',
//     'role:user'
// ])->name('package.details');

// Route::get('/search', [ProductController::class, 'search'])->name('search');

// Route::middleware('auth')->group(function () {
//     Route::get('/cart', [CartController::class, 'index'])->name('user.cart');
//     Route::post('/add-to-cart', [CartController::class, 'addToCart']);
//     Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('remove-from-cart');
//     Route::post('/update-cart', [CartController::class, 'updateCart']);
//     Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
//     Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
//     Route::post('orders/{id}', [OrderController::class, 'showId'])->name('order.order');
// });


// Route::post('/checkout', [PaymentController::class, 'processPayment'])->name('order.place');
// Route::get('/checkout/success', function () {
//     return view('user.checkout-success');
// })->name('checkout.success');
// Route::get('/MyOrders', [OrderController::class, 'UserOrders'])->name('MyOrders');

// //Admin
// Route::prefix('admin')->group(function () {
//     // 
//     Route::get('/products', [ProductController::class, 'index'])->name('products');  // عرض جميع المنتجات
//     // -------------------------
//     Route::get('/products/add', [ProductController::class, 'create'])->name('products.create');  // عرض نموذج إضافة منتج
//     // -------------------------
//     Route::post('/products', [ProductController::class, 'store'])->name('products.store');  // إضافة منتج جديد
//     // -------------------------
//     Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
//     // -------------------------
//     Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');  // إضافة منتج جديد
//     // -------------------------
//     Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');  // تحديث منتج معين
//     // -------------------------
//     Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');
//     // -------------------------
//     Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
//     // -------------------------
// Route::get('/products/import', [ProductController::class, 'showImportForm'])->name('products.import');
// Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.store');
//     // -------------------------
//     Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
//     Route::get('/orders/{id}/notification/{notification}', [OrderController::class, 'showId'])->name('orders.show.notification');
//     // -------------------------
//     Route::get('accepted_orders', function () {
//         return view('admin.orders.accepted_orders');
//     })->name('accepted_orders');
//     // -------------------------
//     Route::get('orders', function () {
//         return view('admin.orders.orders_summary');
//     })->name('orders');
//     // -------------------------
//     Route::get('categories', [CategoryController::class, 'index'])->name('categories');
//     Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
//     Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
//     Route::get('categories/edit/{id}/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
//     Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
//     Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
//     Route::delete('categories/deleteImage/{id}', [CategoryController::class, 'deleteImage'])->name('categories.image.delete');
//     // -------------------------
//     Route::get('/category/products/{category_id}', [CategoryController::class, 'showProducts'])
//         ->name('categories.products');
//     // -------------------------
//     Route::get('/category/import/productsTo/{categoryName}', [CategoryController::class, 'showImportFormForCategory'])
//         ->name('categories.import');
//     // -------------------------
//     Route::post('/coategory/import-products/import', [CategoryController::class, 'importproducts'])->name('categories.import.store');
//     // -------------------------
//     Route::get('/add-image', [
//         ImageController::class,
//         'index'
//     ])->name('add-image');
//     // --------------
//     Route::post('/store-image', [
//         ImageController::class,
//         'store'
//     ])->name('images.store');
//     // --------------
//     Route::delete('/delete-image{id}', [
//         ImageController::class,
//         'deleteImage'
//     ])->name('carousel.image.delete');
//     // ---------------------

//     Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
//     Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
//     Route::post('/packages/store', [PackageController::class, 'store'])->name('packages.store');
//     Route::get('/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
//     Route::put('/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
//     Route::delete('/packages/delete/{id}', [PackageController::class, 'destroy'])->name('admin.packages.destroy');
//     // ----------------------------
//     Route::get('/control', [AdminControl::class, 'index'])->name('control');
//     Route::post('/control/store', [AdminControl::class, 'store'])->name('control.store');
//     Route::delete('/control/destroy{id}', [AdminControl::class, 'destroy'])->name('control.destroy');
//     // });

//     // Route::prefix('admin')->group(function () {
//     Route::resource('admin/coupons', CouponController::class);
// }); // 

// // update order status route => Admin
// Route::put('/order/{id}/update-status', [OrderController::class, 'updateOrderStatus']);

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     // 
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     // 
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     // 
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//     // 
//     Route::get('/checkout', [CheckOutController::class, 'index'])->name('checkout');
// });

// Route::get('contact-us', function () {
//     return view('user.contact-us');
// })->middleware('role:user')->name('contact');


// Route::post('/notifications/read/{id}', function ($id) {
//     $notification = Auth::user()->notifications()->find($id);
//     if ($notification) {
//         $notification->markAsRead();
//     }
//     return redirect()->back();
// })->name('notifications.markOneAsRead');

// Route::post('/notifications/read-all', function () {
//     Auth::user()->unreadNotifications->markAsRead();
//     return redirect()->back();
// })->name('notifications.markAllAsRead');


require __DIR__ . '/auth.php';
