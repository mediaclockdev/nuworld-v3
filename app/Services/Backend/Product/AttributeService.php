<?php

namespace App\Services\Backend\Product;

use App\Services\Backend\BaseFormService;

class AttributeService extends BaseFormService
{
  public function __construct(string $modelClass, string $displayName, string $variableName = 'productAttribute')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }

  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),

    ];
  }

  public function getEditData(int $id): array
  {
    return [
      ...$this->getBaseEditData($id),

    ];
  }
}
