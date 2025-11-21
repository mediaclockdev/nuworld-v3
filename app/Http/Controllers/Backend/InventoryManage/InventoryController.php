<?php

namespace App\Http\Controllers\Backend\InventoryManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\InventoryManage\InventoryRequest;
use App\Models\Inventory;
use App\Services\Backend\InventoryManage\InventoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class InventoryController extends Controller
{
  protected string $name = 'Stock Products';
  protected $model = Inventory::class;
  protected InventoryService $inventoryService;

  public function __construct()
  {
    $this->inventoryService = new InventoryService($this->model, $this->name, 'inventory');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.inventory-manage.inventories.index', ['cardHeader' => $this->name . ' List']);
  }

  public function edit($id = ''): View
  {
    return view('backend.pages.inventory-manage.inventories.form', $this->inventoryService->getEditData($id));
  }

  public function update(InventoryRequest $request, string $id = ''): JsonResponse
  {
    return $this->inventoryService->updateInventory($request, $id);
  }
}
