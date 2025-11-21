<?php

namespace App\Services\Backend\Admin;

use App\Models\Address;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Department;
use App\Models\Roles;
use App\Services\Backend\BaseFormService;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminUserService extends BaseFormService
{
  public function __construct(protected ImageUploadService $imageUploadService) {}

  public function getCreateData()
  {
    $currentRoleId = user('admin')->roles[0]->id;

    $roles = Roles::where([['id', '>=', $currentRoleId], ['status', 1]])->get();
    $departments = Department::where('status', 1)->get();

    return ['cardHeader' => 'Create Admin User', 'roles' => $roles, 'departments' => $departments, 'adminUser' => new AdminRole()];
  }

  public function getEditData($id = 0)
  {
    $adminUser = AdminRole::getSingleUserWithRole($id);
    $roles = Roles::where('status', 1)->get();
    $departments = Department::where('status', 1)->get();

    return ['cardHeader' => 'Edit Admin User', 'adminUser' => $adminUser, 'roles' => $roles, 'departments' => $departments];
  }

  public function addNewUser($request, $model): JsonResponse
  {
    $password = Str::random(8);
    $data = json_decode($model::store($request, 0, $password)->getContent());

    if ($data->success === false)
      return $data;

    $params = [
      'name' => $request->firstname . ' ' . $request->lastname,
      'password' => $password
    ];

    app('SendEmailService')->WelcomeMail($request->adminemail, $params);

    return response()->json(['success' => true, 'message' => __('response.success.create', ['item' => 'Admin User'])]);
  }

  public function resendLoginMail(int $id = 0): JsonResponse
  {
    $adminUser = Admin::find($id);

    if (!$adminUser)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Admin User'])]);

    $newPassword = Admin::resetPassword($id);

    if (!$newPassword)
      return response()->json(['success' => false, 'message' => __('response.email.error', ['item' => ''])]);

    try {
      $email = $adminUser->email;
      $params = [
        'name' => $adminUser->first_name . ' ' . $adminUser->last_name,
        'password' => $newPassword
      ];

      app('SendEmailService')->WelcomeMail($email, $params);
    } catch (\Exception $e) {
      Log::error('Email sending failed: ' . $e->getMessage());
      return response()->json(['success' => false, 'message' => __('response.email.failed', ['item' => 'Login Credentials Mail'])]);
    }

    return response()->json(['success' => true, 'message' => __('response.email.resent', ['item' => 'Login Credentials Mail'])]);
  }

  public function getProfile()
  {
    return Address::getAdminAddress(user('admin')->id);
  }

  public function updateProfileDetails($request): JsonResponse
  {
    $update = Admin::updateProfile($request, user('admin')->id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Profile'])]);

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Profile'])]);
  }

  public function updateAddressDetails($request): JsonResponse
  {
    $update = Address::updateAdminAddress($request);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Address'])]);

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Address'])]);
  }

  public function updateProfilePicture($request): JsonResponse
  {
    $image = $request->file('image');
    $userType = 'admin';
    $imageType = 'profile';

    // Delete old image
    if ($user = Admin::find(user('admin')->id)) {
      $imagePath = 'uploads/admin/profile/' . $user->avatar;
      $thumbnailPath = 'uploads/admin/profile/thumbnail/' . $user->avatar;
      if (Storage::disk('public')->exists($imagePath)) {
        Storage::disk('public')->delete($imagePath);
        Storage::disk('public')->delete($thumbnailPath);
      }
    }

    $upload = $this->imageUploadService->uploadImage($image, $userType, $imageType);

    $fullPath = url('/') . '/public/storage/' . $upload['path'];
    $update = Admin::updateProfilePicture($upload['filename'], user('admin')->id);

    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Profile Picture'])]);

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Profile Picture']), 'path' => $fullPath]);
  }

  public function removeProfilePicture(): JsonResponse
  {
    $update = Admin::updateProfilePicture('', user('admin')->id);
    if (!$update)
      return response()->json(['success' => false, 'message' => __('response.error.delete', ['item' => 'Profile Picture'])]);

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Profile Picture'])]);
  }
}
