<?php

namespace App\Http\Controllers\Backend\ContentManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Models\CustomBanner;
use App\Models\MenuItem;
use App\Models\ProductCategory;
use App\Services\Frontend\BannerService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;


class CustomBannerController extends Controller
{
  // protected $imageUploadService;
  protected $model = CustomBanner::class;

  public function __construct(protected BannerService $bannerService)
  {
    $this->bannerService = $bannerService;
    view()->share('pageTitle', 'Manage Banners');
  }

  public function index(): View
  {
    $cards = config('banner');
    unset($cards['ticker_speed'], $cards['four_hover_card_title']);

    $webBanners = [];
    $appBanners = [];
    $bothBanners = [];

    foreach ($cards as $key => $banner) {
      $type = $banner['banner_type'] ?? 'web'; // fallback if not defined

      // Group banners based on their banner_type from config
      if ($type === 'web') {
        $webBanners[$key] = $banner;
      } elseif ($type === 'app') {
        $appBanners[$key] = $banner;
      } elseif ($type === 'both') {
        $bothBanners[$key] = $banner;
      }
    }

    // Fetch all custom banner data from DB by position
    $allBannerKeys = array_keys($cards);
    $customBanners = CustomBanner::whereIn('position', $allBannerKeys)->get()->groupBy('position');

    return view('backend.pages.content-manage.custom-banners.index', [
      'cardHeader'    => 'Banner List',
      'webBanners'    => $webBanners,
      'appBanners'    => $appBanners,
      'bothBanners'   => $bothBanners,
      'customBanners' => $customBanners,
    ]);
  }






  public function store(Request $request): JsonResponse
  {
    $result = $this->bannerService->storeBanner($request);

    return response()->json($result['success'] ? $result : ['success' => false, 'message' => $result['message'] ?? 'Failed']);
  }


  public function updateOrder(Request $request)
  {
    $request->validate([
      'custom_order' => 'required|array',
    ]);

    $order = $request->custom_order;
    foreach ($order as $index => $id) {
      $id = Hashids::decode($id)[0];

      $this->model::where('id', $id)->update([
        'custom_order' => $index + 1,
        'updated_by' => user('admin')->id,
      ]);
    }

    return response()->json([
      'success' => true,
      'message' => 'Order updated successfully.',
    ]);
  }



  public function saveSpeed(Request $request)
  {
    $request->validate([
      'position' => 'required|string',
      'speed' => 'required|numeric',
    ]);

    $existing = CustomBanner::where('position', 'ticker_speed')->first();

    if ($existing) {
      // Update existing settings
      $settings = json_decode($existing->settings ?? '{}', true);
      $settings['speed'] = $request->speed * 1000;

      $existing->update(['settings' => json_encode($settings)]);
    } else {
      // Insert new entry
      $settings = [
        'speed' => $request->speed * 1000
      ];

      CustomBanner::create([
        'position' => 'ticker_speed',
        'settings' => json_encode($settings),
      ]);
    }

    return response()->json(['success' => true]);
  }

  public function saveGlobalTitle(Request $request)
  {
    $request->validate([
      'position' => 'required|string',
      'title' => 'required|string',
    ]);

    $existing = CustomBanner::where('position', 'four_hover_card_title')->first();

    if ($existing) {
      // Update existing settings
      $settings = json_decode($existing->settings ?? '{}', true);
      $settings['title'] = $request->title;

      $existing->update(['settings' => json_encode($settings)]);
    } else {
      // Insert new entry
      $settings = [
        'title' => $request->title
      ];

      CustomBanner::create([
        'position' => 'four_hover_card_title',
        'settings' => json_encode($settings),
      ]);
    }

    return response()->json(['success' => true]);
  }



  public function editBanner(string $id): View
  {
    $cards = config('banner');
    $banner_config_speed = $cards['ticker_speed']['speed'] ?? null;
    $banner_four_hover_card_title = $cards['four_hover_card_title']['title'] ?? null;

    //pd($banner_config_speed);
    if (!array_key_exists($id, $cards)) {
      abort(404, 'Banner key not found.');
    }

    $customBanners = CustomBanner::where('position', '=', $id)->orderBy('custom_order')->get();
    $customBannerSpeed = CustomBanner::where('position', '=', 'ticker_speed')->first();
    $customBannerFourHoverCard = CustomBanner::where('position', '=', 'four_hover_card_title')->first();
    $firstSettings = json_decode($customBanners->first()->settings ?? '{}', true);
    $globalSpeed = $firstSettings['speed'] ?? '';
    $globalFourHoverCartTitle = $firstSettings['title'] ?? '';



    $categories =  ProductCategory::whereHas('parent.parent', function ($q) {})->where('status', 1)
      ->pluck('title', 'id')
      ->toArray();

     $parent_categories = ProductCategory::where('parent_id', 0)
    ->take(7)
    ->pluck('title', 'id')
    ->toArray();

    $menu_items = MenuItem::where('parent_id', 0)
    ->take(7)
    ->pluck('title', 'id')
    ->toArray();
      //pd($parent_categories);





    // Return the view with all necessary data
    return view('backend.pages.content-manage.custom-banners.show', [
      'cardHeader' => 'Edit Banner',
      'bannerConfig' => $cards[$id],
      'banner_config_speed' => $banner_config_speed,
      'customBannerSpeed' => $customBannerSpeed,
      'banner_four_hover_card_title' => $banner_four_hover_card_title,
      'customBannerFourHoverCard' => $customBannerFourHoverCard,
      'key' => $id,
      'customBanners' => $customBanners,
      'globalSpeed' => $globalSpeed,
      'globalFourHoverCartTitle' => $globalFourHoverCartTitle,
      'categories' => $categories,
      'parent_categories' => $parent_categories,
      'menu_items' => $menu_items,
    ]);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->model::remove($id);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    $decodedIds = $request->decodedIds(); // Already validated

    $failed = [];
    foreach ($decodedIds as $id) {
      $result = $this->model::remove($id)->getData(true);
      if ($result["success"] === false) {
        return response()->json($result);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Banner(s)'])]);
  }
}
