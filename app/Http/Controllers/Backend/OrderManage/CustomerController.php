<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\OrderManage\CustomerRequest;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Models\AdminActivity;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
  protected string $name;
  protected $model;

  public function __construct()
  {
    $this->name = 'Customer';
    $this->model = User::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  public function index(): View
  {
    return view('backend.pages.order-manage.customers.index', ['cardHeader' => $this->name . ' List']);
  }

  public function create(): View
  {
    return view('backend.pages.order-manage.customers.form', ['cardHeader' => 'Create ' . $this->name, 'customer' => new User()]);
  }

  public function edit(int $id = 0): View
  {
    $customer = User::find($id);

    if (!$customer)
      abort(404);

    return view('backend.pages.order-manage.customers.form', ['cardHeader' => 'Edit ' . $this->name, 'customer' => $customer]);
  }

  public function store(CustomerRequest $request): JsonResponse
  {
    $newPassword = User::store($request);

    if ($newPassword === false)
      return response()->json(['success' => false, 'message' => __('response.error.create', ['item' => 'Customer'])]);

    //New User Send Mail
    $email = $request->customeremail;
    $params = [
      'name' => $request->firstname . ' ' . $request->lastname,
      'password' => $newPassword
    ];

    app('SendEmailService')->WelcomeMail($email, $params);

    return response()->json(['success' => true, 'message' => __('response.success.create', ['item' => 'Customer'])]);
  }

  public function toggle(int $id = 0): JsonResponse
  {
    return User::toggleStatus($id);
  }

  public function destroy(int $id = 0): JsonResponse
  {
    return User::remove($id);
  }

  public function update(CustomerRequest $request, int $id = 0): JsonResponse
  {
    $update = User::store($request, $id);

    if ($update === true)
      return response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Customer'])]);

    return response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Customer'])]);
  }


  public function loginHistory(): JsonResponse
  {
    $rows = 10; // Last 10 records
    $loginHistory = AdminActivity::getLoginHistory($rows);

    return response()->json(['success' => true, 'data' => $loginHistory]);
  }


  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    $decodedIds = $request->decodedIds();

    foreach ($decodedIds as $id) {
      $result = User::remove($id)->getData(true);
      if ($result["success"] === false) {
        return response()->json($result);
      }
    }

    return response()->json(['success' => true, 'message' => __('response.success.delete', ['item' => 'Customer(s)'])]);
  }
}
