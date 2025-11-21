<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchQuery extends Model
{
  protected $fillable = ['query', 'count', 'user_id', 'ip_address'];

  /**
   * Log or increment a search query
   */
  public static function log(string $query, ?int $userId = null, ?string $ip = null): void
  {
    $query = trim(strtolower($query)); // normalize

    // Try to increment if exists
    $existing = self::where('query', $query)->first();

    if ($existing) {
      $existing->increment('count');
      $existing->touch(); // updates timestamps
    } else {
      self::create([
        'query' => $query,
        'user_id' => $userId,
        'ip_address' => $ip,
        'count' => 1,
      ]);
    }
  }
}
