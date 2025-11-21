<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderManage\PincodeRequest;
use Illuminate\Http\Request;
use App\Models\Pincode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PincodeController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'pincode';
    $this->model = Pincode::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.pincode.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {

    return view('backend.pages.order-manage.pincode.form', ['cardHeader' => 'Create ' . $this->name, 'pinCode' => new $this->model()]);
  }

  public function store(PincodeRequest $request): JsonResponse
  {
    return $this->model::store($request);
  }

  public function edit($id = ''): View
  {
    $pinCode = Pincode::find($id);
    if (!$pinCode)
      abort(404);

    return view('backend.pages.order-manage.pincode.form', ['cardHeader' => 'Edit ' . $this->name, 'pinCode' => $pinCode]);
  }

  public function update(PincodeRequest $request, $id = ''): JsonResponse
  {
    return $this->model::store($request, $id);
  }

  public function toggle($id): JsonResponse
  {
    return $this->model::toggleStatus($id);
  }
}
