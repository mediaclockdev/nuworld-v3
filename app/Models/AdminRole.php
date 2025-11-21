<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
  protected $table = 'admin_role';

  protected $fillable = ['admin_id', 'role_id', 'created_by', 'updated_by'];

  public static function getRole($user_id)
  {
    $roleId = AdminRole::where('admin_id', $user_id)->value('role_id');
    return Roles::where('id', $roleId)->first(['id', 'name']);
  }

  public static function getUsersByRole()
  {
    $currentUserRoleID = AdminRole::getRole(user('admin')->id)->id;

    $admins = Admin::with(['roles' => function ($query) use ($currentUserRoleID) {
      $query->where('id', '>=', $currentUserRoleID);
    }])->get();

    // Map only the matching admins
    $adminList = $admins->filter(function ($admin) {
      return $admin->roles->isNotEmpty(); // Keep only admins with roles matching the filter
    })->map(function ($admin) {
      return [
        'id' => $admin->id,
        'avatar' => $admin->avatar,
        'name' => trim("{$admin->first_name} {$admin->middle_name} {$admin->last_name}"),
        'email' => $admin->email,
        'phone' => $admin->phone,
        'roles' => $admin->roles->pluck('name')->implode(', '),
        'created_at' => $admin->created_at,
        'status' => $admin->status,
      ];
    });

    return $adminList;
  }


  public static function getSingleUserWithRole(int $id = 0)
  {
    $admin = Admin::find($id)->toArray();
    $admin['role'] = [];

    if ($admin) {
      $adminRole = AdminRole::where('admin_id', $id)->get()->toArray();
      foreach ($adminRole as $role) {
        $admin['role'][] = Roles::where('id', $role['role_id'])->value('id');
      }

      $adminDepartment = AdminDepartment::where('admin_id', $id)->get()->toArray();
      foreach ($adminDepartment as $department) {
        $admin['department'] = Department::where('id', $department['department_id'])->value('id');
      }
      return $admin;
    }
  }

  public static function getAllRolesWithID(int $id = 0)
  {
    return AdminRole::where('admin_id', $id)->get(['role_id'])->toArray();
  }
}
