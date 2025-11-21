<?php

namespace App\Http\Controllers\Backend\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\DateRangeRequest;
use App\Services\Backend\Reports\SalesAnalyticsService;

class SalesAnalyticsController extends Controller
{
  public function __construct(protected SalesAnalyticsService $salesAnalyticsService) {}

  public function index()
  {
    return view('backend.pages.reports.sales-analytics.index', $this->salesAnalyticsService->viewData());
  }

  public function getSaleStatus(DateRangeRequest $request)
  {
    return $this->salesAnalyticsService->getSaleStatusJson($request);
  }

  public function getTopSellingProducts(DateRangeRequest $request)
  {
    return $this->salesAnalyticsService->getTopSellingProducts($request);

  }
}
