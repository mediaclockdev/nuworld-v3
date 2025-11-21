<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;
use App\Models\Admin;
use App\Models\AdminActivity;
use App\Models\AdminRole;
use App\Models\Roles;

class AdminUserTable extends BaseComponent
{
  public $filename = '';

  public $startDate;
  public $endDate;

  public $columnsWithAliases = [
    'sl'                                => 'Sl.',
    'first_name.middle_name.last_name'  => 'Name',
    'email'                             => 'Email',
    'phone'                             => 'Phone',
    'roleNames'                         => 'Role',
    'status'                            => 'Status',
    'created_by'                        => 'Created By',
    'created_at'                        => 'Created Date',
    'updated_by'                        => 'Updated By',
    'updated_at'                        => 'Updated Date',
  ];

    public function __construct()
  {
    $this->sortColumn = 'roleNames';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $excludeIds = [1, user('admin')->id];
    $query = Admin::select('admins.*')
      ->addSelect([
        'roleNames' => Roles::selectRaw("GROUP_CONCAT(roles.name ORDER BY roles.name SEPARATOR ', ')")
          ->join('admin_role', 'roles.id', '=', 'admin_role.role_id')
          ->whereColumn('admin_role.admin_id', 'admins.id'),
        'role_ids' => AdminRole::selectRaw("GROUP_CONCAT(admin_role.role_id ORDER BY admin_role.role_id SEPARATOR ', ')")
          ->whereColumn('admin_role.admin_id', 'admins.id'),
      ])->whereNotIn('admins.id', $excludeIds)
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }
    return $query;
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $adminUsers = $this->getQuery(true)->paginate($this->perPage);
    $firstLoggedIn = AdminActivity::getUserIds();

    $serialNumber = ($adminUsers->currentPage() - 1) * $adminUsers->perPage() + 1;

    return view('livewire.admin.admin-user-table', ['adminUsers' => $adminUsers, 'serialNumber' => $serialNumber, 'firstLoggedIn' => $firstLoggedIn]);
  }
}
