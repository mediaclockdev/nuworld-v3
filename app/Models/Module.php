<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Module extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'name',
    'icon',
    'sequence',
    'description',
    'status',
    'created_by',
    'updated_by',
  ];

  /**
   * Gets all submodules belonging to this module ordered by sequence.
   *
   * @return HasMany
   */
  public function subModules(): HasMany
  {
    return $this->hasMany(SubModule::class)->orderBy('sequence');
  }

  /**
   * @param $request
   * @return JsonResponse
   *
   */
  public static function store($request, $id = ''): JsonResponse
  {
    $update = Module::updateOrCreate(['id' => $id], [
      'name' => $request->modulename,
      'icon' => $request->moduleicon,
      'sequence' => $request->modulesequence,
      'description' => $request->description,
      'status' => $request->status,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ]);

    if ($update)
      return response()->json(['success' => true, 'message' => __('response.success' . ($id ? '.update' : '.create'), ['item' => 'Module'])]);

    return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Module'])]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $module = Module::find($id);
    if (!$module)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Module'])]);

    $submodule = SubModule::where('module_id', $id)->first();
    if ($submodule)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Module', 'item2' => 'Sub Module'])]);

    $module->delete();

    $module->deleted_by = auth('admin')->id();
    $module->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Module'])]);
  }


  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $module = Module::find($id);
    if (!$module)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Module'])]);

    $module->status = $module->status ? 0 : 1;
    $module->updated_by = auth('admin')->id();
    $module->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Module Status']), 'newStatus' => $module->status]);
  }

  /**
   * Retrieves all modules with their respective submodules and permissions.
   *
   * @return Collection The collection of modules with their respective submodules and permissions.
   */
  public static function getModulesWithSubModulesAndPermissions(): Collection
  {
    $modules = self::with(['submodules.permissions' => function ($query) {
      $query->select('permissions.id', 'permissions.name');
    }])->orderBy('sequence')->get();

    return $modules->map(function ($module) {
      return ['id' => $module->id, 'name' => $module->name, 'icon' => $module->icon, 'sequence' => $module->sequence, 'submodules' => $module->subModules->map(function ($subModule) {
        return ['id' => $subModule->id, 'name' => $subModule->name, 'sequence' => $subModule->sequence, 'permissions' => $subModule->permissions->map(function ($permission) {
          return ['id' => $permission->id, 'name' => $permission->name,];
        })->toArray(),];
      })->toArray(),];
    });
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%');
  }
}
