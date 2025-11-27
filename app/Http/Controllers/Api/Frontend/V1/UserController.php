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
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;

class UserController extends Controller
{
  public function fetchUserData(): JsonResponse
  {
    $userProfile = UserProfile::getProfileInfo();
    return ApiResponse::success(UserProfileResource::make($userProfile), __('response.success.fetch', ['item' => 'User Data']));
  }

  public function updateUserData(UpdateProfileRequest $request): JsonResponse
  {
    $updated = UserProfile::updateProfile($request->safe()->except('email'));

    return $updated
      ? ApiResponse::success(null, __('response.success.update', ['item' => 'User Data']))
      : ApiResponse::error(__('response.error.update', ['item' => 'User Data']), 400);
  }


  public function fetchUserAddress(): JsonResponse
  {
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
    $response = Address::updateUserAddressApi($request->validated());

    return $response === 0
      ? ApiResponse::error(__('response.error.update', ['item' => 'User Address']), 400, null)
      : ApiResponse::success(null, __('response.success.update', ['item' => 'User Address']), 200);
  }

  public function removeAddress(Request $request): JsonResponse
  {
    $id = $request->id ?? null;
    $address = Address::where('id', $id)->where('user_id', user()->id)->first();
    return $address->delete() ? ApiResponse::success(null, __('response.success.delete', ['item' => 'User Address']), 200) : ApiResponse::error(__('response.error.delete', ['item' => 'User Address']), 400);
  }

  public function dashboardOverview(Request $request)
  {
    $data = $this->productService->getLatestProducts($limit, 'latest');
  }
}
