<?php

namespace App\Services\Backend\System;

use App\Services\Backend\BaseFormService;

class DepartmentService extends BaseFormService
{

  public function __construct(string $modelClass, string $displayName, string $variableName = 'department')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }
  public function getCreateData(): array
  {
    $data = parent::getBaseCreateData();

    return $data;
  }

  public function getEditData(int $id): array
  {
    $data = parent::getBaseEditData($id);

    return $data;
  }
}
