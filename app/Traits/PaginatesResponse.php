<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PaginatesResponse
{
  /**
   * Format pagination for both Eloquent and manually paginated collections.
   *
   * @param LengthAwarePaginator|Collection $items
   * @param int $currentPage
   * @param int $perPage
   * @return array
   */
  protected function formatPagination($items, int $currentPage = 1, int $perPage = 10): array
  {
    if ($items instanceof LengthAwarePaginator) {
      return [
        'current_page' => $items->currentPage(),
        'last_page'    => $items->lastPage(),
        'per_page'     => $items->perPage(),
        'total'        => $items->total(),
        'from'         => $items->firstItem(),
        'to'           => $items->lastItem(),
      ];
    }

    if ($items instanceof Collection) {
      $total = $items->count();
      return [
        'current_page' => $currentPage,
        'last_page'    => (int) ceil($total / $perPage),
        'per_page'     => $perPage,
        'total'        => $total,
        'from'         => ($currentPage - 1) * $perPage + 1,
        'to'           => min($currentPage * $perPage, $total),
      ];
    }

    return [];
  }
}
