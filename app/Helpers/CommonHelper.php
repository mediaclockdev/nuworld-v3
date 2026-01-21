<?php

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\RolePermission;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\Product;
use Jenssegers\Agent\Agent;


if (! function_exists('user')) {
  function user($guard = null)
  {
    if (!Schema::hasTable('admins'))
      return null;

    $authUser = auth()->guard($guard)->user();

    if ($authUser) {
      $role = AdminRole::getRole($authUser->id);
      $authUser->role_id = $role->id ?? null;
      $authUser->role_name = $role->name ?? null;
    }

    return $authUser;
  }
}

if (!function_exists('getUserAgentInfo')) {
  function getUserAgentInfo(): array
  {
    $userAgentService = new \App\Services\UserAgentService(new Agent());

    $browser = $userAgentService->getUserBrowser() . ' v' . $userAgentService->getBrowserVersion();
    $ip = request()->header('X-Forwarded-For') ?: request()->ip();
    $location = ($ip != '::1' && $ip != '127.0.0.1') ? $userAgentService->getLocation($ip) : 'Localhost';
    $device = $userAgentService->getDeviceType();
    $os = $userAgentService->getUserOS();

    return [
      'browser' => $browser,
      'ip' => $ip,
      'location' => $location,
      'device' => $device,
      'os' => $os
    ];
  }
}

if (! function_exists('userNameById')) {
  function userNameById($guard = null, $id = 0)
  {
    if (!Schema::hasTable('admins')) {
      return null; // Return a default value instead of crashing
    }
    $name = $guard == 'admin' ?
      Admin::where('id', $id)->first(['first_name', 'middle_name', 'last_name']) :
      User::where('id', $id)->first(['first_name', 'middle_name', 'last_name']);

    return $name->first_name . ' ' . $name->middle_name . ' ' . $name->last_name;
  }
}

if (! function_exists('userDetailById')) {
  function userDetailById($guard = null, $id = 0)
  {
    if (!Schema::hasTable('admins'))
      return null;

    $detail = $guard == 'admin' ?
      Admin::where('id', $id)->first(['email', 'phone', 'status', 'avatar']) :
      User::where('id', $id)->first(['email', 'phone', 'status', 'avatar']);

    return $detail;
  }
}

if (!function_exists('siteLogo')) {
  function siteLogo()
  {
    if (!Schema::hasTable('site_settings')) {
      return null; // Return a default value instead of crashing
    }
    $logo = SiteSetting::where('key', 'site_logo')->value('value');
    // return $logo ? url('public/storage/uploads/site/logo/' . $logo) : asset('public/backend/assetss/images/logo-dark.png');
    return $logo ? url('public/frontend/assets/img/New_World.webp') : asset('public/frontend/assets/img/New_World.webp');
  }
}

if (!function_exists('getStatuses')) {
  function getStatuses()
  {
    return [
      0 => 'Pending',
      1 => 'Confirmed',
      2 => 'Cancellation Initiated',
      3 => 'Cancelled',
      4 => 'Shipped',
      5 => 'Delivered',
      6 => 'Return Accepted',
      7 => 'Refund Done'
    ];
  }
}
if (!function_exists('getStatusesLog')) {
  function getStatusesLog()
  {
    return [
      1 => 'Confirmed',
      2 => 'Cancellation Initiated',
      3 => 'Cancelled',
      4 => 'Shipped',
      5 => 'Delivered',
      6 => 'Return Accepted',
      7 => 'Refund Done'
    ];
  }
}

if (!function_exists('dashboardSmallLogo')) {
  function dashboardSmallLogo()
  {
    if (!Schema::hasTable('site_settings')) {
      return null; // Return a default value instead of crashing
    }
    $logo = SiteSetting::where('key', 'dashboard_small_logo')->value('value');
    return $logo ? url('public/storage/uploads/site/logo/' . $logo) : asset('public/backend/assetss/images/logo-dark-sm.png');
  }
}

if (!function_exists('userImageById')) {
  function userImageById($guard = null, $id = 0)
  {
    if (!Schema::hasTable('admins')) {
      return null; // Prevent crash if table doesn't exist
    }

    $user = $guard == 'admin'
      ? Admin::where('id', $id)->first(['avatar', 'first_name', 'last_name'])
      : User::where('id', $id)->first(['avatar', 'first_name', 'last_name']);

    $imageName = $user ? $user->avatar : null;

    if ($imageName) {
      $imagePath = 'public/storage/uploads/' . $guard . '/profile/' . $imageName;
      $thumbnailPath = 'public/storage/uploads/' . $guard . '/profile/thumbnail/' . $imageName;

      return [
        'image'     => url($imagePath),
        'thumbnail' => url($thumbnailPath),
      ];
    } else {
      // Handle initials if image not found
      $firstName = $user->first_name ?? 'N';
      $lastName = $user->last_name ?? null;

      $initials = strtoupper(mb_substr($firstName, 0, 1));
      if (!empty($lastName)) {
        $initials .= strtoupper(mb_substr($lastName, 0, 1));
      }

      $svg = base64_encode("
        <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'>
          <rect width='100%' height='100%' fill='#555'/>
          <text x='50%' y='50%' font-size='34' font-family='Arial' font-weight='bold' fill='#ffffff' text-anchor='middle' dy='.3em'>$initials</text>
        </svg>
      ");

      return [
        'image'     => "data:image/svg+xml;base64,{$svg}",
        'thumbnail' => "data:image/svg+xml;base64,{$svg}",
      ];
    }
  }
}

if (!function_exists('userAvtarImageById')) {
  function userAvtarImageById($guard = null, $id = 0)
  {
    if (!Schema::hasTable('admins')) {
      return null; // Prevent crash if table doesn't exist
    }

    $user = $guard == 'admin'
      ? Admin::where('id', $id)->first(['avatar', 'first_name', 'last_name'])
      : User::where('id', $id)->first(['avatar', 'first_name', 'last_name']);

    $imageName = $user ? $user->image : null;

    if ($imageName) {
      $imagePath = 'public/storage/uploads/' . $guard . '/profile/' . $imageName;
      $thumbnailPath = 'public/storage/uploads/' . $guard . '/profile/thumbnail/' . $imageName;

      return [
        'image'     => url($imagePath),
        'thumbnail' => url($thumbnailPath),
      ];
    } else {
      // Handle initials if image not found
      $firstName = $user->first_name ?? 'N';
      $lastName = $user->last_name ?? null;

      $initials = strtoupper(mb_substr($firstName, 0, 1));
      if (!empty($lastName)) {
        $initials .= strtoupper(mb_substr($lastName, 0, 1));
      }

      $svg = base64_encode("
        <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'>
          <rect width='100%' height='100%' fill='#555'/>
          <text x='50%' y='50%' font-size='34' font-family='Arial' font-weight='bold' fill='#ffffff' text-anchor='middle' dy='.3em'>$initials</text>
        </svg>
      ");

      return [
        'image'     => "data:image/svg+xml;base64,{$svg}",
        'thumbnail' => "data:image/svg+xml;base64,{$svg}",
      ];
    }
  }
}


if (!function_exists('hasUserPermission')) {
  function hasUserPermission($routeName)
  {
    $user = user('admin'); // Get the authenticated admin user
    if (!$user) {
      return false;
    }
    $currentUserRoleId = AdminRole::getRole($user->id)->id;
    if ($currentUserRoleId == 1) {
      return true;
    }

    return RolePermission::hasPermission($currentUserRoleId, $routeName);
  }
}

function truncateNoWordBreak($text, $limit = 250, $end = '...')
{
  if (Str::length($text) <= $limit) {
    return $text;
  }

  $truncated = Str::limit($text, $limit, '');
  return preg_replace('/\s+?(\S+)?$/', '', $truncated) . $end;
}

if (!function_exists('renderCategoryOptions')) {
  function renderCategoryOptions($categories, $selectedID = null, $level = 0, $html = '', $path = '')
  {
    foreach ($categories as $category) {
      $indent = str_repeat('&nbsp;', $level * 2);

      $currentPath = $path ? $path . ' > ' . $category['title'] : $category['title'];

      $displayTitle = $indent . str_repeat('-', $level) . ' ' . $category['title'] . ' (' . $currentPath . ')';

      $html .= '<option data-slug="' . $category['slug'] . '" value="' . Hashids::encode($category['id']) . '" ' .
        ($selectedID == $category['id'] ? 'selected' : '') . '>' .
        $displayTitle . '</option>';

      if (!empty($category['children']) && collect($category['children'])->isNotEmpty()) {
        $html = renderCategoryOptions($category['children'], $selectedID, $level + 1, $html, $currentPath);
      }
    }
    return $html;
  }
}

if (!function_exists('adminMailsByRoleID')) {
  function adminMailsByRoleID(array $roleIDs): array // If no user is found the superadmins mail will be returned
  {
    return Admin::where('status', 1)->whereHas('roles', fn($q) => $q->whereIn('roles.id', $roleIDs))
      ->pluck('email')
      ->whenEmpty(fn() => Admin::whereHas('roles', fn($q) => $q->where('roles.id', 1))->pluck('email'))
      ->toArray();
  }
}

if (!function_exists('product_image_url')) {
  function product_image_url($image): ?string
  {
    return $image?->file_name
      ? asset("public/storage/uploads/media/products/images/{$image->file_name}")
      : null;
  }
}

if (!function_exists('get_default_product_image')) {
  function get_default_product_image(Product $product, $matchedVariant = null): string
  {
    $image = $matchedVariant?->relationLoaded('images')
      ? $matchedVariant->images->firstWhere('is_default', 1)?->gallery
      : null;

    if ($url = product_image_url($image)) return $url;

    foreach ($product->variants as $variant) {
      if ($url = product_image_url($variant->images->firstWhere('is_default', 1)?->gallery)) {
        return $url;
      }
    }

    return asset('public/backend/assetss/images/products/sofa.jpg');
  }
}

if (!function_exists('get_default_variant_image')) {
  function get_default_variant_image($variant): string
  {
    return product_image_url($variant->images->first()?->gallery)
      ?? asset('public/backend/assetss/images/products/sofa.jpg');
  }
}


if (!function_exists('pd')) {
  function pd(...$array)
  {
    echo "<body style='height:5px;background:gray'>";
    foreach ($array as $value) {
      echo "<pre style='background:#18171B;color:#56DB3A;padding:10px'>";
      print_r(json_decode(json_encode($value), true));
      echo "</pre>";
    }
    die("</body>");
  }
}
if (!function_exists('getProductNames')) {
  function getProductNames($productIds)
  {
    // Assuming you have a Product model
    $products = Product::whereIn('id', $productIds)->pluck('name')->toArray();
    return $products ?? ['Unknown Products'];
  }
}
