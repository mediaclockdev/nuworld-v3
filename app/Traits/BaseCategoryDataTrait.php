<?php

namespace App\Traits;

trait BaseCategoryDataTrait
{
  /**
   * Get the nested categories array for the specific context
   * @return array
   */

  abstract protected function getNestedCategories(): array;

  /**
   * Get the parent, child, and grandchild categories arrays for the specific context
   * @return array
   */
  public function getCategoryLevels(): array
  {
    $categories = $this->getNestedCategories();
    $parentCategories = [];
    $childCategories = [];
    $grandchildCategories = [];

    foreach ($categories as $parent => $children) {
      $parentCategories[] = $parent;
      $childCategories[$parent] = [];

      foreach ($children as $child => $grandchildren) {
        $childCategories[$parent][] = $child;
        if (!empty($grandchildren)) {
          $grandchildCategories[$child] = $grandchildren;
        }
      }
    }

    return [
      'parentCategories' => $parentCategories,
      'childCategories' => $childCategories,
      'grandchildCategories' => $grandchildCategories
    ];
  }
}
