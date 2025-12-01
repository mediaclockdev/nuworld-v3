<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\DB;


class Address extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'type',
    'admin_id',
    'user_id',
    'address_1',
    'address_2',
    'landmark',
    'city',
    'state_id',
    'country_id',
    'pin',
    'phone',
    'primary',
  ];
  public static function getAdminAddress(int $id = 0): ?Model
  {
    $address = self::where('admin_id', $id)->first();
    if (!$address)
      return null;

    $countryName = Country::where('id', $address->country_id)->value('name');
    $stateName = State::where('id', $address->state_id)->value('name');
    $address->country = $countryName;
    $address->state = $stateName;
    return $address;
  }

  public static function updateAdminAddress($request): bool
  {
    $status = self::updateOrCreate(
      [['admin_id', user('admin')->id], ['type', 2]],
      [
        'admin_id' => user('admin')->id,
        'type' => 2, // Other type Address
        'address_1' => $request->address1,
        'address_2' => $request->address2,
        'landmark' => $request->landmark,
        'city' => $request->city,
        'state_id' => $request->state,
        'country_id' => Hashids::decode($request->country)[0],
        'pin' => $request->zip_code,
      ],
    )->id;
    return $status ? true : false;
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public static function updateUserAddress(array $params): bool
  {
    $address = self::updateOrCreate(
      ['id' => $params['id'] ?? null, 'user_id' => user()->id],
      [
        'name'       => $params['name'],
        'phone'      => $params['phone'],
        'user_id'    => user()->id,
        'primary'    => $params['primary'] ?? 0,
        'type'       => 0,
        'address_1'  => $params['address_line_1'],
        'address_2'  => $params['address_line_2'],
        'landmark'   => $params['landmark'],
        'city'       => $params['city_name'],
        'state_id'   => $params['state_id'],
        'country_id' => $params['country_id'] ?? config('defaults.country_id'),
        'pin'        => $params['pincode'],
      ]
    );

    return (bool) $address;
  }

  // public static function updateUserAddressApi(array $params): bool
  // {
  //   // If this address should be primary, reset all other addresses
  //   if (!empty($params['primary']) && $params['primary'] == 1) {
  //     self::where('user_id', user()->id)
  //       ->update(['primary' => 0]);
  //   }

  //   $address = self::updateOrCreate(
  //     ['id' => $params['id'] ?? null, 'user_id' => user()->id],
  //     [
  //       'name'       => $params['name'],
  //       'phone'      => $params['phone'],
  //       'user_id'    => user()->id,
  //       'primary'    => $params['primary'] ?? 0,
  //       'type'       => 0,
  //       'address_1'  => $params['address_line_1'],
  //       'address_2'  => $params['address_line_2'],
  //       'landmark'   => $params['landmark'],
  //       'city'       => $params['city_name'],
  //       'state_id'   => $params['state_id'],
  //       'country_id' => $params['country_id'] ?? config('defaults.country_id'),
  //       'pin'        => $params['pincode'],
  //     ]
  //   );

  //   return (bool) $address;
  // }

  public static function updateUserAddressApi(array $params): ?self
  {
    return DB::transaction(function () use ($params) {

      // If this address should be primary, reset all other addresses
      if (! empty($params['primary']) && (int) $params['primary'] === 1) {
        self::where('user_id', user()->id)->update(['primary' => 0]);
      }

      $address = self::updateOrCreate(
        ['id' => $params['id'] ?? null, 'user_id' => user()->id],
        [
          'name'       => $params['name'],
          'phone'      => $params['phone'],
          'user_id'    => user()->id,
          'primary'    => $params['primary'] ?? 0,
          'type'       => 0,
          'address_1'  => $params['address_line_1'],
          'address_2'  => $params['address_line_2'] ?? null,
          'landmark'   => $params['landmark'] ?? null,
          'city'       => $params['city_name'],
          'state_id'   => $params['state_id'],
          'country_id' => $params['country_id'] ?? config('defaults.country_id'),
          'pin'        => $params['pincode'],
        ]
      );

      // updateOrCreate returns model instance; return it (or null if something odd)
      return $address ?: null;
    });
  }
}
