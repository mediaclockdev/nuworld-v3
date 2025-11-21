<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DateRangeRequest;
use App\Models\{
  Admin,
  AdminRole,
  Cart,
  Inventory,
  Order,
  OrderHistory,
  ProductVariant,
  Roles
};
use App\Services\Backend\Dashboard\DashboardService;
use Illuminate\Http\{
  JsonResponse,
  Request
};
use Illuminate\Support\Facades\{
  DB,
  Response
};
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
  public function __construct(protected DashboardService $dashboardService) {}

  public function index()
  {
    $user = user('admin');
    $excludeIds = [1, $user->id];

    $adminUsers = Admin::select('admins.*')
      ->addSelect([
        'roleNames' => Roles::selectRaw("GROUP_CONCAT(roles.name ORDER BY roles.name SEPARATOR ', ')")
          ->join('admin_role', 'roles.id', '=', 'admin_role.role_id')
          ->whereColumn('admin_role.admin_id', 'admins.id'),

        'role_ids' => AdminRole::selectRaw("GROUP_CONCAT(admin_role.role_id ORDER BY admin_role.role_id SEPARATOR ', ')")
          ->whereColumn('admin_role.admin_id', 'admins.id'),
      ])
      ->whereNotIn('admins.id', $excludeIds)
      ->limit(2)
      ->get();

    $dashboardData = $this->dashboardService->getDashboardData($user);

    return view('backend.pages.dashboard', with([
      'pageTitle' => 'Dashboard',
      'user' => $user,
      'dashboardData' => $dashboardData,
      'adminUsers' => $adminUsers
    ]));
  }

  public function exportStreamed()
  {
    $user = user('admin');
    $excludeIds = [1, $user->id];

    $adminUsers = Admin::select('admins.*')
      ->addSelect([
        'roleNames' => Roles::selectRaw("GROUP_CONCAT(roles.name ORDER BY roles.name SEPARATOR ', ')")
          ->join('admin_role', 'roles.id', '=', 'admin_role.role_id')
          ->whereColumn('admin_role.admin_id', 'admins.id'),

        'role_ids' => AdminRole::selectRaw("GROUP_CONCAT(admin_role.role_id ORDER BY admin_role.role_id SEPARATOR ', ')")
          ->whereColumn('admin_role.admin_id', 'admins.id'),
      ])
      ->whereNotIn('admins.id', $excludeIds)
      ->limit(2)
      ->get();

    $filename = 'admin_users_' . date('Y-m-d H:i:s') . '.csv';

    $response = new StreamedResponse(function () use ($adminUsers) {
      $handle = fopen('php://output', 'w');

      // CSV headers
      fputcsv($handle, [
        'ID',
        'User Name',
        'Email Address',
        'Role',
        'Status',
        'Joined On'
      ]);

      foreach ($adminUsers as $user) {
        $fullName = trim("{$user->first_name} {$user->middle_name} {$user->last_name}");
        fputcsv($handle, [
          $user->id,
          $fullName,
          $user->email,
          $user->roleNames,
          $user->status == 1 ? 'Active' : 'Inactive',
          $user->created_at->format('Y-m-d H:i:s'),
        ]);
      }

      fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    return $response;
  }


  public function filterDashboardData(Request $request)
  {
    $user = user('admin');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $dashboardData = $this->dashboardService->getDashboardData($user, $startDate, $endDate);

    return response()->json($dashboardData);
  }

  public function revenueOverviewJson(DateRangeRequest $request)
  {
    return $this->dashboardService->getRevenue($request);
  }

  private function fetchInventoryOverview()
  {
    /* $completed = OrderHistory::with('order')->where('status', 5)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count();
    $inTransit = OrderHistory::with('order')->where('status', 1)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count();
    $inCart = Cart::where('status', 0)
      ->where('is_saved_for_later', 0)
      ->where('quantity', '>', 0)
      ->whereMonth('created_at', now()->month)
      ->whereYear('created_at', now()->year)
      ->whereNull('deleted_at')
      ->count();
    $cancelled = OrderHistory::with('order')->where('status', 3)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count(); */

    $completed = Order::where('order_status', 5)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count();
    $inTransit = Order::where('order_status', 1)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count();
    $inCart = Cart::where('status', 0)
      ->where('is_saved_for_later', 0)
      ->where('quantity', '>', 0)
      ->whereMonth('created_at', now()->month)
      ->whereYear('created_at', now()->year)
      ->whereNull('deleted_at')
      ->count();
    // pd($inCart);
    $cancelled = Order::where('order_status', 3)->whereMonth('updated_at', now()->month)
      ->whereYear('updated_at', now()->year)->count();


    return [
      'Completed'  => $completed,
      'In Transit' => $inTransit,
      'In Cart'    => $inCart,
      'Cancelled'  => $cancelled,
    ];
  }


  public function getInventoryOverviewData()
  {
    $counts = $this->fetchInventoryOverview();
    $total = array_sum($counts);
    $newProducts = ProductVariant::whereDate('created_at', today())->count();

    return response()->json([
      'series' => array_values($counts),
      'total' => $total,
      'new' => $newProducts,
      'labels' => array_keys($counts),
      'counts' => $counts,
    ]);
  }

  public function exportInventoryCsv(): StreamedResponse
  {
    $counts = $this->fetchInventoryOverview();

    $datetime = str_replace([':', ' '], ['-', '_'], date('Y-m-d H:i:s'));
    $filename = "orders_overview_{$datetime}.csv";

    $headers = [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function () use ($counts) {
      $file = fopen('php://output', 'w');
      fputcsv($file, ['Status', 'Count']);

      foreach ($counts as $status => $count) {
        fputcsv($file, [$status, $count]);
      }

      fclose($file);
    };

    return response()->stream($callback, 200, $headers);
  }

  // public function getInventoryBarChart()
  // {
  //   $inventories = Inventory::with('product')
  //     ->selectRaw('product_id, SUM(quantity) as total_quantity')
  //     ->groupBy('product_id')
  //     ->whereMonth('created_at', Carbon::now()->month)
  //     ->whereYear('created_at', Carbon::now()->year)
  //     ->orderByDesc('total_quantity')

  //     ->get();

  //   $labels = [];
  //   $series = [];

  //   foreach ($inventories as $inventory) {
  //     $productName = optional($inventory->product)->name ?? 'Unnamed';
  //     $labels[] = Str::limit($productName, 25); // limit long labels
  //     $series[] = (int) $inventory->total_quantity;
  //   }

  //   return response()->json([
  //     'labels' => $labels,
  //     'series' => $series
  //   ]);
  // }

  public function getInventoryBarChart()
  {
    /* $inventories = Inventory::with('variant')->get();

    $variantStatus = [
      'In Stock' => 0,
      'Low Stock' => 0,
      'Out of Stock' => 0,
    ];

    foreach ($inventories as $inventory) {
      if ($inventory->quantity == 0) {
        $variantStatus['Out of Stock']++;
      } elseif ($inventory->quantity < $inventory->threshold) {
        $variantStatus['Low Stock']++;
      } else {
        $variantStatus['In Stock']++;
      }
    }

    $total = array_sum($variantStatus);
    $series = $total > 0
      ? array_map(function ($count) use ($total) {
        return round(($count / $total) * 100, 2);
      }, array_values($variantStatus))  // <--- important
      : [0, 0, 0];

    return response()->json([
      'labels' => array_keys($variantStatus),
      'series' => $series
    ]); */

    $inventories = Inventory::with('variant')->get();

    $variantStatus = [
      'In Stock' => 0,
      'Low Stock' => 0,
      'Out of Stock' => 0,
    ];

    foreach ($inventories as $inventory) {
      if ($inventory->quantity == 0) {
        $variantStatus['Out of Stock']++;
      } elseif ($inventory->quantity < $inventory->threshold) {
        $variantStatus['Low Stock']++;
      } else {
        $variantStatus['In Stock']++;
      }
    }

    $total = array_sum($variantStatus);
    $series = $total > 0
      ? array_map(function ($count) use ($total) {
        return round(($count / $total) * 100, 2);
      }, array_values($variantStatus))
      : [0, 0, 0];

    return response()->json([
      'labels' => array_keys($variantStatus),
      'series' => $series,
      'counts' => array_values($variantStatus) // Add counts array
    ]);
  }

  public function inventoryTurnoverRate(Request $request)
  {
    $startDate = Carbon::parse($request->input('start_date', Carbon::now()->startOfWeek()->toDateString()))->toDateString();
    $endDate = Carbon::parse($request->input('end_date', Carbon::now()->endOfWeek()->toDateString()))->toDateString();

    // Query to get the latest stock (quantity) per product and group by stock level
    $data = Inventory::select(
      'quantity AS stock_left',
      DB::raw('COUNT(*) AS product_count')
    )
      ->whereIn('updated_at', function ($query) use ($startDate, $endDate) {
        $query->select(DB::raw('MAX(updated_at)'))
          ->from('inventories')
          ->whereBetween('updated_at', [$startDate, $endDate])
          ->groupBy('product_variant_id');
      })
      ->whereBetween('updated_at', [$startDate, $endDate])
      ->groupBy('quantity')
      ->orderBy('quantity')
      ->get();

    $labels = [];
    $series = [];

    foreach ($data as $row) {
      $labels[] = $row->stock_left . ' stock left';
      $series[] = $row->product_count;
    }

    return response()->json([
      'labels' => $labels,
      'series' => $series,
      'message' => empty($labels) ? 'No stock data available for the selected date range.' : null
    ]);
  }
  public function getUserDemographyChart(): JsonResponse
  {
    $subquery = DB::table('orders')
      ->select(
        DB::raw("JSON_UNQUOTE(JSON_EXTRACT(shipping_address, '$.state')) as shipping_state"),
        'user_id'
      )
      ->whereNotNull('user_id')
      ->groupBy('shipping_state', 'user_id');

    // Outer query: count how many unique users per state
    $stateData = DB::table(DB::raw("({$subquery->toSql()}) as sub"))
      ->mergeBindings($subquery)
      ->select('shipping_state', DB::raw('COUNT(*) as count'))
      ->groupBy('shipping_state')
      ->orderByDesc('count')
      ->get()
      ->map(function ($item) {
        return [
          'label' => $item->shipping_state ?? 'Unknown State',
          'count' => $item->count
        ];
      });

    return response()->json([
      'labels' => $stateData->pluck('label'),
      'series' => $stateData->pluck('count'),
    ]);
  }

  public function exportUserDemography()
  {
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $addresses = DB::table('addresses')
      ->leftJoin('states', 'addresses.state_id', '=', 'states.id')
      ->select(
        'addresses.country_id',
        'states.name as state_name',
        'addresses.city',
        'addresses.pin',
        DB::raw('COUNT(addresses.id) as user_count')
      )
      ->whereNotNull('addresses.user_id')
      ->whereBetween('addresses.created_at', [$startOfMonth, $endOfMonth])
      ->groupBy('addresses.state_id', 'states.name')
      ->get();

    $csvData = [];
    $csvData[] = ['State Name', 'User Count'];

    foreach ($addresses as $row) {
      $csvData[] = [
        $row->state_name ?? 'Unknown State',
        $row->user_count
      ];
    }

    $filename = 'user_demography_' . date('Y-m-d H:i:s') . '.csv';

    $handle = fopen('php://temp', 'r+');
    foreach ($csvData as $line) {
      fputcsv($handle, $line);
    }
    rewind($handle);
    $content = stream_get_contents($handle);
    fclose($handle);

    return Response::make($content, 200, [
      'Content-Type' => 'text/csv',
      'Content-Disposition' => "attachment; filename=\"$filename\"",
    ]);
  }
}
