<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\ApiAddressRequest;
use App\Http\Requests\Frontend\UpdateProfileRequest;
use App\Services\Frontend\UserProfile;
use App\Http\Resources\Api\Frontend\AddressResource;
use App\Http\Resources\Api\Frontend\StatesResource;
use App\Http\Resources\Api\Frontend\UserProfileResource;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

  public function fetchUserData(): JsonResponse
  {
    $userProfile = UserProfile::getProfileInfo();
    return ApiResponse::success(UserProfileResource::make($userProfile), __('response.success.fetch', ['item' => 'User Data']));
  }

  public function updateUserData(UpdateProfileRequest $request): JsonResponse
  {
    try {
      // Perform the update (even if no actual values changed)
      UserProfile::updateProfile($request->safe()->except('email'));

      // Always return current profile data
      $profile = UserProfile::getProfileInfo();

      return ApiResponse::success(
        new UserProfileResource($profile),
        __('response.success.update', ['item' => 'User Data'])
      );
    } catch (\Throwable $e) {

      // Log::error('User update failed', [
      //   'user_id' => auth()->id(),
      //   'error'   => $e->getMessage(),
      // ]);

      return ApiResponse::error(
        __('response.error.update', ['item' => 'User Data']),
        400
      );
    }
  }


  public function updateUserImage(Request $request): JsonResponse
  {
    $request->validate([
      'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    try {
      $user  = Auth::user();
      $guard = Auth::getDefaultDriver(); // web | admin

      // Delete old image if exists
      if (!empty($user->avatar)) {
        Storage::delete([
          "public/storage/uploads/{$guard}/profile/{$user->avatar}",
          "public/storage/uploads/{$guard}/profile/thumbnail/{$user->avatar}",
        ]);
      }

      // Store new image
      $file = $request->file('avatar');
      $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

      $file->storeAs(
        "public/storage/uploads/{$guard}/profile",
        $fileName
      );

      // Update ONLY avatar column
      $user->update([
        'avatar' => $fileName,
      ]);

      // Return fresh profile info
      $profile = UserProfile::getProfileInfo();

      return ApiResponse::success(
        new UserProfileResource($profile),
        __('response.success.update', ['item' => 'Profile Image'])
      );
    } catch (\Throwable $e) {
      return ApiResponse::error(
        __('response.error.update', ['item' => 'Profile Image']),
        400
      );
    }
  }


  public function fetchUserAddress(): JsonResponse
  {
    //dd(auth()->user());
    $country = Country::find(config('defaults.country_id'));
    // pd($country);
    $data = [
      'country' => $country ? [
        'id'   => Hashids::encode($country->id),
        'name' => $country->name,
        'code' => $country->code,
      ] : null,
      'addresses' => AddressResource::collection(Address::where('user_id', user()->id)->get()),
      'list_of_states' => StatesResource::collection(State::where([['status', 1], ['country_id', config('defaults.country_id')]])->get(['id', 'name']))
    ];

    return ApiResponse::success($data, __('response.success.fetch', ['item' => 'User Address']), 200);
  }
  public function updateUserAddress(ApiAddressRequest $request): JsonResponse
  {
    $address = Address::updateUserAddressApi($request->validated());

    if (! $address) {
      return ApiResponse::error(__('response.error.update', ['item' => 'User Address']), 400, null);
    }

    return ApiResponse::success(
      new AddressResource($address),
      __('response.success.update', ['item' => 'User Address']),
      200
    );
  }

  public function removeAddress(Request $request): JsonResponse
  {
    $id = $request->id ?? null;

    // 1. Validate `id` presence + numeric
    if (!$id || !is_numeric($id)) {
      return response()->json([
        'success' => false,
        'data'    => [],
        'message' => 'Invalid address id.',
      ], 422);
    }

    // 2. Check address exists for given user
    $address = Address::where('id', $id)
      ->where('user_id', user()->id)
      ->first();

    if (!$address) {
      return response()->json([
        'success' => false,
        'data'    => [],
        'message' => 'Address not found.',
      ], 422);
    }

    // 3. Delete address
    if ($address->delete()) {
      return ApiResponse::success(
        [],
        __('response.success.delete', ['item' => 'User Address']),
        200
      );
    }

    return ApiResponse::error(
      __('response.error.delete', ['item' => 'User Address']),
      400
    );
  }

  // public function dashboardOverview(Request $request)
  // {
  //   $data = $this->productService->getLatestProducts($limit, 'latest');
  // }
}
