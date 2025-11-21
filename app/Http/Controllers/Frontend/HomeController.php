<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SubscribeEmailRequest;
use App\Services\Frontend\HomePageService;
use App\Services\Frontend\ProductCardService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\View\View;
use Vinkla\Hashids\Facades\Hashids;

class HomeController extends Controller
{

  public function __construct(protected HomePageService $homePageService, protected ProductCardService $productCardService) {}
  /**
   * Display the home page with product categories.
   */
  public function index(): View
  {
    $data = $this->homePageService->getHomePageData();

    return view('frontend.pages.home.index', $data);
  }

  /**
   * Fetch and filter the collections based on user request.
   */
  public function getFilteredCollections(Request $request): View
  {
    $products = $this->productCardService->getProductsWithVariants($request);

    return view('frontend.includes.products_scroller_cards', compact('products'));
  }

  /**
   * Retrieve variant details based on the request parameters.
   */


  public function getVariantDetails(Request $request): JsonResponse
  {
    $result = $this->productCardService->getVariantDetails(Hashids::decode($request->variant_id)[0],   $request->color);

    return response()->json($result);
  }

  public function subscribeEmail(SubscribeEmailRequest $request): JsonResponse
  {
    return $this->homePageService->subscribeEmailService($request);
  }

  public function confirmEmail($hash)
  {
    $this->homePageService->confirmEmailService($hash);
    return redirect()->route('home');


    // return view('frontend.email_status', $this->homePageService->confirmEmailService($hash));
  }
}
