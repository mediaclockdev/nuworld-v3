<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AddtoCartRequest;
use App\Http\Requests\Frontend\ProductVariantIDRequest;
use App\Http\Requests\Frontend\UpdateCartQuantityRequest;
use App\Services\Frontend\CartService;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\View\View;

class CartController extends Controller
{
  public function __construct(protected CartService $cartService) {}

  public function index(): View
  {
    return view('frontend.pages.cart.index', $this->cartService->getCartData());
  }

  public function count(): JsonResponse
  {
    return response()->json($this->cartService->getCounts());
  }

  public function isInCart(ProductVariantIDRequest $request): JsonResponse
  {
    return response()->json(['in_cart' => $this->cartService->isInCart($request->product_variant_id)]);
  }

  public function add(AddtoCartRequest $request): RedirectResponse|JsonResponse
  {
    return $this->cartService->addToCart($request->validated(), $request->input('action', 'add_to_cart'), $request);
  }

  public function remove(ProductVariantIDRequest $request): RedirectResponse
  {
    $this->cartService->removeFromCart($request->product_variant_id);
    return back()->with('success', 'Item removed from cart.');
  }


  public function updateQuantity(UpdateCartQuantityRequest $request): RedirectResponse
  {
    // dd($request->product_variant_id, $request->quantity);
    return $this->cartService->updateQuantity($request->product_variant_id, $request->quantity);
  }

  public function clear(): RedirectResponse
  {
    $this->cartService->clearCart();
    return back()->with('success', 'Cart cleared.');
  }
}
