<?php

namespace App\Services\Backend;

use App\Traits\ModelFinder;

class BaseFormService
{
  use ModelFinder;

  public function __construct(
    protected string $modelClass,
    protected string $name = '',
    protected string $variableName = 'model'
  ) {}

  public function getBaseCreateData(): array
  {
    return [
      'cardHeader' => 'Create ' . $this->name,
      $this->variableName => new $this->modelClass()
    ];
  }

  public function getBaseEditData(int $id, array $with = []): array
  {
    $model = $this->modelClass::query()
      ->with($with)
      ->findOrFail($id);

    return [
      'cardHeader' => 'Edit ' . $this->name,
      $this->variableName => $model
    ];
  }
}
