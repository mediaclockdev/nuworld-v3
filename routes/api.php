<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Frontend\V1\{
  BannerController,
  AuthenticationController,
  CartController,
  CategoryController,
  HomePageController,
  OrderController,
  ProductController,
  ReviewController,
  UserController
};

// Banner APIs
Route::prefix('v1')->group(function () {

  Route::get('/app-launch-banners', [BannerController::class, 'appLaunchBanners'])->name('app-launch-banners');
  Route::get('/home-landing', [HomePageController::class, 'index'])->name('home-landing');
  Route::get('/footer-menu', [HomePageController::class, 'footerMenus'])->name('footer-menus');
  Route::get('/blogs', [HomePageController::class, 'blogs'])->name('blogs');
  Route::get('/blog-list', [HomePageController::class, 'blogList'])->name('blog-list');
  Route::get('/limited-time-deal', [HomePageController::class, 'limitedTimeDeal'])->name('limited-time-deal');

  // Product routes
  Route::controller(ProductController::class)->group(function () {
    Route::get('/best-selling-products', 'bestSellingProducts')->name('best-selling-products');
    Route::get('/latest-products/{limit?}', 'getLatestProducts');
    Route::get('/products/{productID}', 'getProductVariants')->name('product.variants');
    Route::get('/product/{sku}', 'getProductBySku')->name('product.slug');
    //Route::get('/product-search', 'searchProduct')->name('product.search');
    Route::get('/products-with-filter', 'filterProducts')->name('product.filter');
    Route::post('/search-by-product-items', 'searchByProductItems')->name('search.by.product.items');
    Route::post('/apply-pincode', 'applyPincode')->name('apply.pincode');
    Route::get('/products-autocomplete',  'autocomplete')->name('products.autocomplete');
    Route::get('/products-search', 'search')->name('products.search');
    Route::get('/attribute-options', 'attributeOptions')->name('attribute.options');
    Route::post('/products/resolve-variant', 'resolveVariant')->name('products.resolve-variant'); // optional
    Route::post('/try-on', 'tryOn')->name('try-on'); // optional

  });
  // Product routes

  // Category routes
  Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getCategories')->name('categories');
    Route::get('/product-listing/{slug}', 'getCategoryBySlug')->name('category.slug');
  });
  // Category routes


  Route::controller(AuthenticationController::class)->group(function () {
    // Public routes
    Route::post('/login', 'login')->name('api.login');
    Route::post('/verify-otp', 'verifyOtp')->name('api.verify-otp');
    Route::post('/resend-otp', 'resendOtp')
      ->middleware('customThrottle:3,1')
      ->name('api.resend-otp');
    Route::get('/auth/google/redirect', 'redirectToGoogle');
    Route::get('/auth/google/callback', 'handleGoogleCallback');
  });
  // Protected routes with JWT middleware
  Route::middleware('decodeJwtToken')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {

      Route::post('/logout', 'logout')->name('api.logout');
      Route::post('/refresh-token', 'refreshToken')->name('api.refresh-token');
    });

    Route::controller(UserController::class)->group(function () {
      Route::get('/user-profile', 'fetchUserData')->name('fetch-user-profile');
      Route::post('/user-profile', 'updateUserData')->name('update-user-profile');
      Route::post('/user-profile-image', 'updateUserImage')->name('update-user-image');
      Route::post('/user-avtar-image', 'updateAvtarImage')->name('update-avtar-image');
      Route::post('/user-orders', 'updateUserData')->name('update-user-profile');
      Route::get('/user-address', 'fetchUserAddress')->name('fetch-user-address');
      Route::post('/user-address', 'updateUserAddress')->name('update-user-address');
      Route::post('/remove-address', 'removeAddress')->name('remove-user-address');
    });
    // Orders Routes
    Route::controller(OrderController::class)->group(function () {
      Route::get('/my-orders', 'myOrders')->name('my.orders');
      Route::get('/order-details/{order_number}', 'orderDetails')->name('order.details');
      Route::get('/order/invoice/download/{order}', 'downloadInvoice')->name('order-invoice.download');
      Route::get('/change-payment-method', 'changePaymentMethod')->name('change.payment.method');
      Route::get('/coupon-list', 'couponList')->name('coupon.list');
      Route::post('/coupon/apply', 'couponApply')->name('order.coupon.apply');
      Route::get('/coupon/remove', 'couponRemove')->name('order.coupon.remove');
      Route::post('/checkout/process', 'process')->name('checkout.process');
      Route::post('/checkout/confirm', 'updateCODOrder')->name('checkout.confirm');
      Route::post('/checkout/stripe/init', 'stripeInitApi')->name('checkout.stripe');
      Route::post('/checkout/stripe/confirm', 'stripeConfirmApi')->name('checkout.stripeconfirm');
    });
    // Orders Routes
    // My Cart/Wishlist Routes
    Route::controller(CartController::class)->group(function () {
      Route::get('/my-cart', 'myCart')->name('my.cart');
      Route::get('/my-wishlist', 'myWishlist')->name('my.wishlist');
      Route::post('/remove-from-cart', 'removeFromCart')->name('remove.from.cart');
      Route::post('/remove-from-wishlist', 'removeFromWishlist')->name('remove.from.wishlist');
      Route::post('/move-to-cart', 'addToCart')->name('add.to.cart');
      Route::post('/move-to-wishlist', 'addToWishlist')->name('move.to.wishlist');
      Route::post('/move-or-remove-wishlist', 'moveOrRemoveWishlist')->name('move.or.remove.wishlist');
      Route::post('/update-quantity', 'updateQuantity')->name('updateQuantity');
    });
    // My Cart/Wishlist Routes
    // My Review Routes
    Route::controller(ReviewController::class)->group(function () {
      Route::post('/submit-review', 'submitReview')->name('user.submit-review');
      Route::get('/get-review', 'reviewDetails')->name('review.details');
      // Route::get('/my-wishlist', 'myWishlist')->name('my.wishlist');
      // Route::post('/remove-from-cart', 'removeFromCart')->name('remove.from.cart');
      // Route::post('/move-to-cart', 'addToCart')->name('add.to.cart');
      // Route::post('/move-to-wishlist', 'addToWishlist')->name('move.to.wishlist');
      // Route::post('/update-quantity', 'updateQuantity')->name('updateQuantity');
    });
    // My Review Routes

  });
});
