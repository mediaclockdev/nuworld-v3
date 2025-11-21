<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class Permission extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'name',
    'slug',
    'description',
    'status',
    'created_by',
    'updated_by',
  ];

  public function roles()
  {
    return $this->belongsToMany(Roles::class, 'role_permissions', 'permission_id', 'role_id');
  }

  public function subModules()
  {
    return $this->belongsToMany(SubModule::class, 'submodule_permissions', 'permission_id', 'sub_module_id');
  }

  public static function store($request, int $id = 0): JsonResponse
  {
    Permission::updateOrCreate(['id' => $id], [
      'name' => $request->permissionname,
      'slug' => $request->permissionslug,
      'description' => $request->description,
      'status' => $request->status,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ]);

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Permission']),
    ]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $permission = Permission::find($id);

    if (!$permission)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Permission'])]);

    $permission->delete();
    $permission->deleted_by = auth('admin')->id();
    $permission->save();


    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Permission'])]);
  }
  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $permission = Permission::find($id);

    if (!$permission)
      return response()->json(['status' => false, 'message' => __('response.not_found', ['item' => 'Permission'])], 404);

    $permission->status = !$permission->status;
    $permission->updated_by = auth('admin')->id();
    $permission->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Permission Status']), 'newStatus' => $permission->status]);
  }

  public static function scopeSearch($query, $value)
  {
    return $query->where('name', 'like', "%" . $value . "%")
      ->orWhere('slug', 'like', "%" . $value . "%");
  }
}
