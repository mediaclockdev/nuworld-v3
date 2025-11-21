<?php

namespace App\Services\Backend\Admin;

use App\Models\Module;
use App\Models\RolePermission;
use App\Models\Roles;
use App\Services\Backend\BaseFormService;

class RoleService extends BaseFormService
{
  public function __construct() {}

  public function getCreateData()
  {
    $modules = Module::getModulesWithSubModulesAndPermissions();

    return ['cardHeader' => 'Create Role', 'modules' => $modules, 'checkedPermissions' => [], 'role' => new Roles()];
  }

  public function getEditData($id = 0)
  {
    $role = Roles::find($id);
    $modules = Module::getModulesWithSubModulesAndPermissions();
    $checkedPermissions = RolePermission::getCheckedPermissions($id);

    return ['cardHeader' => 'Edit Role', 'role' => $role, 'modules' => $modules, 'checkedPermissions' => $checkedPermissions];
  }
}
