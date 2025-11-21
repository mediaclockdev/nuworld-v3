<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class UserActivity extends Model
{
  protected $fillable = [
    'user_id',
    'ip_address',
    'location',
    'browser',
    'os',
    'device',
  ];

  /**
   * Insert an agent activity record into the database.
   *
   * @param string $browser The browser used by the agent.
   * @param string $ip The IP address of the agent.
   * @param string $device The device used by the agent.
   */
  public static function insertAgentActivity(array $userAgent = []): int
  {
    try {
      $logId = UserActivity::create([
        'user_id' => user('')->id, // From helper
        'ip_address' => $userAgent['ip'],
        'location' => $userAgent['location'],
        'browser' => $userAgent['browser'],
        'os' => $userAgent['os'],
        'device' => $userAgent['device'],
      ])->id;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    return $logId;
  }

  // public static function setLogOut(): bool
  // {
  //   $logId = session()->get('logID');
  //   if (!$logId)
  //     return false;
  //   AdminActivity::where('id', $logId)->update(['logged_in' => 0]);
  //   return true;
  // }

  // public static function getLoginHistory(int $rows = 10): array
  // {
  //   return AdminActivity::where('user_id', user('admin')->id)->latest()->take($rows)->get()->toArray();
  // }

  // public static function getUserIds(): array
  // {
  //   return self::select('user_id')->distinct()->pluck('user_id')->toArray();
  // }

  // public function scopeSearch($query, $value)
  // {
  //   $query->where('ip_address', 'like', '%' . $value . '%')
  //     ->orWhere('location', 'like', '%' . $value . '%')
  //     ->orWhere('browser', 'like', '%' . $value . '%')
  //     ->orWhere('os', 'like', '%' . $value . '%')
  //     ->orWhere('device', 'like', '%' . $value . '%')
  //     ->orWhere('created_at', 'like', '%' . $value . '%');
  // }
}
