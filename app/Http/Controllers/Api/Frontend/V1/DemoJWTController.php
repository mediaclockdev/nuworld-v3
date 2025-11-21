<?php

namespace App\Http\Controllers\Api\Frontend\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class DemoJWTController extends Controller
{
  public function index()
  {
    $product = [
      'id' => 1,
      'name' => 'Product 1',
    ];
    return ApiResponse::success($product, 'Product fetched successfully');
  }

  public function generateToken($userId = 5)
  {
    $user = User::find($userId);
    if (!$user)
      return ApiResponse::error('User not found', 404);

    $token = JWTAuth::fromUser($user);
    return ApiResponse::success($token, __("response.success.generate", ['item' => 'Token']));
  }

  public function decodeId()
  {
    $user = Auth::user();
    return ApiResponse::success($user->id, __("response.success.decode", ['item' => 'User Id']));
  }
}
