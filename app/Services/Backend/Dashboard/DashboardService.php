<?php

namespace App\Services\Backend\Dashboard;

use App\Models\User;
use App\Models\AdminRole;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\Promotion;
use Carbon\Carbon;

class DashboardService
{
  public function getDashboardData($adminUser, $startDate = null, $endDate = null)
  {
    $role = AdminRole::getRole($adminUser->id);

    $start = $startDate
      ? Carbon::parse($startDate)->startOfDay()
      : Carbon::now()->startOfYear();

    $end = $endDate
      ? Carbon::parse($endDate)->endOfDay()
      : Carbon::now()->endOfMonth();

    //dd($start, $end);

    // Single query to get all three customer counts
    $customerCounts = User::selectRaw("
        COUNT(*) as total,
        COUNT(CASE WHEN status = 1 THEN 1 END) as active,
        COUNT(CASE WHEN status = 2 THEN 1 END) as inactive
    ")
      ->whereIn('status', [1, 2])
      ->whereBetween('created_at', [$start, $end])
      ->first();

    $stats = tap(new \stdClass, function ($data) use ($start, $end) {
      $data->productCount = ProductVariant::whereBetween('created_at', [$start, $end])->count();
      $data->activePromotionCount = Promotion::where('status', 1)
        ->whereBetween('created_at', [$start, $end])->count();
      $data->totalSalesRevenue = Order::whereBetween('created_at', [$start, $end])->whereIn('order_status', [1, 5])->sum('net_total');
    });

    $skuCount = ProductVariant::whereBetween('created_at', [$start, $end])
      ->distinct('sku')
      ->count('sku');
    // 1. Total category count
    $categoryCount = ProductCategory::whereBetween('created_at', [$start, $end])->count();
    //dd($categoryCount);
    // 2. Categories with sales > 0 (Top Categories)
    $topCategories = ProductCategory::withCount([
      'products as sale_count' => function ($query) use ($start, $end) {
        $query->whereHas('variants.orderDetails', function ($q) use ($start, $end) {
          $q->whereBetween('created_at', [$start, $end]);
        });
      }
    ])
      ->having('sale_count', '>', 0)
      ->orderByDesc('sale_count')
      ->get();



    $totalTopCategorySales = $topCategories->count();
    //dd($totalTopCategorySales);
    // 3. Categories with 0 sales (Weak Categories)
    $zeroSaleCategories = ProductCategory::withCount([
      'products as sale_count' => function ($query) use ($start, $end) {
        $query->whereHas('variants.orderDetails', function ($q) use ($start, $end) {
          $q->whereBetween('created_at', [$start, $end]);
        });
      }
    ])
      ->having('sale_count', '=', 0)
      ->get();

    $totalZeroSaleCount = $zeroSaleCategories->count();

    // $totalCounted = $topCategories->count() + $zeroSaleCategories->count();
    // //$totalCounted = $topCategories->count() + $zeroSaleCategories->count();
    // if ($categoryCount != $totalCounted) {
    //   dd("Category count mismatch", [
    //     'Total from DB' => $categoryCount,
    //     'Top Categories' => $topCategories->count(),
    //     'Zero Sale Categories' => $zeroSaleCategories->count(),
    //     'Combined Total' => $totalCounted,
    //   ]);
    // }

    $ordersQuery = Order::whereBetween('created_at', [$start, $end]);

    $ordersCount = (clone $ordersQuery)->count();
    $ordersCancelCount = (clone $ordersQuery)->where('order_status', 3)->count();
    $ordersConfirmCount = (clone $ordersQuery)->where('order_status', 1)->count();
    $ordersShippingCount = (clone $ordersQuery)->where('order_status', 4)->count();
    $ordersProceedCount = (clone $ordersQuery)->where('order_status', 5)->count();

    $fulfilledOrders = $ordersProceedCount;

    $fulfillmentRate = $ordersCount > 0 ? round(($fulfilledOrders / $ordersCount) * 100, 2) : 0;

    $abandonedCartCount = Cart::where('status', 0) // still in cart (not ordered)
      ->where('is_saved_for_later', 0) // user didn't save it intentionally
      ->where('quantity', '>', 0) // avoid zero-quantity junk
      ->whereNull('deleted_at') // exclude soft-deleted carts
      ->whereBetween('created_at', [$start, $end]) // date range
      ->count();

    $totalSaleVolume = Order::whereIn('order_status', [1, 5]) // confirmed or delivered
      ->whereBetween('created_at', [$start, $end]) // date range
      ->sum('order_total'); // assuming 'total' is your column for order amount

    // pd($totalTopCategorySales);

    return [
      'role' => $role,
      'customersCount' => $customerCounts->total,
      'activecustomersCount' => $customerCounts->active,
      'inactivecustomersCount' => $customerCounts->inactive,
      'productCount' => $stats->productCount,
      'activePromotionCount' => $stats->activePromotionCount,
      'totalSalesRevenue' => displayPrice($stats->totalSalesRevenue),
      'skuCount' => $skuCount,
      'categoryCount' => $categoryCount,
      'totalTopCategorySales' => $totalTopCategorySales,
      'totalWeakCategorySales' => $totalZeroSaleCount,
      'ordersCount' => $ordersCount,
      'ordersCancelCount' => $ordersCancelCount,
      'ordersConfirmCount' => $ordersConfirmCount,
      'ordersShippingCount' => $ordersShippingCount,
      'ordersProceedCount' => $ordersProceedCount,
      'fulfillmentRate' => $fulfillmentRate,
      'abandonedCartCount' => $abandonedCartCount,
      'totalSaleVolume' => displayPrice($totalSaleVolume)
    ];
  }

  public function getRevenue($dateRange)
  {
    $monthlyData = Order::revenueGenerate([$dateRange->start_date, $dateRange->end_date . ' 23:59:59']);
    $categories = [];
    $orderCounts = [];
    $totalSales = [];

    foreach ($monthlyData as $row) {
      $categories[] = $row->month_name;
      $orderCounts[] = (int) $row->order_count;
      $totalSales[] = (float) $row->total_sales;
    }

    return response()->json([
      'series' => [
        [
          'name' => 'Total Sales',
          'type' => 'column',
          'data' => $totalSales
        ],
        [
          'name' => 'Order Count',
          'type' => 'line',
          'data' => $orderCounts
        ]
      ],
      'chart' => [
        'height' => 350,
        'type' => 'line',
        'toolbar' => [
          'show' => true,
          'export' => [
            'csv' => [
              'filename' => 'revenue-overview-' . date('Y-m-d H:i:s')
            ],
          ],
          'tools' => [
            'download' => false
          ]
        ]
      ],
      'stroke' => [
        'width' => [0, 4]  // No stroke for column, 4px for line
      ],
      'dataLabels' => [
        'enabled' => true
      ],
      'xaxis' => [
        'categories' => $categories
      ],
      'yaxis' => [
        [
          'title' => ['text' => 'Total Sales']
        ],
        [
          'opposite' => true,
          'title' => ['text' => 'Order Count']
        ]
      ],
      'colors' => ['#0062d0', '#00E396'],
      'legend' => [
        'position' => 'top',
        'horizontalAlign' => 'left'
      ]
    ]);
  }
}
