<?php

namespace App\Http\Controllers\Backend\Promotions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\Promotions\CouponRequest;
use App\Models\Coupon;
use App\Services\Backend\Promotion\CouponService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use App\Contracts\CommonServiceInterface;

class CouponController extends Controller
{
  protected CouponService $couponService;
  protected string $name = 'Coupons';
  protected $model = Coupon::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->couponService = new CouponService($this->model, $this->name, 'coupon');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  /**
   * Display the list of coupons.
   */
  public function index(): View
  {
    return view('backend.pages.promotions.coupons.index', [
      'cardHeader' => 'Coupon List',
    ]);
  }

  /**
   * Show the form for creating a new coupon.
   */
  public function create(): View
  {
    return view('backend.pages.promotions.coupons.form', $this->couponService->getCreateData());
  }

  /**
   * Show the form for editing an existing coupon.
   */
  public function edit(string $id): View
  {
    $coupon = Coupon::find($id);
    if (!$coupon) {
      abort(404, 'Coupon not found.');
    }

    return view('backend.pages.promotions.coupons.form', $this->couponService->getEditData($id));
  }

  /**
   * Store a newly created coupon.
   */
  public function store(CouponRequest $request): JsonResponse
  {
    return Coupon::store($request);
  }

  /**
   * Update an existing coupon.
   */
  public function update(CouponRequest $request, string $id): JsonResponse
  {
    return Coupon::store($request, $id);
  }

  /**
   * Soft delete a coupon.
   */
  public function destroy(string $id): JsonResponse
  {
    return $this->commonService->destroy($this->model, $id);
  }

  /**
   * Toggle coupon status (active/inactive).
   */
  public function toggle(string $id): JsonResponse
  {
    return Coupon::toggleStatus($id);
  }

  /**
   * Bulk delete coupons.
   */
  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
