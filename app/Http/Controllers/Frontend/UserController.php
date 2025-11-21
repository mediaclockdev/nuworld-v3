<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\{
  SetDefaultLocationRequest,
  UpdatePasswordRequest,
  UpdateProfileRequest,
  UpdateUserAddressRequest
};
use App\Models\CustomerReward;
use App\Services\Frontend\UserService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\{JsonResponse, Request};


class UserController extends Controller
{

  public function __construct(protected UserService $userService) {}

  public function profile(): View
  {
    $data['title'] = 'Profile';
    return view('frontend.pages.user.profile', $data);
  }
  public function editProfile(): View
  {
    $data['title'] = 'Edit Profile';
    return view('frontend.pages.user.edit-profile-details', $data);
  }

  public function updateProfile(UpdateProfileRequest $request): JsonResponse
  {
    return $this->userService->updateProfile($request);
  }

  public function savedPayment(): View
  {
    return view('frontend.pages.user.saved-payment.index');
  }
  public function address(): View
  {
    $data = $this->userService->getAddressPageData();
    return view('frontend.pages.user.address.index', $data);
  }

  public function createOrUpdateAddress(UpdateUserAddressRequest $request): JsonResponse
  {
    return $this->userService->createOrUpdateAddress($request); //Checkout page address create
  }

  public function deleteAddress(Request $request): JsonResponse
  {
    return $this->userService->deleteAddress($request);
  }

  public function changePassword(): View
  {
    $data['title'] = 'Change Password';
    return view('frontend.pages.user.change-password', $data);
  }
  public function updatePassword(UpdatePasswordRequest $request): JsonResponse
  {
    return $this->userService->updatePassword($request);
  }

  public function profileDetails(): View
  {
    $data = $this->userService->getProfileDetailsData();
    return view('frontend.pages.user.profile-details', $data);
  }
  public function reward(): View
  {
    $data['title'] = 'Reward';

    $data['customerRewards'] = CustomerReward::with('scratchCardReward')
      ->where('customer_id', user()->id)
      ->get();

    $today = Carbon::now()->toDateString();

    $data['customerRewards']->filter(function ($reward) use ($today) {
      return $reward->expiry_date < $today;
    })->each(function ($reward) {
      $reward->update(['status' => 3]);
    });

    // Sum the value column from the related table
    $data['totalReward'] =  $data['customerRewards']->where('status', 1)
      ->sum(function ($reward) {
        return $reward->scratchCardReward->value ?? 0;
      });
    return view('frontend.pages.user.reward.index', $data);
  }

  public function updateUserAddress(UpdateUserAddressRequest $request)
  {
    return $this->userService->updateUserAddress($request);
  }

  public function getAddressList()
  {
    return $this->userService->getAddressList();
  }
  public function setDefaultAddress(SetDefaultLocationRequest $request)
  {
    return $this->userService->setDefaultAddress($request);
  }

  public function setSelectedAddress(Request $request)
  {
    return $this->userService->setSelectedAddress($request);
  }
}
