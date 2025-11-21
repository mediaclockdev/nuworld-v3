<?php

namespace App\Http\Controllers\Backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Backend\Reports\InventoryAnalyticsService;
use App\Http\Requests\Backend\DateRangeRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;


class InventoryAnalyticsController extends Controller
{
  public function __construct(protected InventoryAnalyticsService $inventoryAnalyticsService) {}

  public function index()
  {
    return view('backend.pages.reports.inventory-analytics.index', $this->inventoryAnalyticsService->viewData());
  }
}
