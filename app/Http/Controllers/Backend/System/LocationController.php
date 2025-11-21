<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\CountryState\CountryStateRequest;
use App\Models\State;
use App\Services\Backend\System\LocationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
  protected LocationService $locationService;
  protected string $name = 'States';
  protected $model = State::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->locationService = new LocationService($this->model, $this->name, 'state');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.location.index', ['cardHeader' => 'States List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.location.form', $this->locationService->getCreateData());
  }

  public function store(CountryStateRequest $request): JsonResponse
  {
    return State::store($request, null);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.location.form', $this->locationService->getEditData($id));
  }

  public function update(CountryStateRequest $request, int $id = 0): JsonResponse
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
