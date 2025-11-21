<?php

use App\Http\Controllers\Backend\Admin\{
  AdminController,
  AdminUserController,
  AuthController,
  DashboardController,
  RoleController
};
use App\Http\Controllers\Backend\{
  CountryStateController,
  MediaGalleryController,
};
use App\Http\Controllers\Backend\BlogManage\BlogController;
use App\Http\Controllers\Backend\BlogManage\PostController;
use App\Http\Controllers\Backend\ContactManage\ContactController;
use App\Http\Controllers\Backend\System\{
  CurrencyController,
  CustomerRewardController,
  DepartmentController,
  LocationController,
  ModuleController,
  PermissionController,
  SiteSettingsController,
  SubmoduleController,
  PaymentsController,
  NewStoreSetupController,
  ScratchCardRewardController,
};
use App\Http\Controllers\Backend\ContentManage\{
  BannerController,
  CMSPagesController,
  CustomBannerController,
  MenuBuilderController
};
use App\Http\Controllers\Backend\FaqManage\FaqCategoryController;
use App\Http\Controllers\Backend\FaqManage\FaqController;
use App\Http\Controllers\Backend\InventoryManage\InventoryController;
use App\Http\Controllers\Backend\OrderManage\{
  CartController,
  ChargeController,
  CustomerController,
  OrderController,
  WishlistController,
  PincodeController,
  ReturnRequestManageController,
  ShippingController
};
use App\Http\Controllers\Backend\ProductManage\{CategoryController, AttributeController, AttributeValueController, BestSellerController, CsvImportController, ProductController, ProductRecommendationController, ProductVariationController, ReviewController};

use App\Http\Controllers\Backend\Promotions\{
  CouponController,
  NewsletterController,
  PromotionController
};
use App\Http\Controllers\Backend\Reports\SalesAnalyticsController;
use App\Http\Controllers\Backend\Reports\CustomerAnalyticsController;
use App\Http\Controllers\Backend\Reports\InventoryAnalyticsController;
use App\Http\Controllers\Backend\StoreManage\StoreController;
use Illuminate\Support\Facades\Route;



//Function to Amalgamate CRUD Routes
function registerCrudRoutes(array $entities, string $namespace)
{
  foreach ($entities as $prefix => $controller) {
    Route::prefix($prefix)->controller($controller)->group(function () use ($prefix, $namespace) {
      Route::get('/', 'index')->name("$namespace.$prefix");
      Route::get('/create', 'create')->name("$namespace.$prefix.create");
      Route::post('/', 'store')->name("$namespace.$prefix.store");

      Route::middleware('decodeHashid')->group(function () use ($prefix, $namespace) {
        Route::get('/{id}/edit', 'edit')->name("$namespace.$prefix.edit");
        Route::post('/{id}/update', 'update')->name("$namespace.$prefix.update");
        Route::post('/{id}/destroy', 'destroy')->name("$namespace.$prefix.delete");
        Route::post('/{id}/togglestatus', 'toggle')->name("$namespace.$prefix.edit.status");
      });
      Route::post('/multidestroy', 'multidestroy')->name("$namespace.$prefix.delete.multiple");

      Route::get('/export', function () {
        return response()->json(1);
      })->name("$namespace.$prefix.export");
    });
  }
}

Route::group(['middleware' => 'preventBackButton', 'prefix' => 'admin'], function () {
  Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('admin.login');
    Route::post('/login', 'validateLogin')->name('admin.validateLogin');
    Route::get('/login-otp', 'loginOtp')->name('admin.login_otp');
    Route::post('/login-otp', 'validateLoginOtp')->name('admin.validateLoginOtp');
    Route::post('/resend-otp', 'resendOtp')->name('admin.resendOtp');
    Route::get('/forgot-password', 'forgotPassword')->name('admin.forgot_password');
    Route::post('/forgot-password', 'validateForgotPassword')->name('admin.forgot_password');
    Route::get('/reset-password/{token}', 'resetPassword')->name('admin.reset_password');
    Route::post('/reset-password', 'changePassword')->name('admin.reset_password');
    Route::view('/email', 'emails.design')->name('admin.email');
  });

  Route::group(['middleware' => ['isAdmin']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::middleware('isLogin')->group(function () {
      Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
      Route::post('/dashboard-data/filter', [DashboardController::class, 'filterDashboardData'])->name('admin.dashboard-data.filter');
      Route::post('admin/revenue-overview-json', [DashboardController::class, 'revenueOverviewJson'])->name('admin.revenue-overview-json');
      Route::get('/inventory-overview-data', [DashboardController::class, 'getInventoryOverviewData'])->name('admin.inventory-overview-json');
      Route::get('/inventory-export-csv', [DashboardController::class, 'exportInventoryCsv'])->name('admin.inventory-export-csv');
      Route::get('/admin/inventory-bar-chart', [DashboardController::class, 'getInventoryBarChart'])->name('admin.inventory.bar.chart');

      Route::post('admin/inventory/turnover-rate', [DashboardController::class, 'inventoryTurnoverRate'])->name('admin.inventory.turnover.rate');

      Route::get('/user-demography-chart', [DashboardController::class, 'getUserDemographyChart'])->name('admin.user.demography.chart');
      Route::get('user-demography-export', [DashboardController::class, 'exportUserDemography'])->name('admin.user.demography.export');
      Route::get('admin-users/export-stream', [DashboardController::class, 'exportStreamed'])->name('admin.users.export.stream');


      Route::view('/coming-soon', ['backend.pages.coming-soon'])->name('coming-soon');
      Route::get('/state-list/{country_id}', [CountryStateController::class, 'getStatesByCountry'])->name('admin.state_list');

      Route::controller(AdminController::class)->group(function () {
        Route::get('/profile', 'profile')->name('admin.profile');
        Route::post('/profile', 'updateProfile')->name('admin.profile');
        Route::post('/update-password', 'updatePassword')->name('admin.update_password');
        Route::post('/update-profile-picture', 'updateProfilePicture')->name('admin.upload_profile_picture');
        Route::post('/delete-profile-picture', 'deleteProfilePicture')->name('admin.delete_profile_picture');
        Route::post('/update-address', 'updateAddress')->name('admin.update_address');
      });

      Route::group(['prefix' => 'system'], function () {
        $systemEntities = [
          'modules' => ModuleController::class,
          'submodules' => SubmoduleController::class,
          'permissions' => PermissionController::class,
          'currencies' => CurrencyController::class,
          'states' => LocationController::class,
          'payments' => PaymentsController::class,
          'departments' => DepartmentController::class,
        ];

        registerCrudRoutes($systemEntities, 'admin'); // Call the registerCrudRoutes function

        Route::controller(SiteSettingsController::class)->group(function () {
          Route::get('/site-settings', 'index')->name('admin.site_settings');
          Route::post('/site-settings', 'store')->name('admin.site_settings.store');
          Route::post('/upload-logo', 'uploadLogo')->name('admin.upload-site-logo');
          Route::post('/upload-dashboard-small-logo', 'uploadDashboardSmallLogo')->name('admin.upload-dashboard-small-logo');
          Route::post('/update-mail-config', 'updateMailConfig')->name('admin.update_mail_config');
          Route::post('/update-currency', 'updateLocalCurrency')->name('admin.update_currency');
        });
        Route::controller(NewStoreSetupController::class)->group(function () {
          Route::get('/new-store-setup', 'index')->name('admin.new-store-setup');
          // Route::get('/new-store-setup/create', 'create')->name('admin.new-store-setup.create');
          Route::post('/new-store-setup/truncate-and-seed', 'truncateAndSeed')->name('admin.truncate-and-seed');
        });
      }); // end of system


      Route::group(['middleware' => 'chkPermission'], function () {
        $entitiesWithPermission = [
          'roles' => RoleController::class,
          'users' => AdminUserController::class,
          'cms-pages' => CMSPagesController::class,
          'newsletter' => NewsletterController::class,
          'product-categories' => CategoryController::class,
          'product-attributes' => AttributeController::class,
          'product-attribute-values' => AttributeValueController::class,
          'products' => ProductController::class,
          'product-variations' => ProductVariationController::class,
          'product-reviews' => ReviewController::class,
          'media-gallery' => MediaGalleryController::class,
          'coupons' => CouponController::class,
          'orders' => OrderController::class,
          'wishlist' => WishlistController::class,
          'cart' => CartController::class,
          'inventory' => InventoryController::class,
          'customers' => CustomerController::class,
          'shipment-management' => ShippingController::class,
          'pincode' => PincodeController::class,
          'return-requests' => ReturnRequestManageController::class,
          'promotion' => PromotionController::class,
          'banners' => CustomBannerController::class,
          'best-sellers' => BestSellerController::class,
          'blogs' => BlogController::class,
          'posts' => PostController::class,
          'contacts' => ContactController::class,
          'faqs' => FaqController::class,
          'faq-categories' => FaqCategoryController::class,
          'scratch-card-rewards'  => ScratchCardRewardController::class,
          'charges' => ChargeController::class,
          'customer-rewards' => CustomerRewardController::class,
          'product-recommendation' => ProductRecommendationController::class,
          'stores' => StoreController::class,
        ];
        registerCrudRoutes($entitiesWithPermission, 'admin');
        Route::controller(PromotionController::class)->group(function () {
          Route::get('/get-product-variants', 'getProductVariants')->name('admin.get-product-variants');
        });
        Route::controller(OrderController::class)->group(function () {
          Route::post('/chatbot-ask', 'chatbotAsk')->name('admin.chatbot-ask');
        });

        Route::controller(CustomBannerController::class)->group(function () {
          Route::prefix('banners')->middleware('auth:admin')->group(function () {
            Route::post('update-order', 'updateOrder')->name('admin.banners.update-order');
            Route::post('save-speed', 'saveSpeed')->name('admin.banners.save-speed');
            Route::post('save-global-title', 'saveGlobalTitle')->name('admin.banners.save-global-title');
            Route::get('{key}/edit-banner', 'editBanner')->name('admin.banners.edit-banner');
          });
        });

        Route::controller(ProductVariationController::class)->group(function () {
          Route::prefix('product-variations')->middleware('decodeHashid')->group(function () {
            Route::post('/{id}/product-variations', 'variationsByProduct')->name('admin.product-variations.variations-list-by-product');
            Route::post('/{id}/set-default-image', 'setDefaultImage')->name('admin.product-variations.set-default-image');
            Route::post('/{id}/delete-image', 'deleteImage')->name('admin.product-variations.delete-image');
          });
        });

        Route::controller(OrderController::class)->prefix('order/invoice')->group(function () {
          Route::get('download/{order}', 'downloadInvoice')->name('order.invoice.download');
          Route::get('print/{order}', 'printInvoice')->name('order.invoice.print');

          Route::middleware('decodeHashid')->group(function () {
            Route::get('{id}/download', 'downloadInvoice')->name('admin.order.invoice.download');
            Route::get('{id}/print', 'printInvoice')->name('admin.order.invoice.print');
          });
        });

        Route::group(['prefix' => 'admin/users', 'middleware' => 'decodeHashid'], function () {
          Route::post('/{id}/resend-login-mail', [AdminUserController::class, 'resendLoginMail'])
            ->name('admin.users.mail.resend');
        });

        Route::controller(CsvImportController::class)->group(function () {
          Route::get('/import-csv', 'index')->name('admin.import-csv');
          Route::get('/download-csv-template', 'downloadCsvTemplate')->name('admin.csv-template');
          Route::post('/import-csv-template', 'importCsvTemplate')->name('admin.csv-import');
          Route::get('/download-product-attributes-csv-template', 'downloadProductAttributesCsvTemplate')->name('admin.product-attributes-csv-template');
          Route::post('/import-product-attribute-csv-template', 'importProductAttributeCsvTemplate')->name('admin.product-attributes-csv-import');
          Route::get('/download-products-template', 'downloadProductCsvTemplate')->name('admin.products-csv-template');
          Route::post('/import-products-csv-template', 'importProductsCsvTemplate')->name('admin.products-csv-import');

          Route::get('/download-pincodes-template', 'downloadpincodeCsvTemplate')->name('admin.pincodes-csv-template');
          Route::post('/import-pincodes-csv-template', 'importpincodesCsvTemplate')->name('admin.pincodes-csv-import');
        });
      }); // end of chkPermission Middleware
      Route::get('/contacts/details/{id}', [ContactController::class, 'details'])->name('admin.contacts.details');
      Route::post('/customer-rewards/update-status', [CustomerRewardController::class, 'updateStatus'])->name('admin.customer-rewards.update.status');
      Route::get('/customer-reward/customer-reward-logs/{user_id?}', [CustomerRewardController::class, 'getCustomerRewardLogs'])->name('admin.customer-rewards.customer-reward-logs');

      Route::controller(MenuBuilderController::class)->prefix('menu-builder')->group(function () {
        Route::get('/', 'index')->name('admin.menu-builder');
        Route::post('save', 'save')->name('admin.menu-builder.save');
        Route::get('menu-items', 'menuItems')->name('admin.menu-builder.items');
      });

      Route::controller(SalesAnalyticsController::class)->prefix('sales-analytics')->group(function () {
        Route::get('/', 'index')->name('admin.sales-analytics');
        Route::post('sale-status-json', 'getSaleStatus')->name('admin.reports.sale-status-json');
        Route::post('top-selling-products-json', 'getTopSellingProducts')->name('admin.reports.top-selling-products-json');
      });

      Route::controller(CustomerAnalyticsController::class)->prefix('customer-analytics')->group(function () {
        Route::get('/', 'index')->name('admin.customer-analytics');
        Route::post('new-vs-returning-customers', 'getNewVsReturningCustomers')->name('admin.reports.new-vs-returning-customers');
        Route::get('customer-order-list/{user_id}', 'getOrderListByCustomer')->name('admin.reports.customer-order-list');

        // Route::get('customer-order-list/{user_id}/export', 'exportCsvValue')->name('admin.reports.customer-order-list.export');
      });

      Route::controller(InventoryAnalyticsController::class)->prefix('inventory-analytics')->group(function () {
        Route::get('/', 'index')->name('admin.inventory-analytics');
        Route::get('inventory-order-list/{user_id}', 'getOrderListByCustomer')->name('admin.reports.inventory-order-list');
      });
    }); // end of isLogin Middleware
  }); // end of isAdmin Middleware
}); // end of admin group
