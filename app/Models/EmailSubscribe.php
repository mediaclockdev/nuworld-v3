<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class EmailSubscribe extends Model
{
  protected $fillable = [
    'email',
    'is_subscribe',
    'updated_by',
    'created_by',
  ];

  // public static function updateMailConfig($data): bool
  // {
  //   $id = self::value('id');
  //   $model = self::updateOrCreate(['id' => $id], $data);

  //   return $model ? true : false;
  // }

  public static function subscribeEmail($request, int $id = 0)
  {
    $subscribeEmail = self::updateOrCreate(['id' => $id], [
      'email' => $request->email,
      'is_subscribe' => 0,
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Email Subscribed !!',
    ]);
  }

  public function scopeSearch($query, $value)
  {
    return $query->where('email', 'like', '%' . $value . '%');
    // ->orWhere('slug', 'like', '%' . $value . '%')
    // ->orWhere('meta_title', 'like', '%' . $value . '%');
  }
}
