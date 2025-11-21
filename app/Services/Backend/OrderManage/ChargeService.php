<?php

namespace App\Services\Backend\OrderManage;

use App\Services\Backend\BaseFormService;

class ChargeService extends BaseFormService
{
  public function __construct(string $modelClass, string $displayName, string $variableName = 'charge')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }
  public function getCreateData(): array
  {
    return parent::getBaseCreateData();
  }

  public function getEditData(int $id): array
  {
    return parent::getBaseEditData($id);
  }
}
