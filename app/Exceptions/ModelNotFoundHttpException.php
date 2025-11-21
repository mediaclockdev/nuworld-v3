<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class ModelNotFoundHttpException extends Exception
{
  protected string $model;
  protected int $id;

  public function __construct(string $model, int $id)
  {
    $this->model = $model;
    $this->id = $id;
    parent::__construct("{$model} with ID {$id} not found.");
  }

  public function render(): JsonResponse
  {
    return response()->json([
      'success' => false,
      'message' => __('response.not_found', [
        'model' => class_basename($this->model),
        'id' => Hashids::encode($this->id)
      ])
    ], 404);
  }
}
