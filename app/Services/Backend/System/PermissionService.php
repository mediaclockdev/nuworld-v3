<?php

namespace App\Services\Backend\System;

use App\Services\Backend\BaseFormService;

class PermissionService extends BaseFormService
{
  public function __construct(string $modelClass, string $name = 'Permission', string $variableName = 'permission')
  {
    parent::__construct($modelClass, $name, $variableName);
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
