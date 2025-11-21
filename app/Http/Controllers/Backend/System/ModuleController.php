<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\Module\ModuleRequest;
use App\Models\Module;
use App\Services\Backend\System\ModuleService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
  protected ModuleService $moduleService;
  protected string $name = 'Modules';
  protected $model = Module::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->moduleService = new ModuleService($this->model, $this->name, 'module');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.module.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.module.form', $this->moduleService->getCreateData());
  }

  public function store(ModuleRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.module.form', $this->moduleService->getEditData($id));
  }

  public function update(ModuleRequest $request, int $id = 0): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }

  public function getPermission(): Collection
  {
    return $this->model::getModulesWithSubModulesAndPermissions();
  }
}
