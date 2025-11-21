<?php

use App\Http\Controllers\Frontend\{AuthController, CartController, CategoryController, CheckoutController, CmsPageController, CustomerOrderReturnController, HomeController, LocationController, OrderController, ProductController, ReviewController, UserController, WishlistController, BlogController, ContactUsController, StoreController};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes (Guests Only)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
  Route::get('/signin', 'showLoginRegisterForm')->middleware('throttle:3,1')->name('signuplogin');
  Route::post('/signin', 'signin');
  Route::post('/resend-otp', 'resendotp')->name('resendotp');
  Route::post('/verify-otp', 'verifyotp')->name('verifyotp');
  Route::get('/login', 'showLoginRegisterForm')->name('login');
  // Route::post('/login', 'signin')->middleware('throttle:5,1');

  // Socialite
  Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
  Route::get('/auth/google/callback', 'handleGoogleCallback');

  // Password Reset
  Route::get('/forgot-password', 'showForgotPasswordForm')->name('password.request');
  Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
  Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
  Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::get('/refresh-captcha', function () {
  return response()->json([
    'captcha' => captcha_src('flat')
  ]);
})->middleware('throttle:5,1')->name('captcha.refresh');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Landing Page
Route::controller(HomeController::class)->group(function () {
  Route::get('/', 'index')->name('home');
  Route::get('/home/collections', 'getFilteredCollections')->name('home.collections');
  Route::get('/home/get-variant-details', 'getVariantDetails')->name('home.getVariantDetails');
  Route::post('/subscribe-email', 'subscribeEmail')->name('subscribeEmail');
  Route::get('/email/confirm/{hash}', 'confirmEmail')->name('email.confirm');
});

// Categories & Products (Public Access)
Route::controller(CategoryController::class)->group(function () {
  Route::get('/categories', 'index')->name('category.list');
  Route::get('/category/{slug}', 'bySlug')->name('category.slug');
});

Route::controller(ProductController::class)->group(function () {

  Route::get('/product/{variant}', 'show')->name('product.show');
  Route::post('/load-more-reviews', 'loadMoreReviews')->name('reviews.load-more');
  Route::get('/products/{product}', 'search')->name('search.product');
  Route::get('/search', 'search')->name('base.search');
});

// Cart
Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
  Route::get('/', 'index')->name('index');
  Route::get('/count', 'count')->name('count');
  Route::get('/is-in-cart', 'isInCart')->name('is-in-cart');
  Route::post('/add', 'add')->name('add');
  Route::post('/remove', 'remove')->name('remove');
  Route::post('/save-for-later', 'saveForLater')->name('saveForLater');
  Route::post('/move-to-cart', 'moveToCart')->name('moveToCart');
  Route::post('/update-quantity', 'updateQuantity')->name('updateQuantity');
  Route::post('/clear', 'clear')->name('clear');
});

Route::post('/check-pincode', [LocationController::class, 'checkPincode'])->name('pincode.check');
Route::post('/set-location', [LocationController::class, 'set'])->name('location.set')->middleware('throttle:3,1');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
Route::get('/blog/{slug}', [BlogController::class, 'blogDetails'])->name('blog.details');
Route::post('/conatct-us/save', [ContactUsController::class, 'saveContactInformation'])->name('contact-us.store');

Route::get('/stores', [StoreController::class, 'index'])->name('stores');
Route::get('/stores/search', [StoreController::class, 'search'])->name('stores.search');



// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
  Route::controller(UserController::class)->group(function () {
    Route::get('/profile', 'profile')->name('profile');
    Route::get('/edit-profile', 'editProfile')->name('user.profile.edit');
    Route::post('/update-profile', 'updateProfile')->name('user.update-profile');
    Route::get('/saved-payment', 'savedPayment')->name('saved-payment');
    Route::get('/address', 'address')->name('address');

    Route::post('/address/create-or-update', 'createOrUpdateAddress')->name('user.create-or-update-address');
    Route::post('/address/update-user-address', 'updateUserAddress')->name('user.update-address');
    Route::get('/address/list', 'getAddressList')->name('address.list');

    Route::post('/address/set-default', 'setDefaultAddress')->name('address.set-default');
    Route::post('/set-selected-address', 'setSelectedAddress')->name('address.set-selected-address');

    Route::post('/remove-address', 'deleteAddress')->name('user.delete-address');
    Route::get('/change-password', 'changePassword')->name('change-password');
    Route::post('/update-password', 'updatePassword')->name('user.update-password');
    Route::get('/profile-details', 'profileDetails')->name('profile-details');
    Route::get('/reward', 'reward')->name('reward');
  });

  Route::get('/ajax/load-address-modal', [LocationController::class, 'getAddressModal'])->name('load.address.modal');

  Route::controller(ReviewController::class)->group(function () {
    Route::post('/submit-review', 'submitReview')->name('user.submit-review');
    Route::get('/user/review', 'getReview')->name('user.get-review');
  });

  Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

  Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'index')->name('checkout');
    Route::post('/checkout/process', 'process')->name('checkout.process');
    Route::get('/checkout/{type}/{order_number}', 'nextProcess')->name('checkout.next-process');
    Route::post('/checkout/confirm', 'updateCODOrder')->name('checkout.confirm');
    Route::get('/list-of-coupons', 'couponList')->name('checkout.list-of-coupons');
    Route::post('/coupon/apply', 'couponApply')->name('checkout.coupon.apply');
    Route::post('/coupon/remove', 'couponRemove')->name('checkout.coupon.remove');
    Route::post('/change-default-address', 'changeDefaultAddress')->name('user.change-default-address');
    Route::post('/pay', 'processPayment')->name('pay');

    Route::post('/paypal/create', 'paypalCreate')->name('paypal.create');
    Route::post('/paypal/capture', 'paypalCapture')->name('paypal.capture');
  });

  Route::controller(OrderController::class)->group(function () {
    Route::get('/order-confirmation/{order_number}', 'confirmation')->name('order.confirmation');
    Route::get('/order-details/{order_number}', 'orderDetails')->name('order.details');
    Route::post('/order-return', 'orderReturn')->name('order.return');
    Route::post('/order-return-save', 'orderReturnSave')->name('order.return.save');
    Route::get('/order/invoice/download/{order}', 'downloadInvoice')->name('order-invoice.download');
    Route::get('/orders', 'index')->name('orders');
    Route::get('/order/{id}', 'show')->name('order.show');
    Route::get('/order-tracking/{order_number}', 'tracking')->name('order.tracking');
    Route::get('/order/reward/{order_number}', 'getReward')->name('order.getReward');
  });

  Route::post('/order-requests', [CustomerOrderReturnController::class, 'store'])->name('order-requests.store');
});


 Route::get('/{slug}', [CmsPageController::class, 'cmsPage'])->name('cms.page');
