<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\JsonResponse;

class Department extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'description',
    'status',
    'created_by',
    'updated_by',
  ];

  public static function store($request, int $id = 0): JsonResponse
  {
    $result = Department::updateOrCreate(['id' => $id], [
      'name' => $request->departmentname,
      'description' => $request->description,
      'status' => $request->status,
      'updated_by' => user('admin')->id,
      'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
    ])->id;

    if (!$result)
      return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Department']),]);

    return response()->json(['success' => true, 'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Department']),]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Department']),]);

    // Check if Pivot has entry
    if (AdminDepartment::where('department_id', $search->id)->count() > 0)
      return response()->json(['success' => false, 'message' => __('response.error.has_associated', ['item1' => 'Department', 'item2' => 'Users']),]);

    $search->delete();
    $search->deleted_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Department']),]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Department']),]);

    $search->status = $search->status ? 0 : 1;
    $search->updated_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Department Status']), 'newStatus' => $search->status]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%');
  }
}
