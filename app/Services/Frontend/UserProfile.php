<?php

namespace App\Services\Frontend;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;


class UserProfile
{
  /**
   * Create a new class instance.
   */

  // fetching user profile
  // public static function getProfileInfo()
  // {
  //   $data = [];
  //   $userData = Auth::user();
  //   $userImage = userImageById('api', $userData->id);
  //   $data['user_data'] = $userData;
  //   $data['user_image'] = $userImage['image'] ?? null;
  //   return $data;
  // }

  public static function getProfileInfo()
  {
    $user = Auth::user();

    $guard = Auth::getDefaultDriver();
    $guard = $guard === 'api' ? 'web' : $guard;

    $image = userImageById($guard, $user->id);
    $avtarimage = userAvtarImageById($guard, $user->id);


    return [
      'user_data' => $user, // ✅ REQUIRED
      'user_image' => $image['image'] ?? null,
      'user_avtar_image' => $avtarimage['image'] ?? null,
    ];
  }

  public static function updateProfile(array $attributes): bool
  {
    $user = Auth::user();
    $user->fill($attributes);

    if (!$user->isDirty())
      return false;
    return $user->save();
  }
}
