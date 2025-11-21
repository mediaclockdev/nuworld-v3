<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\Currency\CurrencyRequest;
use App\Models\Currency;
use App\Services\Backend\System\CurrencyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;


class CurrencyController extends Controller
{
  protected CurrencyService $currencyService;
  protected string $name = 'Currency';
  protected $model = Currency::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->currencyService = new CurrencyService($this->model, $this->name, 'currency');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  /**
   * Renders the currency management page.
   *
   * @return View
   */
  public function index(): View
  {
    return view('backend.pages.system.currency.index', ['cardHeader' => 'Currencies List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.currency.form', $this->currencyService->getCreateData());
  }

  public function store(CurrencyRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit(int $id = 0): View|JsonResponse
  {
    return view('backend.pages.system.currency.form', $this->currencyService->getEditData($id));
  }

  public function update(CurrencyRequest $request, int $id = 0): JsonResponse
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
