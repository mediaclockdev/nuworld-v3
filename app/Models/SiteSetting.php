<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class SiteSetting extends Model
{
  protected $fillable = ['key', 'value', 'created_by', 'updated_by'];

  /**
   * Boot the model and set event listeners for creating and updating events.
   * Automatically assigns the current admin user ID to the created_by and updated_by fields.
   */

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->created_by = user('admin')->id;
    });

    static::updating(function ($model) {
      $model->updated_by = user('admin')->id;
    });
  }

  /**
   * Get the currency associated with this site setting when the key is 'currency_id'.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function currency()
  {
    return $this->belongsTo(Currency::class, 'value', 'id')
      ->where('key', 'currency_id');
  }

  /**
   * Store the given data to the database.
   *
   * @param array $data
   * @return JsonResponse
   */
  public static function store(array $data): JsonResponse
  {
    unset($data['_token']);

    foreach ($data as $key => $value) {
      if ($key == 'country' || $key == 'state' || $key == 'currency'|| $key == 'order_copy_to' || $key == 'threshold_mails') {
        $key .= '_id';
        $value = $value ? Hashids::decode($value)[0] : null;
      }

      $update = self::updateOrCreate(
        ['key' => $key],
        [
          'key' => $key,
          'value' => $value,
          'updated_by' => user('admin')->id,
        ]
      );

      if ($update->wasRecentlyCreated) {
        $update->created_by = user('admin')->id;
        $update->save();
      }
    }

    if ($update)
      return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Site Setting'])]);

    return response()->json(['success' => false, 'message' => __('response.error.create', ['item' => 'Site Setting'])]);
  }

  /**
   * Create or Update the site logo
   * @param string $filename the filename of the logo
   * @return bool true if success
   */
  public static function updateLogo($filename): bool
  {
    $update = self::updateOrCreate(
      ['key' => 'site_logo'],
      ['key' => 'site_logo', 'value' => $filename, 'updated_by' => user('admin')->id]
    );

    if ($update->wasRecentlyCreated) {
      $update->created_by = user('admin')->id;
      $update->save();
    }

    return true;
  }

  public static function updateCurrency($data)
  {
    foreach ($data as $key => $value) {
      $update = self::updateOrCreate(
        ['key' => $key],
        ['key' => $key, 'value' => $value, 'updated_by' => user('admin')->id]
      );
    }

    return true;
  }
}
