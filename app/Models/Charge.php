<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
  use SoftDeletes;
  protected $fillable = [
    'name',
    'calculation_method',
    'value',
    'is_mandatory',
    'applicability',
    'conditions',
    'status',
    'created_by',
    'updated_by',
  ];

  protected $casts = [
    'conditions' => 'array',
    'is_mandatory' => 'boolean',
  ];

  public static function store($request, $id = null)
  {
    $charge = self::updateOrCreate(
      ['id' => $id],
      [
        'name'               => $request->name,
        'calculation_method' => $request->calculation_method, // fixed, percentage, etc.
        'value'              => $request->charge_amount,
        'is_mandatory'       => $request->is_mandatory ?? true,
        'conditions'         => $request->conditions, // Can be stringified JSON or null
        'applicability'      => $request->applicability,
        'status'             => $request->status ?? true,
        'updated_by'         => user('admin')->id,
        'created_by'         => $id ? self::find($id)->created_by : user('admin')->id,
      ]
    );

    return response()->json([
      'success' => true,
      'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Charge']),
    ]);
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
    $search = self::find($id);
    if (!$search)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Charge']),]);

    $search->status = $search->status ? 0 : 1;
    $search->updated_by = auth('admin')->id();
    $search->save();

    return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Charge Status']), 'newStatus' => $search->status]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
    $charge = self::find($id);

    if (!$charge)
      return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Charge'])]);

    $charge->delete();
    $charge->deleted_by = auth('admin')->id();
    $charge->save();

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Charge'])]);
  }

  public static function scopeSearch($query, $search)
  {
    return $query->where('name', 'like', '%' . $search . '%');
  }
}
