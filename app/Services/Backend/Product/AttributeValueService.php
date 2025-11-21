<?php

namespace App\Services\Backend\Product;

use App\Models\ProductAttribute;
use App\Services\Backend\BaseFormService;


class AttributeValueService extends BaseFormService
{
  public function __construct(string $modelClass, string $displayName, string $variableName = 'attributeValue')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }

  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),
      'attributes' => ProductAttribute::where('status', 1)->get()

    ];
  }

  public function getEditData(int $id): array
  {
    return [
      ...$this->getBaseEditData($id),
      'attributes' => ProductAttribute::where('status', 1)->get()

    ];
  }
}
