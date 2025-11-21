<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class Store extends Model
{
  use SoftDeletes, HasFactory;

  protected $fillable = [
      'name',
      'address',
      'country_id',
      'city',
      'state',
      'pincode',
      'location',
      'latitude',
      'longitude',
      'phone',
      'image',
      'status',
      'created_by',
      'updated_by',
  ];

  public function country()
  {
    return $this->belongsTo(Country::class, 'country_id');
  }

  public static function scopeSearch($query, $search)
  {
      if (!$search) return;

      $query->where(function ($q) use ($search) {
          $q->where('name', 'like', "%$search%")
            ->orWhere('city', 'like', "%$search%")
            ->orWhere('address', 'like', "%$search%");
      });
  }

  public static function toggleStatus(int $id = 0): JsonResponse
  {
      $update = self::find($id);
      if (!$update)
          return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Store'])]);

      $update->status = $update->status == 1 ? 0 : 1;
      $update->save();

      return response()->json([
          'success' => true,
          'message' => __('response.success.update', ['item' => 'Store Status']),
          'newStatus' => $update->status
      ]);
  }

  public static function remove(int $id = 0): JsonResponse
  {
      $store = self::find($id);
      if (!$store)
          return response()->json(['success' => false, 'message' => __('response.not_found', ['item' => 'Store'])]);

      $store->delete();
      $store->deleted_by = auth('admin')->id();
      $store->save();

      return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Store'])]);
  }

  public static function store($data, string $id = '')
  {
      $result = self::updateOrCreate(['id' => $id], [
          'name'       => $data->name,
          'address'    => $data->address,
          'country_id' => $data->country_id ?? config('defaults.country_id'),
          'city'       => $data->city,
          'state'      => $data->state ?? null,
          'pincode'    => $data->pincode ?? null,
          'location'   => $data->location ?? null,
          'latitude'   => $data->latitude ?? null,
          'longitude'  => $data->longitude ?? null,
          'phone'      => $data->phone ?? null,
          'image'      => $data->image_name ?? null,
          'status'     => $data->status ?? 1,
          'updated_by' => user('admin')->id,
          'created_by' => $id ? self::find($id)->created_by : user('admin')->id,
      ])->id;

      if (!$result)
          return response()->json(['success' => false, 'message' => __('response.error' . ($id ? '.update' : '.create'), ['item' => 'Store'])]);

      return response()->json([
          'success' => true,
          'message' => __('response.success.' . ($id ? 'update' : 'create'), ['item' => 'Store']),
          'value' => Hashids::encode($result)
      ]);
  }
}
