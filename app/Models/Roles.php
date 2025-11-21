<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
  use SoftDeletes;
  protected $fillable = ['name', 'description', 'status', 'created_by', 'updated_by'];

  public function admins()
  {
    return $this->belongsToMany(Admin::class, 'admin_role', 'role_id', 'admin_id');
  }

  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
  }

  public static function store($request, int $id = 0)
  {
    return self::updateOrCreate(['id' => $id], [
      'name' => $request->rolename,
      'description' => $request->description,
      'status' => $request->status,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id
    ])->id;
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    if ($id <= user('admin')->role_id)
      return response()->json(['success' => false, 'message' => __('response.error.prohibited', ['item' => 'Higher or Same Role Change'])]);

    $role = self::find($id);
    if (!$role)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Role'])]);

    $role->status = $role->status ? 0 : 1;
    $role->updated_by = auth('admin')->id();
    $role->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Role Status']), 'newStatus' => $role->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    if ($id <= user('admin')->role_id)
      return response()->json(['success' => false, 'message' => __('response.error.prohibited', ['item' => 'Higher or Same Role Deletion'])]);

    $role = self::find($id);
    if (!$role)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Role'])]);

    if (AdminRole::where('role_id', $role->id)->exists())
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Role ', 'item2' => 'Users'])]);

    $permissions = RolePermission::where('role_id', $role->id)->delete();

    $delete = $role->delete();

    if (!$delete)
      return response()->json(['success' => false, 'message' => __('response.error.delete', ['item' => 'Role'])]);

    $role->deleted_by = auth('admin')->id();
    $role->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Role'])]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%');
  }
}
