<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\Payments\PaymentRequest;
use App\Models\PaymentSettings;
use App\Services\Backend\System\PaymentGatewayService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PaymentsController extends Controller
{
  protected PaymentGatewayService $paymentGatewayService;
  protected string $name = 'Payment Options';
  protected $model = PaymentSettings::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->paymentGatewayService = new PaymentGatewayService($this->model, $this->name, 'payment_gateway');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.payments.index', ['cardHeader' => 'Payment Options List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.payments.form', $this->paymentGatewayService->getCreateData());
  }

  public function store(PaymentRequest $request)
  {
    return PaymentSettings::store($request);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.payments.form', $this->paymentGatewayService->getEditData($id));
  }

  public function update(PaymentRequest $request, int $id = 0): JsonResponse
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
