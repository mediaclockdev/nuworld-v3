<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\RoleRequest;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Models\RolePermission;
use App\Models\Roles;
use App\Services\Backend\Admin\RoleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
  protected $model = Roles::class;
  public function __construct(protected RoleService $roleService, protected CommonServiceInterface $commonService)
  {
    view()->share('pageTitle', 'Roles');
  }

  public function index()
  {
    return view('backend.pages.admin.role.index', ['cardHeader' => 'Role List']);
  }

  public function create()
  {
    return view('backend.pages.admin.role.form', $this->roleService->getCreateData());
  }

  public function store(RoleRequest $request)
  {
    $roleId = Roles::store($request);

    if ($roleId)
      return RolePermission::store($request->permissions, $roleId);

    return response()->json(['success' => false, 'message' => __('response.error.create', ['item' => 'Role']), 'role_id' => $roleId]);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.admin.role.form', $this->roleService->getEditData($id));
  }

  public function update(RoleRequest $request, int $id = 0)
  {
    $roleId = Roles::store($request, $id);

    if ($roleId)
      return RolePermission::store($request->permissions, $roleId);

    return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Role'])]);
  }

  public function toggle(int $id = 0): JsonResponse
  { 
    return $this->commonService->toggle($this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
