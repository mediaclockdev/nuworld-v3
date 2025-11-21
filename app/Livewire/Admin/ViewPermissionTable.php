<?php

namespace App\Livewire\Admin;

use App\Models\AdminRole;
use App\Models\Module;
use App\Models\RolePermission;
use Livewire\Component;

class ViewPermissionTable extends Component
{
  public $modules;
  public $checkedPermissions;
  public $roleID;

  public function mount()
  {
    $this->modules = Module::getModulesWithSubModulesAndPermissions();
    $uid = user('admin')->id;
    $this->roleID = user('admin')->role_id;
    $roleIDs = AdminRole::where('admin_id', $uid)->get()->pluck('role_id');
    $checkedPermissions = [];
    foreach ($roleIDs as $roleID) {
      $permissions = RolePermission::getCheckedPermissions($roleID);
      foreach ($permissions as $key => $value) {
        if (!isset($checkedPermissions[$key])) {
          $checkedPermissions[$key] = [];
        }
        $checkedPermissions[$key] = array_unique(array_merge($checkedPermissions[$key], $value));
      }
    }
    $this->checkedPermissions = $checkedPermissions;
  }
  public function render()
  {
    return view('livewire.admin.view-permission-table');
  }
}
