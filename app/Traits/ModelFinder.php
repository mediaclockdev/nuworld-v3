<?php

namespace App\Traits;

use App\Exceptions\ModelNotFoundHttpException;

trait ModelFinder
{
  protected function findOrFailModel(string $modelClass, int $id): object
  {
    $model = $modelClass::find($id);

    if (!$model) {
      throw new ModelNotFoundHttpException($modelClass, $id);
    }

    return $model;
  }
}
