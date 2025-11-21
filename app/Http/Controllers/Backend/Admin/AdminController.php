<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\{ChangePasswordRequest, ImageRequest, UpdateProfileRequest, Backend\Admin\AddressRequest};
use App\Services\Backend\Admin\AdminUserService;
use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
  public function __construct(protected AdminUserService $adminUserService) {}
  public function profile(): View
  {
    return View('backend.pages.auth.profile', ['pageTitle' => 'Profile', 'address' => $this->adminUserService->getProfile()]);
  }

  /**
   * @param UpdateProfileRequest $request
   * @return JsonResponse
   */
  public function updateProfile(UpdateProfileRequest $request): JsonResponse
  {
    return $this->adminUserService->updateProfileDetails($request);
  }

  /**
   * @param ChangePasswordRequest $request with currentpassword and newpassword
   *
   * @return JsonResponse
   */
  public function updatePassword(ChangePasswordRequest $request): JsonResponse
  {
    return Admin::updatePassword(user('admin')->id, $request->currentpassword, $request->newpassword);
  }

  /**
   * Update the profile picture for the current admin user.
   *
   * @param ImageRequest $request
   * @return JsonResponse
   */
  public function updateProfilePicture(ImageRequest $request): JsonResponse
  {
    return $this->adminUserService->updateProfilePicture($request);
  }

  public function deleteProfilePicture(): JsonResponse
  {
    return $this->adminUserService->removeProfilePicture();
  }

  public function updateAddress(AddressRequest $request): JsonResponse
  {
    return $this->adminUserService->updateAddressDetails($request->validated());
  }
}
