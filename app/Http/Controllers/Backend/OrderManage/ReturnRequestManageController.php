<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderManage\ReturnItemRequest;
use App\Models\OrderReturn;
use App\Services\Backend\OrderManage\OrderReturnService;
use Illuminate\Contracts\View\View;


class ReturnRequestManageController extends Controller
{

  protected CommonServiceInterface $commonService;
  protected OrderReturnService $orderReturnService;
  protected string $name;
  protected $model, $modelClass;
  public function __construct(CommonServiceInterface $commonService)
  {
    $this->commonService = $commonService;
    $this->name = 'Return Requests';
    $this->model = OrderReturn::class;
    $this->orderReturnService = new OrderReturnService($this->model, $this->name, 'order_return'); // Last one is variable name
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.returns.index', ['cardHeader' => $this->name . ' List']);
  }

  public function edit(int $id = 0): View
  {
    return view('backend.pages.order-manage.returns.form',  $this->orderReturnService->getEditData($id));
  }

  public function update(ReturnItemRequest $request, int $id = 0)
  {
    return $this->model::processUpdate($request, $id);
  }
}
