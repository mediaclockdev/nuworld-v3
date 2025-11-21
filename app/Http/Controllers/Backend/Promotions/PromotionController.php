<?php

namespace App\Http\Controllers\Backend\Promotions;

use App\Contracts\CommonServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\BulkDestroyRequest;
use App\Http\Requests\Backend\Promotions\PromotionRequest;
use App\Models\Promotion;
use App\Services\Backend\Promotion\PromotionService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
  protected PromotionService $promotionService;
  protected string $name = 'Promotions';
  protected $model = Promotion::class;

  public function __construct(protected CommonServiceInterface $commonService)
  {
    $this->promotionService = new PromotionService($this->model, $this->name, 'promotion');
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  /**
   * Display the list of coupons.
   */
  public function index(): View
  {
    return view('backend.pages.promotions.promotion.index', [
      'cardHeader' => 'Promotion List',
    ]);
  }

  /**
   * Show the form for creating a new coupon.
   */
  public function create(): View
  {
    return view('backend.pages.promotions.promotion.form', $this->promotionService->getCreateData());
  }

  /**
   * Show the form for editing an existing coupon.
   */
  public function edit(string $id): View
  {
    return view('backend.pages.promotions.promotion.form', $this->promotionService->getEditData($id));
  }

  /**
   * Store a newly created coupon.
   */
  public function store(PromotionRequest $request): JsonResponse
  {
    return Promotion::store($request);
  }

  /**
   * Update an existing coupon.
   */
  public function update(PromotionRequest $request, string $id): JsonResponse
  {
    return Promotion::store($request, $id);
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
    return $this->commonService->toggle($this->model, $id);
  }

  public function getProductVariants(Request $request): JsonResponse
  {
    return response()->json($this->promotionService->getProductVariants($request));
  }

  /**
   * Bulk delete coupons.
   */
  public function multiDestroy(BulkDestroyRequest $request): JsonResponse
  {
    return $this->commonService->multidestroy($request, $this->model);
  }
}
