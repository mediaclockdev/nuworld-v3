<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Admin\AdminUserRequest;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Models\Admin;
use App\Services\Backend\Admin\AdminUserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class AdminUserController extends Controller
{
  protected $model = Admin::class;

  public function __construct(protected CommonServiceInterface $commonService, protected AdminUserService $adminUserService)
  {
    view()->share('pageTitle', 'Manage Admin Users');
  }
  public function index(): View
  {
    return view('backend.pages.admin.users.index', ['cardHeader' => 'Admin Users List']);
  }

  public function create(): View
  {
    return view('backend.pages.admin.users.form', $this->adminUserService->getCreateData());
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.admin.users.form', $this->adminUserService->getEditData($id));
  }

  public function store(AdminUserRequest $request): JsonResponse
  {
    return $this->adminUserService->addNewUser($request, $this->model);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return $this->commonService->toggle($this->model, $id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  public function update(AdminUserRequest $request, int $id = 0): JsonResponse
  {
    return $this->commonService->update($request, $this->model, $id);
  }

  public function multidestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }

  public function resendLoginMail(int $id = 0): JsonResponse
  {
    return $this->adminUserService->resendLoginMail($id);
  }
}
