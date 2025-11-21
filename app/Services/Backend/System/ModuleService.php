<?php

namespace App\Services\Backend\System;

use App\Models\RemixIcon;
use App\Services\Backend\BaseFormService;


class ModuleService extends BaseFormService
{
  public function __construct(string $modelClass, string $displayName, string $variableName = 'module')
  {
    parent::__construct($modelClass, $displayName, $variableName);
  }

  public function getCreateData(): array
  {
    return [
      ...$this->getBaseCreateData(),
      'remixIcons' => RemixIcon::all(),
    ];
  }

  public function getEditData(int $id): array
  {
    return [
      ...$this->getBaseEditData($id),
      'remixIcons' => RemixIcon::all(),
    ];
  }
}
