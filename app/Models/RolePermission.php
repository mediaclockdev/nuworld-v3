<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class RolePermission extends Model
{
  protected $fillable = ['role_id', 'sub_module_id', 'permission_id', 'created_by', 'updated_by'];


  public function role()
  {
    return $this->belongsTo(Roles::class, 'role_id');
  }

  public function subModule()
  {
    return $this->belongsTo(SubModule::class, 'sub_module_id');
  }

  public function permission()
  {
    return $this->belongsTo(Permission::class, 'permission_id');
  }

  /**
   * Retrieves the menu structure for the given role_id, sorted by their sequence.
   *
   * @param int $roleId The role_id for which to retrieve the menu structure. Defaults to 0, which is the admin role.
   * @return array The menu structure as an associative array, where each key is a module_id and the value is an associative array containing the module's details and a 'submodules' key which contains an array of submodule details.
   */
  public static function getMenus(int $roleId = 0): array
  {
    if ($roleId == 1) // For Super Admin
      $subIDs = SubModule::distinct()->pluck('id');
    else
      $subIDs = self::where('role_id', $roleId)->distinct()->pluck('sub_module_id'); // Retrieve unique sub_module_ids for the given role_id

    // Fetch submodules along with their associated modules
    $submodules = SubModule::whereIn('id', $subIDs)
      ->where('status', 1)
      ->whereHas('module', function ($query) {
        $query->where('status', 1);
      })
      ->with('module')
      ->get()
      ->sortBy([
        fn($submodule) => optional($submodule->module)->sequence, // Use optional to prevent null pointer
        fn($submodule) => $submodule->sequence,
      ]);

    // Group submodules by their parent module
    $menu = $submodules->groupBy('module.id')->map(function ($submodules) {
      $module = $submodules->first()->module;

      return [
        'id' => $module->id,
        'name' => $module->name,
        'icon' => $module->icon,
        'path' => $module->route_name,
        'sequence' => $module->sequence,
        'submodules' => $submodules->map(function ($submodule) {
          return ['id' => $submodule->id, 'name' => $submodule->name, 'sequence' => $submodule->sequence, 'path' => $submodule->route_name];
        })->sortBy('sequence')->values()->toArray(),
      ];
    })->sortBy('sequence')->values()->toArray();

    return $menu;
  }

  /**
   * Stores the given permissions for the given role.
   *
   * @param $params
   * @return bool
   */
  public static function store($params, int $roleID): JsonResponse
  {
    $hasPermission = RolePermission::where('role_id', $roleID)->first();

    if ($hasPermission)
      RolePermission::where('role_id', $roleID)->delete();

    if (!empty($params))
      foreach ($params as $key => $value) {
        foreach ($value as $v) {
          RolePermission::create([
            'role_id' => $roleID,
            'sub_module_id' => $key,
            'permission_id' => Hashids::decode($v)[0],
          ]);
        }
      }

    return response()->json(['success' => true, 'message' => __('response.success.' . ($hasPermission ? 'update' : 'create'), ['item' => 'Role & Permission'])]);
  }

  /**
   * Retrieves an associative array of checked permissions for the given role.
   *
   * @param int $roleId The role_id for which to retrieve the checked permissions. Defaults to 0, which is the admin role.
   * @return array An associative array, where each key is a sub_module_id and the value is an array of permission_ids checked for that submodule.
   */
  public static function getCheckedPermissions(int $roleId = 0): array
  {
    $checkedPermissions = RolePermission::select('sub_module_id', 'permission_id')
      ->when($roleId != 1, function ($query) use ($roleId) {
        $query->where('role_id', $roleId);
      })
      ->get()
      ->groupBy('sub_module_id')
      ->map(fn($permissions) => $permissions->pluck('permission_id')->unique()->values()->toArray())
      ->toArray(); // Ensure it returns an array

    return $checkedPermissions;
  }


  /**
   * Checks if a given role has permission for a specific route.
   *
   * @param int $roleId The role_id to check for permission. Defaults to 0.
   * @param string $routeName The route name for which to check the permission.
   * @return bool Returns true if the role has the permission for the route, otherwise false.
   */

  public static function hasPermission(int $roleId = 0, string $routeName = ''): bool
  {
    $permissionMap = [
      'update' => 'edit',
      'store' => 'create',
    ];

    $parts = explode('.', $routeName);
    if (count($parts) <= 2) {
      $submoduleSlug = $routeName;
      $permissionSlug = 'view';
    } else {
      $submoduleSlug = $parts[0] . '.' . $parts[1];
      $permissionSlug = $parts[2];
      $permissionSlug = $permissionMap[$permissionSlug] ?? $permissionSlug;
    }

    return RolePermission::where('role_id', $roleId)
      ->whereHas('subModule', function ($query) use ($submoduleSlug) {
        $query->where('route_name', $submoduleSlug);
      })
      ->whereHas('permission', function ($query) use ($permissionSlug) {
        $query->where('slug', $permissionSlug);
      })
      ->exists();
  }
}
