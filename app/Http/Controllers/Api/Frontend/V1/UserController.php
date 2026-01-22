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
use App\Services\PortraitValidator;


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
      'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ]);

    try {
      $user = Auth::user();

      $guard = Auth::getDefaultDriver();
      $guard = $guard === 'api' ? 'web' : $guard;

      // Delete old image
      if ($user->avatar) {
        Storage::disk('public')->delete("uploads/{$guard}/profile/{$user->avatar}");
      }

      // Store image (FORCE public disk)
      $file = $request->file('image');
      $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

      Storage::disk('public')->putFileAs(
        "uploads/{$guard}/profile",
        $file,
        $fileName
      );

      // Update DB
      $user->update([
        'avatar' => $fileName,
      ]);

      //$profile = UserProfile::getProfileInfo();

      $profile = UserProfile::getProfileInfo();

      return ApiResponse::success(
        new UserProfileResource($profile),
        __('response.success.update', ['item' => 'User Data'])
      );
    } catch (\Throwable $e) {
      return ApiResponse::error($e->getMessage(), 400);
    }
  }

  public function updateAvtarImage(Request $request): JsonResponse
  {
    $request->validate([
      'is_avatar' => ['required', 'boolean'],
      'url' => ['required', 'string'],
    ]);

    try {
      $user = Auth::user();

      $guard = Auth::getDefaultDriver();
      $guard = $guard === 'api' ? 'web' : $guard;

      // Delete old image
      if ($user->image) {
        Storage::disk('public')->delete("uploads/{$guard}/profile/{$user->image}");
      }

      $fileName = uniqid() . '.png';
      $path = "uploads/{$guard}/profile/{$fileName}";

      // CASE 1: URL avatar (AI image) -> skip validation
      if ($request->is_avatar === true) {
        $imageContents = file_get_contents($request->url);

        if (!$imageContents) {
          return ApiResponse::error("Failed to download avatar image", 400);
        }

        Storage::disk('public')->put($path, $imageContents);
      }

      // CASE 2: Base64 image -> MUST be real human portrait
      else {
        $base64 = $request->url;

        if (str_contains($base64, ',')) {
          $base64 = explode(',', $base64)[1];
        }

        $imageContents = base64_decode($base64);

        if (!$imageContents) {
          return ApiResponse::error("Invalid base64 image", 400);
        }

        // 🔽 TEMP FILE FOR FACE++ CHECK
        $tempPath = storage_path('app/temp_portrait_check.png');
        file_put_contents($tempPath, $imageContents);

        // 🔴 VALIDATE PORTRAIT
        if (!PortraitValidator::isValid($tempPath)) {
          @unlink($tempPath);
          // return ApiResponse::error(
          //   "Image must be a clear human portrait photo (one face, front, close-up)",
          //   422
          // );
          return response()->json([
            'success' => false,
            'message' => "Image must be a clear human portrait photo (one face, front, close-up)",

          ], 422);
        }

        @unlink($tempPath);

        // ✅ SAVE FINAL IMAGE
        Storage::disk('public')->put($path, $imageContents);
      }

      // Update DB
      $user->update([
        'image' => $fileName,
      ]);

      $profile = UserProfile::getProfileInfo();

      return ApiResponse::success(
        new UserProfileResource($profile),
        __('response.success.update', ['item' => 'User Data'])
      );
    } catch (\Throwable $e) {
      return ApiResponse::error($e->getMessage(), 400);
    }
  }


  // public function updateAvtarImage(Request $request): JsonResponse
  // {
  //   $request->validate([
  //     'is_avatar' => ['required', 'boolean'],
  //     'url' => ['required', 'string'],
  //   ]);

  //   try {
  //     $user = Auth::user();

  //     $guard = Auth::getDefaultDriver();
  //     $guard = $guard === 'api' ? 'web' : $guard;

  //     // Delete old image
  //     if ($user->image) {
  //       Storage::disk('public')->delete("uploads/{$guard}/profile/{$user->image}");
  //     }

  //     $fileName = uniqid() . '.png';
  //     $path = "uploads/{$guard}/profile/{$fileName}";

  //     // CASE 1: URL avatar (AI / remote image)
  //     if ($request->is_avatar === true) {
  //       $imageContents = file_get_contents($request->url);

  //       if (!$imageContents) {
  //         return ApiResponse::error("Failed to download avatar image", 400);
  //       }

  //       Storage::disk('public')->put($path, $imageContents);
  //     }

  //     // CASE 2: Base64 image
  //     else {
  //       $base64 = $request->url;

  //       // Remove base64 header if exists
  //       if (str_contains($base64, ',')) {
  //         $base64 = explode(',', $base64)[1];
  //       }

  //       $imageContents = base64_decode($base64);

  //       if (!$imageContents) {
  //         return ApiResponse::error("Invalid base64 image", 400);
  //       }

  //       Storage::disk('public')->put($path, $imageContents);
  //     }

  //     // Update DB
  //     $user->update([
  //       'image' => $fileName,
  //     ]);

  //     $profile = UserProfile::getProfileInfo();

  //     return ApiResponse::success(
  //       new UserProfileResource($profile),
  //       __('response.success.update', ['item' => 'User Data'])
  //     );
  //   } catch (\Throwable $e) {
  //     return ApiResponse::error($e->getMessage(), 400);
  //   }
  // }




  public function fetchUserAddress(): JsonResponse
  {
    //dd(auth()->user());
    $country = Country::find(config('defaults.country_id'));
    // pd($country);s
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
