<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderManage\CustomerRewardStatusRequest;
use App\Models\CustomerReward;
use App\Services\Backend\System\CustomerRewardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class CustomerRewardController extends Controller
{
  protected string $name = "Customer Reward";
  protected $model = CustomerReward::class;

  public function __construct(protected CustomerRewardService $customerRewardService)
  {
    view()->share('pageTitle', "Manage {$this->name}s");
  }

  public function index(): View
  {
    $this->customerRewardService->updateExpiredRewards();  // Set to Expired
    return view('backend.pages.system.customer-reward-manage.index', [
      'cardHeader' => "Manage {$this->name}s List",
    ]);
  }


  public function updateStatus(CustomerRewardStatusRequest $request): JsonResponse
  {
    $result = $this->customerRewardService->updateStatus($request->id, $request->status);

    return response()->json($result, $result['success'] ? 200 : 404);
  }


  public function destroy($id): JsonResponse
  {
    $decodedId = (new Hashids())->decode($id)[0] ?? null;
    $reward = $this->model::findOrFail($decodedId);
    $reward->delete();
    return response()->json(['success' => true]);
  }

  public function getCustomerRewardLogs($userId = null): View
  {
    return view('backend.pages.system.customer-reward-manage.customer-reward-logs', [
      'cardHeader' => "Manage Customer Reward Logs List",
      'userId' => $userId
    ]);
  }
}
