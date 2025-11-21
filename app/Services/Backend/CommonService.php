<?php

namespace App\Services\Backend;

use App\Contracts\CommonServiceInterface;
use App\Traits\ModelFinder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommonService implements CommonServiceInterface
{
  use ModelFinder;

  public function update(Request $request, string $modelClass, int $id = 0): JsonResponse
  {
    $this->findOrFailModel($modelClass, $id);

    return $modelClass::store($request, $id);
  }

  public function destroy(string $modelClass, int $id = 0): JsonResponse
  {
    $this->findOrFailModel($modelClass, $id);

    return $modelClass::remove($id);
  }

  public function toggle(string $modelClass, int $id = 0): JsonResponse
  {
    $this->findOrFailModel($modelClass, $id);

    return $modelClass::toggleStatus($id);
  }

  public function multidestroy(Request $request, string $modelClass): JsonResponse
  {
    if (!method_exists($request, 'decodedIds')) {
      abort(400, 'Invalid request object. decodedIds() method missing.');
    }

    $decodedIds = $request->decodedIds();

    foreach ($decodedIds as $id) {
      $result = $modelClass::remove($id)->getData(true);
      if ($result["success"] === false) {
        return response()->json($result);
      }
    }

    return response()->json([
      'success' => true,
      'message' => __('response.success.delete', ['item' => class_basename($modelClass)])
    ]);
  }
}
