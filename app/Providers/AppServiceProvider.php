<?php

namespace App\Providers;

use App\Contracts\CommonServiceInterface;
use App\Models\CmsPage;
use App\Models\CustomBanner;
use App\Models\MenuItem;
use App\Models\ProductCategory;
use Illuminate\Support\ServiceProvider;
use App\Services\Backend\CommonService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\SiteSetting;
use App\Models\State;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use PHPOpenSourceSaver\JWTAuth\Claims\Custom;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    $this->app->bind(CommonServiceInterface::class, CommonService::class);
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Blade::if('routeExists', function ($routeName) {
      return Route::has($routeName);
    });
    View::composer('*', function ($view) {
      $menus = ProductCategory::with(['children'])
        ->where('parent_id', 0)
        ->take(7)
        ->get(['title', 'id']);

      $pages = CmsPage::whereNull('deleted_at')
        ->where('status', 1)
        ->where('slug', '!=', 'faqs')
        ->orderBy('title', 'asc')
        ->get()
        ->keyBy('slug');
      $privacy_policy_page = CmsPage::where('slug', 'privacy-policy')->where('status', 1)->whereNull('deleted_at')->first();
      $terms_and_conditions_page = CmsPage::where('slug', 'terms-of-use')->where('status', 1)->whereNull('deleted_at')->first();

      $siteSettings = SiteSetting::pluck('value', 'key')->toArray();

      $mega_menu_banner = CustomBanner::where('position', 'mega_menu_banner')->get()->toArray();

      //pd($mega_menu_banner);

      $states = State::all();
      $view->with([
        'navMenus' => $menus,
        'siteSettings' => $siteSettings,
        'states' => $states,
        'pages' => $pages,
        'mega_menu_banner' => $mega_menu_banner,
        'privacy_policy_page' => $privacy_policy_page,
        'terms_and_conditions_page' => $terms_and_conditions_page,
      ]);
    });

    Paginator::useBootstrap();
  }
}
