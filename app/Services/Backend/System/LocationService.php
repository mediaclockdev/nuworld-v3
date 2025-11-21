<?php

namespace App\Services\Backend\System;

use App\Models\Country;
use App\Services\Backend\BaseFormService;

class LocationService extends BaseFormService
{

  public function __construct(string $modelClass, string $displayName, string $variableName = 'state')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }
  public function getCreateData(): array
  {
    $data = parent::getBaseCreateData();
    $data['countries'] = Country::all();

    return $data;
  }

  public function getEditData(int $id): array
  {
    $data = parent::getBaseEditData($id);
    $data['countries'] = Country::all();

    return $data;
  }
}
