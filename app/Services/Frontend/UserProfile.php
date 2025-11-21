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
  public static function getProfileInfo()
  {
    $data = [];
    $userData = Auth::user();
    $userImage = userImageById('api', $userData->id);
    $data['user_data'] = $userData;
    $data['user_image'] = $userImage['image'] ?? null;
    return $data;
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
