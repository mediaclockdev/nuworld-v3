<?php

namespace App\Services\Backend\OrderManage;

use App\Services\Backend\BaseFormService;

class OrderReturnService extends BaseFormService
{
  public function __construct(string $modelClass, string $displayName, string $variableName = 'order_return')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }

  public function getEditData(int $id): array
  {
    return parent::getBaseEditData($id, ['order', 'user', 'reviewer']);
  }
}
