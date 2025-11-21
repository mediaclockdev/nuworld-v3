<?php

namespace App\Services\Backend\Reports;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InventoryAnalyticsService
{
  public function __construct() {}

  public function viewData()
  {
    return [
      'pageTitle' => 'Inventory Analytics',
      'cardHeader' => 'Inventory Analytics Dashboard',
    ];
  }
  public function customerAnalyticsServiceJson1($dateRange = null)
  {
    $startDate = $dateRange
      ? Carbon::parse($dateRange->start_date)->startOfDay()
      : Carbon::now()->startOfMonth();

    $endDate = $dateRange
      ? Carbon::parse($dateRange->end_date)->endOfDay()
      : Carbon::now()->endOfMonth();

    $labels = [];
    $newCustomerData = [];
    $returningCustomerData = [];

    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
      // $labels[] = $date->format('d M');
      $labels[] = $date->format('l');


      $newCustomerCount = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->whereDoesntHave('orders')
        ->count();



      $returningCustomerCount = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->whereExists(function ($q) {
          $q->select(DB::raw(1))
            ->from('orders as o2')
            ->whereColumn('o2.user_id', 'orders.user_id')
            ->where('o2.created_at', '<', now()->startOfWeek());
        })
        ->distinct('user_id')
        ->ddRawSql();


      $newCustomerData[] = $newCustomerCount;
      $returningCustomerData[] = $returningCustomerCount;
    }


    return response()->json([
      'series' => [
        [
          'name' => 'New Customer',
          'data' => $newCustomerData
        ],
        [
          'name' => 'Returning Customer',
          'data' => $returningCustomerData
        ]
      ],
      'chart' => [
        'type' => 'line',
        'height' => 350,
        'toolbar' => [
          'show' => true,
          'export' => [
            'csv' => [
              'filename' => 'customer-analytics-' . now()->format('Y-m-d_H-i-s')
            ]
          ],
          'tools' => [
            'download' => false
          ]
        ]
      ],
      'stroke' => [
        'curve' => 'smooth',
        'width' => 3
      ],
      'xaxis' => [
        'categories' => $labels
      ],
      'colors' => ['#3b82f6', '#10b981'],
      'legend' => [
        'position' => 'top',
        'horizontalAlign' => 'left'
      ],
      'dataLabels' => [
        'enabled' => false
      ],
    ], 200, [], JSON_NUMERIC_CHECK);
  }
  public function customerAnalyticsServiceJson($dateRange = null)
  {
    $startDate = $dateRange
      ? Carbon::parse($dateRange->start_date)->startOfDay()
      : Carbon::now()->startOfMonth();

    $endDate = $dateRange
      ? Carbon::parse($dateRange->end_date)->endOfDay()
      : Carbon::now()->endOfMonth();

    $labels = [];
    $newCustomerData = [];
    $returningCustomerData = [];

    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
      $labels[] = $date->format('l d');

      // New Customers: created on this day and never ordered
      $newCustomerCount = User::whereDate('created_at', $date->toDateString())
        ->whereDoesntHave('orders')
        ->count();

      // Returning Customers: placed order on this day AND had an earlier order
      $returningCustomerCount = Order::whereDate('created_at', $date->toDateString())
        ->whereExists(function ($q) use ($date) {
          $q->select(DB::raw(1))
            ->from('orders as o2')
            ->whereColumn('o2.user_id', 'orders.user_id')
            ->where('o2.created_at', '<', $date->startOfDay());
        })
        ->distinct('user_id')
        ->count();

      $newCustomerData[] = $newCustomerCount;
      $returningCustomerData[] = $returningCustomerCount;
    }

    return response()->json([
      'series' => [
        [
          'name' => 'New Customer',
          'data' => $newCustomerData
        ],
        [
          'name' => 'Returning Customer',
          'data' => $returningCustomerData
        ]
      ],
      'chart' => [
        'type' => 'line',
        'height' => 350,
        'toolbar' => [
          'show' => true,
          'export' => [
            'csv' => [
              'filename' => 'customer-analytics-' . now()->format('Y-m-d_H-i-s')
            ]
          ],
          'tools' => [
            'download' => false
          ]
        ]
      ],
      'stroke' => [
        'curve' => 'smooth',
        'width' => 3
      ],
      'xaxis' => [
        'categories' => $labels
      ],
      'colors' => ['#3b82f6', '#10b981'],
      'legend' => [
        'position' => 'top',
        'horizontalAlign' => 'left'
      ],
      'dataLabels' => [
        'enabled' => false
      ],
    ], 200, [], JSON_NUMERIC_CHECK);
  }
}
