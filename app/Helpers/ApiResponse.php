<?php

namespace App\Helpers;

class ApiResponse
{
  public static function success($data = null, string $message = 'Success', int $statusCode = 200)
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $data,
    ], $statusCode);
  }

  public static function error(string $message = 'Something went wrong', int $statusCode = 400, $data = [])
  {
    return response()->json([
      'success' => false,
      'message' => $message,
      'data' => $data,
    ], $statusCode);
  }

  public static function successWithPagination($data = null, $pagination = null, string $message = 'Success', int $statusCode = 200)
  {
    return response()->json([
      'success' => true,
      'message' => $message,
      'data' => $data,
      'pagination' => $pagination
    ], $statusCode);
  }
}
