<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
  protected $fillable = ['menu_id', 'title', 'slug', 'parent_id', 'sequence'];

  public function children()
  {
    return $this->hasMany(self::class, 'parent_id')->with('children');
  }

  public function category()
  {
    return $this->belongsTo(ProductCategory::class);
  }

  public static function saveItems(array $items, int $menuId, ?int $parentId = 0, int $sequence = 0): void
  {
    foreach ($items as $item) {
      if ($item['deleted'])
        continue;

      $menuItem = self::create([
        'menu_id' => $menuId,
        'title' => $item['name'],
        'slug' => $item['slug'] ?? 'aa',
        'parent_id' => $parentId ?? 0,
        'sequence' => $sequence++,
      ]);

      if (!empty($item['children'])) {
        self::saveItems($item['children'], $menuId, $menuItem->id, 0, $sequence);
      }
    }
  }
}
