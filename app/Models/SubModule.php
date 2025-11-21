<?php

namespace App\Models;

use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class SubModule extends Model
{
  use SoftDeletes;

  protected $fillable = ['name', 'module_id', 'route_name', 'sequence', 'icon', 'description', 'created_by', 'updated_by', 'status'];

  /**
   * Belongs to a module.
   *
   * @return BelongsTo
   */
  public function module(): BelongsTo
  {
    return $this->belongsTo(Module::class);
  }

  /**
   * Retrieves all submodule permissions of this submodule.
   *
   * @return HasMany
   */
  public function subModulePermissions(): HasMany
  {
    return $this->hasMany(SubmodulePermission::class, 'sub_module_id');
  }
  /**
   * Defines a many-to-many relationship between the submodule and permissions.
   *
   * @return BelongsToMany
   */

  public function permissions(): BelongsToMany
  {
    return $this->belongsToMany(Permission::class, 'submodule_permissions', 'sub_module_id', 'permission_id');
  }

  /**
   * Retrieves all submodules with their respective parent modules.
   *
   * @return Collection All submodules with their respective parent modules.
   */
  public static function getWithModules(): Collection
  {
    return SubModule::with(['module' => function ($query) {
      $query->select('id', 'name');
    }])->get(['id', 'name', 'module_id', 'route_name', 'sequence', 'icon', 'description', 'status']);
  }

  /**
   * Creates a new submodule or updates an existing one in the database.
   *
   * @param mixed $request The validated request data containing the submodule information.
   *
   * @return JsonResponse The response with the created or updated submodule or an error message.
   */
  public static function store($request, int $id = 0): JsonResponse
  {
    $submoduleID = self::updateOrCreate(
      ['id' => $id],
      [
        'route_name' => $request->submoduleslug,
        'name' => $request->submodulename,
        'module_id' => $request->parentmodule,
        'sequence' => $request->submodulesequence,
        'icon' => $request->submoduleicon,
        'description' => $request->description,
        'status' => $request->status,
        'updated_by' => user('admin')->id,
        'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
      ]
    )->id;

    // Create or update the submodule permissions
    if ($request->has('permissions')) {
      $delete = SubmodulePermission::where('sub_module_id', $submoduleID)->delete();
      $permissions = $request->permissions;
      foreach ($permissions as $permission) {
        SubmodulePermission::updateOrCreate(
          ['sub_module_id' => $submoduleID, 'permission_id' => $permission],
          []
        );
      }
    }

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'SubModule'])
    ]);
  }


  public static function remove(int $id = 0): JsonResponse
  {
    $submodule = self::find($id);

    if (!$submodule) {
      return response()->json([
        'success' => false,
        'message' => __('response.not_found', ['item' => 'SubModule']),
      ]);
    }

    $submodule->delete();

    $submodule->deleted_by = auth('admin')->id();
    $submodule->save();

    return response()->json([
      'success' => true,
      'message' => __('response.success.delete', ['item' => 'SubModule'])
    ]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $submodule = self::find($id);
    if (!$submodule) {
      return response()->json([
        'success' => false,
        'message' => __('response.not_found', ['item' => 'SubModule']),
      ]);
    }

    $submodule->status = $submodule->status ? 0 : 1;
    $submodule->updated_by = auth('admin')->id();
    $submodule->save();

    return response()->json([
      'success' => true,
      'message' => __('response.success.update', ['item' => 'SubModule Status']),
      'newStatus' => $submodule->status
    ]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%')
      ->orWhere('route_name', 'like', '%' . $search . '%')
      ->orWhereHas('module', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
      });
  }
}
