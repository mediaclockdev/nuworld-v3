<?php

namespace App\Http\Controllers\Backend\System;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\System\Department\DepartmentRequest;
use App\Models\Department;
use App\Services\Backend\System\DepartmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;


class DepartmentController extends Controller
{
  protected DepartmentService $departmentService;
  protected string $name = 'Department';
  protected $model = Department::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->departmentService = new DepartmentService($this->model, $this->name, 'department');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.system.department.index', ['cardHeader' => 'Department List']);
  }

  public function create(): View
  {
    return view('backend.pages.system.department.form', $this->departmentService->getCreateData());
  }

  public function store(DepartmentRequest $request): JsonResponse
  {
    return Department::store($request);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.system.department.form',  $this->departmentService->getEditData($id));
  }

  public function update(DepartmentRequest $request, int $id = 0): JsonResponse
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
