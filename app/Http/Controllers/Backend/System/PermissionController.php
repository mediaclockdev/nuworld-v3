<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\Permission\PermissionRequest;
use App\Models\Permission;
use App\Services\Backend\System\PermissionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;


class PermissionController extends Controller
{
  protected PermissionService $permissionService;
  protected string $name = 'Permissions';
  protected $model = Permission::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->permissionService = new PermissionService($this->model, $this->name, 'permission');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.permission.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.permission.form', $this->permissionService->getCreateData());
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.permission.form', $this->permissionService->getEditData($id));
  }

  public function store(PermissionRequest $request): JsonResponse
  {
    return Permission::store($request);
  }

  public function update(PermissionRequest $request, int $id = 0): JsonResponse
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
