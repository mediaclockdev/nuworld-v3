<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
// use App\Http\Requests\Backend\Syatem\RewardManage\ScratchCardRewardRequest;
use App\Http\Requests\Backend\System\RewardManage\ScratchCardRewardRequest;
use App\Models\ScratchCardReward;
use App\Services\Backend\System\ScratchCardRewardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ScratchCardRewardController extends Controller
{
  protected string $name = "Scratch Card Reward";
  protected $model = ScratchCardReward::class;

  public function __construct(protected CommonServiceInterface $commonService, protected ScratchCardRewardService $rewardService)
  {
    view()->share('pageTitle', "Manage {$this->name}s");
  }

  public function index(): View
  {
    $this->rewardService->updateExpiredRewards();
    return view('backend.pages.system.reward-manage.index', ['cardHeader' => "Manage {$this->name}s List"]);
  }

  public function create(): View
  {
    return view('backend.pages.system.reward-manage.form', $this->rewardService->getCreateData());
  }

  public function store(ScratchCardRewardRequest $request)
  {
    return $this->rewardService->storeData($request);
  }

  public function edit(int $id): View
  {
    return view('backend.pages.system.reward-manage.form', $this->rewardService->getEditData($id));
  }

  public function update(ScratchCardRewardRequest $request, $id = '')
  {
    return $this->rewardService->updateData($request, $id);
  }

  public function toggle($id): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }
}
