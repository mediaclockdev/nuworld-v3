<?php

namespace App\Services\Backend\System;

use App\Services\Backend\BaseFormService;

class CurrencyService extends BaseFormService
{
  private $currencies = [];
  public function __construct(string $modelClass, string $displayName, string $variableName = 'currency')
  {
    parent::__construct($modelClass, $displayName, $variableName);
    $this->currencies = json_decode(file_get_contents(resource_path('data/currency.json')), true);
  }
  public function getCreateData(): array
  {
    $data = parent::getBaseCreateData();
    $data['currencies'] = $this->currencies;

    return $data;
  }

  public function getEditData(int $id): array
  {
    $data = parent::getBaseEditData($id);
    $data['currencies'] = $this->currencies;

    return $data;
  }
}
