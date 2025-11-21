<?php

namespace App\Services\Frontend;


use App\Models\CustomBanner;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class BannerService
{

  public function __construct() {}

  // 'categoryBanner' => $this->getBanner('category_page_banner', true),
  // 'categorySaleBlock' => $this->getBanner('category_sale_block', true),
  // 'categoryHotDealsBlock' => $this->getBanner('hot_deals_category_banner', false, 'custom_order'),

  /**
   * Fetch banner(s) dynamically by position.
   *
   * @param string $position
   * @param bool $single
   * @param string|null $orderBy
   * @return \Illuminate\Support\Collection|\App\Models\CustomBanner|null
   */
  public static function getBanner(string $position, bool $single = false, ?string $orderBy = null)
  {
    $query = CustomBanner::where('position', $position);

    if ($orderBy)
      $query->orderBy($orderBy, 'asc');

    return $single ? $query->first() : $query->get();
  }

  public function storeBanner(Request $request)
  {
    //pd($request->all());
    $position = $request->input('position');
    $bannerConfig = config("banner.$position");

    if (!$bannerConfig) {
      return ['success' => false, 'message' => 'Invalid banner position'];
    }

    $rules = $this->buildValidationRules($bannerConfig);
    $messages = $this->buildValidationMessages();

    // if ($request->filled('coordinates.hotspots')) {
    //   $hotspots = json_decode($request->input('coordinates.hotspots'), true);
    //   $settings['coordinates']['hotspots'] = $hotspots;
    // }


    $validated = $request->validate($rules, $messages);

    $settings = $this->prepareSettings($request, $validated, $bannerConfig);

    // Save category IDs from select2
    if ($request->has('option')) {
      $settings['options'] = array_map('intval', $request->input('option', []));
    }

    // --- Handle Hotspots (JSON) ---
    if ($request->filled('coordinates.hotspots')) {
      $hotspots = json_decode($request->input('coordinates.hotspots'), true);
      $settings['coordinates']['hotspots'] = $hotspots;
    }

    $settingsJson = json_encode($settings);
    $id = $request->id ? Hashids::decode($request->id)[0] : null;

    $banner = CustomBanner::updateOrCreate(
      ['id' => $id],
      [
        'title' => $validated['title'] ?? '',
        'sub_title' => $validated['sub_title'] ?? '',
        'position' => $position,
        'settings' => $settingsJson,
        'custom_order' => $request->custom_order ?? 1,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? CustomBanner::find($id)->created_by : user('admin')->id
      ]
    );

    if (!$id) {
      $banner->custom_order = $banner->id;
      $banner->save();
    }

    return ['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Banner'])];
  }

  protected function buildValidationRules(array $bannerConfig): array
  {
    $rules = ['id' => 'nullable'];

    foreach ($bannerConfig as $field => $isEnabled) {
      if (!$isEnabled || $field === 'display_name') continue;

      switch ($field) {
        case 'title':
          $rules[$field] = 'required|string|max:255';
          break;
        case 'sub_title':
          $rules[$field] = 'required|string|max:255';
          break;
        case 'hyper_link':
          $rules[$field] = 'nullable|url';
          break;
        case 'speed':
          $rules[$field] = 'required|integer';
          break;
        case 'alt_text':
          $rules[$field] = 'nullable|string|max:255';
          break;
        case 'image':
          $rules[$field] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:20480';
          break;
        case 'hover_image':
          $rules[$field] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:20480';
          break;
        case 'coordinates_image':
          $rules[$field] = 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:20480';
          break;
        case 'btn_color':
          $rules[$field] = 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/';
          break;
        case 'skip_btn_color':
          $rules[$field] = 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/';
          break;
        case 'product_sku':
          $rules[$field] = ['nullable', 'string', 'exists:product_variants,sku'];
          break;
        case 'coordinates':
          $rules['coordinates'] = 'nullable|array';
          $rules['coordinates.hotspots'] = 'nullable|string'; // JSON string of hotspots
          break;
        default:
          $rules[$field] = 'nullable|string';
          break;
      }
    }

    return $rules;
  }

  protected function buildValidationMessages(): array
  {
    return [
      'product_sku.exists' => 'The selected product SKU does not exist in our records.',
      'product_sku.required' => 'The product SKU field is required.',
      'title.required' => 'Title is required.',
      'title.max' => 'Title cannot exceed 255 characters.',
      'sub_title.required' => 'Sub Title is required.',
      'sub_title.max' => 'Sub Title cannot exceed 255 characters.',
      'image.image' => 'Uploaded file must be an image.',
      'image.mimes' => 'Image must be a JPG, JPEG, PNG, or WEBP file.',
      'image.max'   => 'Image size must not exceed 20480 KB (20 MB).',

      'coordinates.array' => 'Coordinates must be sent as an array.',
      'coordinates.hotspots.string' => 'Hotspot data must be valid JSON.',
      'coordinates_image.image' => 'Uploaded file must be an image.',
      'coordinates_image.mimes' => 'Image must be a JPG, JPEG, PNG, or WEBP file.',
      'coordinates_image.max'   => 'Image size must not exceed 20480 KB (20 MB).',

      'hover_image.image' => 'Uploaded file must be an image.',
      'hover_image.mimes' => 'Image must be a JPG, JPEG, PNG, or WEBP file.',
      'hover_image.max'   => 'Image size must not exceed 20480 KB (20 MB).',
    ];
  }

  protected function prepareSettings(Request $request, array $validated, array $bannerConfig): array
  {
    $settings = [];

    foreach ($bannerConfig as $field => $isEnabled) {
      if (!$isEnabled || $field === 'display_name') continue;

      if ($field === 'image') {
        if ($request->hasFile('image')) {
          $image = $request->file('image');
          $imagePath = $image->store('uploads/banners', 'public');
          $settings[$field] = basename($imagePath);
        } else {
          if ($request->id) {
            $existingId = Hashids::decode($request->id)[0] ?? null;
            $existing = CustomBanner::find($existingId);
            $settings[$field] = $existing ? (json_decode($existing->settings, true)[$field] ?? null) : null;
          } else {
            $settings[$field] = null;
          }
        }
      } elseif ($field === 'hover_image') {
        if ($request->hasFile('hover_image')) {
          $image = $request->file('hover_image');
          $imagePath = $image->store('uploads/banners', 'public');
          $settings[$field] = basename($imagePath);
        } else {
          if ($request->id) {
            $existingId = Hashids::decode($request->id)[0] ?? null;
            $existing = CustomBanner::find($existingId);
            $settings[$field] = $existing ? (json_decode($existing->settings, true)[$field] ?? null) : null;
          } else {
            $settings[$field] = null;
          }
        }
      } elseif ($field === 'coordinates_image') {
        if ($request->hasFile('coordinates_image')) {
          $image = $request->file('coordinates_image');
          $imagePath = $image->store('uploads/banners', 'public');
          $settings[$field] = basename($imagePath);
        } else {
          if ($request->id) {
            $existingId = Hashids::decode($request->id)[0] ?? null;
            $existing = CustomBanner::find($existingId);
            $settings[$field] = $existing ? (json_decode($existing->settings, true)[$field] ?? null) : null;
          } else {
            $settings[$field] = null;
            
          }
        }
      } elseif ($field !== 'title') {
        $settings[$field] = $validated[$field] ?? null;
        // dd($settings);
      }
    }

    if ($request->position === 'app_journey_screen') {
      $settings['show_button'] = ($request->has('btn_text') && trim($request->input('btn_text')) !== '') ? true : false;
      $settings['show_skip_button'] = ($request->has('skip_btn_text') && trim($request->input('skip_btn_text')) !== '') ? true : false;
    }

    return $settings;
  }
}
