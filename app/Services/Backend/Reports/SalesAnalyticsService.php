<?php

namespace App\Services\Backend\Reports;

use App\Models\Order;
use Illuminate\Support\Str;

class SalesAnalyticsService
{
  public function __construct() {}

  public function viewData()
  {
    return [
      'pageTitle' => 'Sales Analytics',
      'cardHeader' => 'Sales Analytics',
    ];
  }

  public function getSaleStatusJson($dateRange)
  {
    $monthlyData = Order::saleStatus([$dateRange->start_date, $dateRange->end_date . ' 23:59:59']);
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
              'filename' => 'sales-status-' . date('Y-m-d H:i:s')
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

  public function getTopSellingProducts($dateRange)
  {
    $topVariants = Order::topSellingProducts([$dateRange->start_date, $dateRange->end_date . ' 23:59:59']);
    $categories = [];
    $orderCounts = [];

    foreach ($topVariants as $item) {
      $variantName = $item->product_name ?? 'Unnamed Product';
      $categories[] = Str::limit($variantName, 40);
      $orderCounts[] = (int) $item->order_count;
    }

    return response()->json([
      'series' => [
        [
          'name' => 'Order Count',
          'data' => $orderCounts
        ]
      ],
      'chart' => [
        'type' => 'bar',
        'height' => 400,
        'toolbar' => [
          'show' => false,
          'export' => [
            'csv' => [
              'filename' => 'top-selling-products-' . date('Y-m-d H:i:s')
            ],
          ],
        ],
      ],
      'xaxis' => [
        'categories' => $categories
      ],
      'plotOptions' => [
        'bar' => [
          'horizontal' => true,
          'barHeight' => '70%',
          'distributed' => true
        ]
      ],
      'dataLabels' => [
        'enabled' => true
      ],
      'tooltip' => [
        'y' => [
          'formatter' => 'function(val) { return val + " orders"; }'
        ]
      ],
      'colors' => ['#0062d0'],
      'legend' => [
        'show' => false
      ]
    ]);
  }
}
