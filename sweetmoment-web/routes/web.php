<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


Route::get('/', [SessionController::class, 'index'])->name('home');;
Route::get('/login', [SessionController::class, 'auth'])->name('login');
Route::get('/news/{id}', [SessionController::class, 'news'])->name('news');
Route::post('/news/{id}/react', [SessionController::class, 'react'])->name('news.react');
Route::post('/register', [SessionController::class, 'signup'])->name('register');
Route::post('/login', [SessionController::class, 'signin'])->name('login');
Route::get('/auth/redirect', [SessionController::class, 'redirect'])->name('redirect');
Route::get('/auth/google/callback', [SessionController::class, 'callback'])->name('callback');

// Google OAuth routes
Route::get('/auth/google', [SessionController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [SessionController::class, 'callback'])->name('google.callback');

// Authentication routes
Route::post('/signin', [SessionController::class, 'signin'])->name('signin');
Route::post('/signup', [SessionController::class, 'signup'])->name('signup');
Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
Route::get('/logout', [SessionController::class, 'logout'])->name('logout');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin_vendor'])->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users Management (Admin Only)
    Route::middleware('admin_only')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/data', [AdminController::class, 'usersData'])->name('users.data');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::get('/users/{id}', [AdminController::class, 'userShow'])->name('users.show');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

        // Banners Management (Admin Only)
        Route::get('/banners', [AdminController::class, 'banners'])->name('banners.index');
        Route::get('/banners/data', [AdminController::class, 'bannersData'])->name('banners.data');
        Route::post('/banners', [AdminController::class, 'bannerStore'])->name('banners.store');
        Route::get('/banners/{id}', [AdminController::class, 'bannerShow'])->name('banners.show');
        Route::put('/banners/{id}', [AdminController::class, 'bannerUpdate'])->name('banners.update');
        Route::delete('/banners/{id}', [AdminController::class, 'bannerDestroy'])->name('banners.destroy');

        // News Management (Admin Only)
        Route::get('/news', [AdminController::class, 'news'])->name('news.index');
        Route::get('/news/data', [AdminController::class, 'newsData'])->name('news.data');
        Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
        Route::get('/news/{id}', [AdminController::class, 'newsShow'])->name('news.show');
        Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
        Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

        // Vendor Categories Management (Admin Only)
        Route::get('/vendor-categories', [AdminController::class, 'vendorCategories'])->name('vendor-categories.index');
        Route::get('/vendor-categories/data', [AdminController::class, 'vendorCategoriesData'])->name('vendor-categories.data');
        Route::post('/vendor-categories', [AdminController::class, 'vendorCategoryStore'])->name('vendor-categories.store');
        Route::get('/vendor-categories/{id}', [AdminController::class, 'vendorCategoryShow'])->name('vendor-categories.show');
        Route::put('/vendor-categories/{id}', [AdminController::class, 'vendorCategoryUpdate'])->name('vendor-categories.update');
        Route::delete('/vendor-categories/{id}', [AdminController::class, 'vendorCategoryDestroy'])->name('vendor-categories.destroy');

        // Testimonies Management (Admin Only)
        Route::get('/testimonies', [AdminController::class, 'testimonies'])->name('testimonies.index');
        Route::get('/testimonies/data', [AdminController::class, 'testimoniesData'])->name('testimonies.data');
        Route::post('/testimonies', [AdminController::class, 'testimonyStore'])->name('testimonies.store');
        Route::get('/testimonies/{id}', [AdminController::class, 'testimonyShow'])->name('testimonies.show');
        Route::put('/testimonies/{id}', [AdminController::class, 'testimonyUpdate'])->name('testimonies.update');
        Route::delete('/testimonies/{id}', [AdminController::class, 'testimonyDestroy'])->name('testimonies.destroy');
    });

    // Vendor Profile Management (Admin & Vendor)
    Route::get('/vendor-profile', [AdminController::class, 'vendorProfile'])->name('vendor-profile.index');
    Route::get('/vendor-profile/data', [AdminController::class, 'vendorProfileData'])->name('vendor-profile.data');
    Route::post('/vendor-profile', [AdminController::class, 'vendorProfileStore'])->name('vendor-profile.store');
    Route::get('/vendor-profile/{id}', [AdminController::class, 'vendorProfileShow'])->name('vendor-profile.show');
    Route::put('/vendor-profile/{id}', [AdminController::class, 'vendorProfileUpdate'])->name('vendor-profile.update');
    Route::delete('/vendor-profile/{id}', [AdminController::class, 'vendorProfileDestroy'])->name('vendor-profile.destroy');

    // Vendor Packages Management (Admin & Vendor)
    Route::get('/vendor-packages', [AdminController::class, 'vendorPackages'])->name('vendor-packages.index');
    Route::get('/vendor-packages/data', [AdminController::class, 'vendorPackagesData'])->name('vendor-packages.data');
    Route::post('/vendor-packages', [AdminController::class, 'vendorPackageStore'])->name('vendor-packages.store');
    Route::get('/vendor-packages/{id}', [AdminController::class, 'vendorPackageShow'])->name('vendor-packages.show');
    Route::put('/vendor-packages/{id}', [AdminController::class, 'vendorPackageUpdate'])->name('vendor-packages.update');
    Route::delete('/vendor-packages/{id}', [AdminController::class, 'vendorPackageDestroy'])->name('vendor-packages.destroy');

    // Portfolio Images Management (Admin & Vendor)
    Route::get('/portfolio-images', [AdminController::class, 'portfolioImages'])->name('portfolio-images.index');
    Route::get('/portfolio-images/data', [AdminController::class, 'portfolioImagesData'])->name('portfolio-images.data');
    Route::post('/portfolio-images', [AdminController::class, 'portfolioImageStore'])->name('portfolio-images.store');
    Route::get('/portfolio-images/{id}', [AdminController::class, 'portfolioImageShow'])->name('portfolio-images.show');
    Route::put('/portfolio-images/{id}', [AdminController::class, 'portfolioImageUpdate'])->name('portfolio-images.update');
    Route::post('/portfolio-images/{id}', [AdminController::class, 'portfolioImageDestroy'])->name('portfolio-images.destroy');

    // Vendor Availability Management (Admin & Vendor)
    Route::get('/vendor-availability', [AdminController::class, 'vendorAvailability'])->name('vendor-availability.index');
    Route::get('/vendor-availability/data', [AdminController::class, 'vendorAvailabilityData'])->name('vendor-availability.data');
    Route::post('/vendor-availability', [AdminController::class, 'vendorAvailabilityStore'])->name('vendor-availability.store');
    Route::get('/vendor-availability/{id}', [AdminController::class, 'vendorAvailabilityShow'])->name('vendor-availability.show');
    Route::put('/vendor-availability/{id}', [AdminController::class, 'vendorAvailabilityUpdate'])->name('vendor-availability.update');
    Route::delete('/vendor-availability/{id}', [AdminController::class, 'vendorAvailabilityDestroy'])->name('vendor-availability.destroy');

    // Bulk Availability Operations
    Route::post('/vendor-availability/bulk', [AdminController::class, 'vendorAvailabilityBulkStore'])->name('vendor-availability.bulk');
    Route::post('/vendor-availability/bulk-weekends', [AdminController::class, 'vendorAvailabilityBulkWeekends'])->name('vendor-availability.bulk-weekends');

    // Availability Stats
    Route::get('/vendor-availability/stats/summary', [AdminController::class, 'vendorAvailabilityStats'])->name('vendor-availability.stats');

    // Orders Management (Admin & Vendor - limited access for vendors)
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/data', [AdminController::class, 'ordersData'])->name('orders.data');
    Route::get('/orders/{id}', [AdminController::class, 'orderShow'])->name('orders.show');
    Route::put('/orders/{id}', [AdminController::class, 'orderUpdate'])->name('orders.update');
    // Delete orders only for admin
    Route::delete('/orders/{id}', [AdminController::class, 'orderDestroy'])->name('orders.destroy')->middleware('admin_only');

    // Reviews Management (Admin & Vendor - view only for vendors)

    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews.index');
    Route::get('/reviews/data', [AdminController::class, 'reviewsData'])->name('reviews.data');
    Route::get('/reviews/{id}', [AdminController::class, 'reviewShow'])->name('reviews.show');
    // Only admin can moderate reviews
    Route::middleware('admin_only')->group(function () {
        Route::put('/reviews/{id}', [AdminController::class, 'reviewUpdate'])->name('reviews.update');
        Route::delete('/reviews/{id}', [AdminController::class, 'reviewDestroy'])->name('reviews.destroy');
    });
});


Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/user/profile', [SessionController::class, 'getUserProfile']);
    Route::put('/user/profile', [SessionController::class, 'updateProfile']);

    // Cart routes
    Route::get('/cart', [SessionController::class, 'getCart']);
    Route::post('/cart/add', [SessionController::class, 'addToCart']);
    Route::put('/cart/{id}', [SessionController::class, 'updateCartItem']);
    Route::delete('/cart/{id}', [SessionController::class, 'removeFromCart']);

    // Order routes
    Route::get('/orders', [SessionController::class, 'getOrders']);
    Route::get('/orders/{id}', [SessionController::class, 'getOrderDetails']);
    Route::post('/checkout', [SessionController::class, 'checkout']);

    // Invoice routes
    Route::get('/orders/{id}/invoice', [SessionController::class, 'downloadInvoice'])
        ->name('invoice.download');
    Route::get('/orders/{id}/invoice/stream', [SessionController::class, 'streamInvoice'])
        ->name('invoice.stream');

    Route::post('/reviews', [SessionController::class, 'submitReview']);
    Route::get('/reviews/order/{orderId}', [SessionController::class, 'getOrderReview']);
    Route::put('/reviews/{id}', [SessionController::class, 'updateReview']);
    Route::delete('/reviews/{id}', [SessionController::class, 'deleteReview']);

    // Vendor routes (for vendor users)
    Route::middleware('role:vendor')->group(function () {
        Route::get('/vendor/profile', [SessionController::class, 'getVendorProfile']);
        Route::put('/vendor/profile', [SessionController::class, 'updateVendorProfile']);
        Route::get('/vendor/orders', [SessionController::class, 'getVendorOrders']);
        Route::put('/vendor/orders/{id}/status', [SessionController::class, 'updateOrderStatus']);
    });

    // Admin routes (for admin users)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [SessionController::class, 'getAdminDashboard']);
        Route::get('/admin/vendors', [SessionController::class, 'getAdminVendors']);
        Route::put('/admin/vendors/{id}/verify', [SessionController::class, 'verifyVendor']);
        Route::get('/admin/orders', [SessionController::class, 'getAdminOrders']);
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/api/favorites', [SessionController::class, 'getFavorites']);
    Route::post('/api/favorites', [SessionController::class, 'addFavorite']);
    Route::delete('/api/favorites/{vendorId}', [SessionController::class, 'removeFavorite']);

    // Notifications routes
    Route::get('/api/notifications', [SessionController::class, 'getNotifications']);
    Route::put('/api/notifications/{id}/read', [SessionController::class, 'markNotificationRead']);
    Route::put('/api/notifications/mark-all-read', [SessionController::class, 'markAllNotificationsRead']);

    // Messages routes
    Route::get('/api/messages', [SessionController::class, 'getMessages']);
    Route::post('/api/messages', [SessionController::class, 'sendMessage']);
    Route::put('/api/messages/{id}/read', [SessionController::class, 'markMessageRead']);

    // Vendor application route
    Route::post('/api/vendor/apply', [SessionController::class, 'applyAsVendor']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{orderId}', [PaymentController::class, 'payOrder'])->name('payment.page');
    Route::post('/payment/{orderId}/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/check/{orderId}', [PaymentController::class, 'checkPaymentStatus'])->name('payment.check');

    Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'paymentUnfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'paymentError'])->name('payment.error');
});

// Route::middleware(['auth'])->group(function () {
//     // Payment page
//     Route::get('/payment/{order}/pay', [PaymentController::class, 'payOrder'])
//         ->name('payment.pay');

//     // Create payment
//     Route::post('/payment/{order}/create', [PaymentController::class, 'createPayment'])
//         ->name('payment.create');

//     // Check payment status
//     Route::get('/payment/check/{order}', [PaymentController::class, 'checkPaymentStatus'])
//         ->name('payment.check');

//     // Get invoice status
//     Route::get('/payment/invoice/{order}', [PaymentController::class, 'getInvoiceStatus'])
//         ->name('payment.invoice');

//     // Callback routes (redirects after payment)
//     Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])
//         ->name('payment.finish');

//     Route::get('/payment/unfinish', [PaymentController::class, 'paymentUnfinish'])
//         ->name('payment.unfinish');

//     Route::get('/payment/error', [PaymentController::class, 'paymentError'])
//         ->name('payment.error');
// });

// Webhook route (no auth needed)
Route::post('/payment/notification', [PaymentController::class, 'handlePakasirWebhook'])->name('payment.notification');
