<?php

namespace App\Http\Controllers\Backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Backend\Reports\CustomerAnalyticsService;
use App\Http\Requests\Backend\DateRangeRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;


class CustomerAnalyticsController extends Controller
{
  public function __construct(protected CustomerAnalyticsService $customerAnalyticsService) {}

  public function index()
  {
    return view('backend.pages.reports.customer-analytics.index', $this->customerAnalyticsService->viewData());
  }

  public function getNewVsReturningCustomers(DateRangeRequest $request)
  {
    return $this->customerAnalyticsService->customerAnalyticsServiceJson($request);
  }

  public function getOrderListByCustomer($user_id): View
  {
    $customer = User::find(Hashids::decode($user_id)[0]);
    if (!$customer)
      abort(404);
    return view('backend.pages.reports.customer-analytics.customer-orders-list', ['cardHeader' => $customer->first_name . ' Order List', 'pageTitle' => 'Customer Order List', 'customer' => $customer]);
  }


  /* public function exportCsvValue($user_id)
  {
    $customer = User::find(Hashids::decode($user_id)[0]);
    if (!$customer)
      abort(404);
  } */
}
