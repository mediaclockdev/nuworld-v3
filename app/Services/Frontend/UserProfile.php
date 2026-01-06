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
    $user = Auth::user();

    $guard = Auth::getDefaultDriver();
    $guard = $guard === 'api' ? 'web' : $guard;

    $image = userImageById($guard, $user->id);

    return [
      'id' => $user->id,
      'first_name' => $user->first_name,
      'last_name' => $user->last_name,
      'email' => $user->email,
      'default_profile_image' => $image['image'] ?? null,
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
