<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\OrderManage\ChargesRequest;
use App\Models\Charge;
use App\Services\Backend\OrderManage\ChargeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class ChargeController extends Controller
{
  protected ChargeService $chargeService;
  protected string $name = 'Charge';
  protected $model = Charge::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->chargeService = new ChargeService($this->model, $this->name, 'charge');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  public function index()
  {
    return view('backend.pages.order-manage.charges.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.order-manage.charges.form', $this->chargeService->getCreateData());
  }

  public function store(ChargesRequest $request): JsonResponse
  {
    return Charge::store($request);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.order-manage.charges.form', $this->chargeService->getEditData($id));
  }

  public function update(ChargesRequest $request, int $id = 0): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }



  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
