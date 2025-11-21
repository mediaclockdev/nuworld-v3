<?php

namespace App\Http\Controllers\Backend\StoreManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreManage\StoreRequest;
use App\Models\Store;
use App\Services\Backend\CommonService;
use App\Services\Backend\StoreManage\StoreService;
use App\Services\CommonServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    protected string $name = "Store";
    protected $model = Store::class;

    public function __construct(protected CommonService $commonService, protected StoreService $storeService)
    {
        view()->share('pageTitle', "Manage {$this->name}s");
    }

    public function index(): View
    {
        return view('backend.pages.store-manage.stores.index', ['cardHeader' => "Manage {$this->name}s List"]);
    }

    public function create(): View
    {
        return view('backend.pages.store-manage.stores.form', $this->storeService->getCreateData());
    }

    public function store(StoreRequest $request)
    {
        return $this->storeService->storeData($request);
    }

    public function edit(int $id): View
    {
        return view('backend.pages.store-manage.stores.form', $this->storeService->getEditData($id));
    }

    public function update(StoreRequest $request, $id = '')
    {
        return $this->storeService->updateData($request, $id);
    }

    public function toggle($id): JsonResponse
    {
        return $this->model::toggleStatus($id);
    }

    public function destroy(int $id = 0): JsonResponse
    {
        return $this->commonService->destroy($this->model, $id);
    }
}
