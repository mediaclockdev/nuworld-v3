<?php

namespace App\Services\Backend\System;

use App\Models\Module;
use App\Models\Permission;
use App\Models\RemixIcon;
use App\Models\SubmodulePermission;
use App\Services\Backend\BaseFormService;

class SubModuleService extends BaseFormService
{
  public function __construct(string $modelClass, string $name = 'Submodule', string $variableName = 'submodule')
  {
    parent::__construct($modelClass, $name, $variableName);
  }

  public function getCreateData(): array
  {
    $data = parent::getBaseCreateData();
    $data['modules'] = Module::where('status', 1)->get();
    $data['remixIcons'] = RemixIcon::all();
    $data['permissions'] = Permission::all();

    return $data;
  }

  public function getEditData(int $id): array
  {
    $data = parent::getBaseEditData($id);
    $data['modules'] = Module::where('status', 1)->get();
    $data['remixIcons'] = RemixIcon::all();
    $data['permissions'] = Permission::all();
    $data['submodulePermissions'] = SubmodulePermission::where('sub_module_id', $id)->pluck('permission_id')->toArray();

    return $data;
  }
}
