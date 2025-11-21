<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\SubModule\SubModuleRequest;
use App\Models\SubModule;
use App\Services\Backend\System\SubModuleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SubmoduleController extends Controller
{
  protected SubModuleService $submoduleService;
  protected string $name = 'Submodules';
  protected $model = Submodule::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->submoduleService = new SubModuleService($this->model, 'Submodule', 'submodule');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.submodule.index', ['cardHeader' => 'Submodules List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.submodule.form', $this->submoduleService->getCreateData());
  }

  public function store(SubModuleRequest $request): JsonResponse
  {
    return SubModule::store($request);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.submodule.form', $this->submoduleService->getEditData($id));
  }

  public function update(SubModuleRequest $request, int $id = 0): JsonResponse
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
