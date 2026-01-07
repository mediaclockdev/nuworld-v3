<?php

namespace App\Services\Frontend;

use App\Http\Resources\Api\Frontend\CartItemResource;
use App\Models\{Cart, ProductVariant, Pincode, Inventory};
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\{Facades\Auth, Str};
use Illuminate\Support\Facades\DB;

class CartService
{

  public function getCartData(): array
  {
    $data = ['title' => 'Cart'];

    //pd($data['extra_charges']);
    if (Auth::check()) {
      $data['cart_items'] = Cart::with(['productVariant.images' => fn($query) => $query->where('is_default', true)])
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', false)
        ->get();

      $data['saved_for_later_items'] = Cart::with('productVariant')
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', true)
        ->get();
    } else {
      $guestCart = session('guest_cart', []);
      $variantIds = collect($guestCart)->pluck('product_variant_id')->unique()->all();

      $variants = ProductVariant::with('product')
        ->whereIn('id', $variantIds)
        ->get()
        ->keyBy('id');

      $data['cart_items'] = collect($guestCart)
        ->filter(fn($item) => !($item['is_saved_for_later'] ?? false))
        ->map(fn($item) => (object) [
          'product_variant_id' => $item['product_variant_id'],
          'quantity' => $item['quantity'],
          'productVariant' => $variants[$item['product_variant_id']] ?? null,
        ]);

      $data['saved_for_later_items'] = collect($guestCart)
        ->filter(fn($item) => $item['is_saved_for_later'] ?? false)
        ->map(fn($item) => (object) [
          'product_variant_id' => $item['product_variant_id'],
          'quantity' => $item['quantity'],
          'productVariant' => $variants[$item['product_variant_id']] ?? null,
        ]);
    }

    return $data;
  }

  public function getApiCartData(): array
  {
    $data = ['title' => 'Cart'];

    if (Auth::check()) {
      $data['cart_items'] = Cart::with([
        'productVariant.images' => fn($query) => $query->where('is_default', true),
        'productVariant.inventory'
      ])
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', false)
        ->get()
        ->map(function ($item) {
          $item->out_of_stock = ($item->productVariant?->inventory?->quantity ?? 0) < 1;
          return $item;
        });

      $data['saved_for_later_items'] = Cart::with(['productVariant.inventory'])
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', true)
        ->get()
        ->map(function ($item) {
          $item->out_of_stock = ($item->productVariant?->inventory?->quantity ?? 0) < 1;
          return $item;
        });
    } else {
      $guestCart = session('guest_cart', []);
      $variantIds = collect($guestCart)->pluck('product_variant_id')->unique()->all();

      $variants = ProductVariant::with(['product', 'inventory'])
        ->whereIn('id', $variantIds)
        ->get()
        ->keyBy('id');

      $data['cart_items'] = collect($guestCart)
        ->filter(fn($item) => !($item['is_saved_for_later'] ?? false))
        ->map(function ($item) use ($variants) {
          $variant = $variants[$item['product_variant_id']] ?? null;
          return (object) [
            'product_variant_id' => $item['product_variant_id'],
            'quantity' => $item['quantity'],
            'productVariant' => $variant,
            'out_of_stock' => ($variant?->inventory?->quantity ?? 0) < 1,
          ];
        });

      $data['saved_for_later_items'] = collect($guestCart)
        ->filter(fn($item) => $item['is_saved_for_later'] ?? false)
        ->map(function ($item) use ($variants) {
          $variant = $variants[$item['product_variant_id']] ?? null;
          return (object) [
            'product_variant_id' => $item['product_variant_id'],
            'quantity' => $item['quantity'],
            'productVariant' => $variant,
            'out_of_stock' => ($variant?->inventory?->quantity ?? 0) < 1,
          ];
        });
    }

    return $data;
  }


  public function getCounts(): array
  {
    return [
      'count' => Auth::check()
        ? Cart::where('user_id', Auth::id())->where('is_saved_for_later', false)->count()
        : collect(session('guest_cart', []))->where('is_saved_for_later', false)->count(),
      'saved_count' => Auth::check()
        ? Cart::where('user_id', Auth::id())->where('is_saved_for_later', true)->count()
        : collect(session('guest_cart', []))->where('is_saved_for_later', true)->count(),
    ];
  }

  public function isInCart(int $variantId): bool
  {
    return Auth::check()
      ? Cart::where('user_id', Auth::id())->where('product_variant_id', $variantId)->exists()
      : collect(session('guest_cart', []))->contains('product_variant_id', $variantId);
  }

  public function addToCart(array $data, string $action, Request $request): RedirectResponse|JsonResponse
  {
    $variantId = $data['product_variant_id'];
    $quantity = $data['quantity'];
    $isSavedForLater = $data['is_saved_for_later'] ?? false;
    $variantName = Str::limit(ProductVariant::find($variantId)->name ?? 'Item', 50, '');
    $isInWishlist = isInCart($data['product_variant_id'], $data['is_saved_for_later']);
    //dd($isInWishlist, $variantName);
    if ($isInWishlist && $action !== 'buy_now') {
      Cart::where('user_id', Auth::id())->where('product_variant_id', $variantId)->where('is_saved_for_later', 1)->delete();
      return response()->json([
        'success' => true,
        'message' => "Removed {$variantName} from wishlist!",
        'cart_count' => $this->getCounts()['count'],
        'wishlist_count' => $this->getCounts()['saved_count'],
      ]);
      return $this->handleResponse($request, $action, $isSavedForLater, $variantName, 'Item already in wishlist.');
    }

    if (!Auth::check()) {
      $guestCart = session('guest_cart', []);
      $index = array_search($variantId, array_column($guestCart, 'product_variant_id'));

      if ($index !== false) {
        if ($guestCart[$index]['is_saved_for_later'] == $isSavedForLater) {
          $guestCart[$index]['quantity'] += $quantity;
        } else {
          $guestCart[$index]['is_saved_for_later'] = $isSavedForLater;
          $guestCart[$index]['quantity'] = $quantity;
        }
      } else {
        $guestCart[] = [
          'product_variant_id' => $variantId,
          'quantity' => $quantity,
          'is_saved_for_later' => $isSavedForLater
        ];
      }

      session(['guest_cart' => $guestCart]);
      return $this->handleResponse($request, $action, $isSavedForLater, $variantName, 'Item added to cart.');
    }

    $userId = Auth::id();
    $cartItem = Cart::withTrashed()
      ->where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->first();

    if ($cartItem) {
      if ($cartItem->trashed()) {
        $cartItem->restore();
      }
      $cartItem->fill([
        'quantity' => $cartItem->is_saved_for_later === $isSavedForLater
          ? $cartItem->quantity + $quantity
          : $quantity,
        'is_saved_for_later' => $isSavedForLater,
        'updated_by' => $userId
      ])->save();
    } else {
      Cart::create([
        'user_id' => $userId,
        'product_variant_id' => $variantId,
        'quantity' => $quantity,
        'is_saved_for_later' => $isSavedForLater,
        'created_by' => $userId,
        'updated_by' => $userId
      ]);
    }

    return $this->handleResponse($request, $action, $isSavedForLater, $variantName, 'Item successfully added to cart.');
  }

  public function addToCartApi(array $data): JsonResponse
  {
    $userId = Auth::id();
    $variantId = $data['product_variant_id'];
    $quantity = $data['quantity'];
    $variantName = Str::limit(ProductVariant::find($variantId)->name ?? 'Item', 50, '');

    $cartItem = Cart::where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->where('is_saved_for_later', false)
      ->first();

    if ($cartItem) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => "Item is already in your cart.",
      ]);
    }

    Cart::create([
      'user_id' => $userId,
      'product_variant_id' => $variantId,
      'quantity' => $quantity,
      'is_saved_for_later' => false,
    ]);

    return response()->json([
      'success' => true,
      'data' => [],
      'message' => "Item added to cart.",
    ]);
  }

  public function addToWishlist(array $data): JsonResponse
  {
    $userId = Auth::id();
    $variantId = $data['product_variant_id'];
    $quantity = $data['quantity'];
    $variantName = Str::limit(ProductVariant::find($variantId)->name ?? 'Item', 50, '');

    $wishlistItem = Cart::where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->where('is_saved_for_later', true)
      ->first();

    if ($wishlistItem) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => "Item is already in your wishlist.",
      ]);
    }

    Cart::create([
      'user_id' => $userId,
      'product_variant_id' => $variantId,
      'quantity' => $quantity,
      'is_saved_for_later' => true,
    ]);
    // ✅ Reload user's cart (or wishlist)
    $cartItems = Cart::with(['productVariant.product', 'productVariant.category', 'productVariant.inventory', 'productVariant.galleries'])
      ->where('user_id', $userId)
      ->where('is_saved_for_later', true)
      ->get();


    return response()->json([
      'success' => true,
      'data' => CartItemResource::collection($cartItems),
      'message' => "Item added to wishlist.",
    ]);
  }





  public function removeFromCart(int $variantId): void
  {
    if (!Auth::check()) {
      session(['guest_cart' => array_values(array_filter(
        session('guest_cart', []),
        fn($item) => $item['product_variant_id'] != $variantId
      ))]);
    } else {
      Cart::where('user_id', Auth::id())
        ->where('product_variant_id', $variantId)
        ->delete();
    }
  }


  public function removeFromCartApi($variantId): JsonResponse
  {
    $userId = Auth::id();

    $cartItem = Cart::where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->where('is_saved_for_later', false)
      ->first();

    if (!$cartItem) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => 'Item not found in cart.',
      ], 422); // <- Return HTTP status 422 here
    }

    $cartItem->delete();

    return response()->json([
      'success' => true,
      'data' => [],
      'message' => 'Item removed from cart.',
    ]);
  }

  public function removeFromWishlistApi($variantId): JsonResponse
  {
    $userId = Auth::id();

    $wishlistItem = Cart::where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->where('is_saved_for_later', true)
      ->first();

    if (!$wishlistItem) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => 'Item not found in wishlist.',
      ], 422);
    }

    $wishlistItem->delete();

    // ✅ Reload user's cart (or wishlist)
    $cartItems = Cart::with(['productVariant.product', 'productVariant.category', 'productVariant.inventory', 'productVariant.galleries'])
      ->where('user_id', $userId)
      ->where('is_saved_for_later', true)
      ->get();


    return response()->json([
      'success' => true,
      'data' => CartItemResource::collection($cartItems),
      'message' => 'Item removed from wishlist.',
    ]);
  }
  public function updateQuantity(int $variantId, int $quantity): RedirectResponse
  {
    $inventory = Inventory::where('product_variant_id', $variantId)->first();

    // Validate inventory availability
    if (!$inventory || $quantity > $inventory->quantity) {
      $availableQuantity = $inventory->quantity ?? 0;
      if (!Auth::check()) {
        $guestCart = session('guest_cart', []);
        $found = false;
        foreach ($guestCart as &$item) {
          if ($item['product_variant_id'] == $variantId) {
            $item['quantity'] = $availableQuantity;
            $found = true;
            break;
          }
        }
        if (!$found) {
          return back()->withErrors(['cart' => 'Item not found in cart.']);
        }
        session(['guest_cart' => $guestCart]);
      } else {
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)->where('product_variant_id', $variantId)->first();
        if (!$cartItem) {
          return back()->withErrors(['cart' => 'Item not found in cart.']);
        }
        $cartItem->update(['quantity' => $availableQuantity, 'updated_by' => $userId]);
      }
      return back()->withErrors(['quantity.' . $variantId => 'Requested quantity exceeds available inventory.']);
    }

    // Validate max selling quantity only if max_selling_quantity is greater than 0
    if ($inventory->max_selling_quantity > 0 && $quantity > $inventory->max_selling_quantity) {
      if (!Auth::check()) {
        $guestCart = session('guest_cart', []);
        $found = false;
        foreach ($guestCart as &$item) {
          if ($item['product_variant_id'] == $variantId) {
            $item['quantity'] = $inventory->max_selling_quantity;
            $found = true;
            break;
          }
        }
        if (!$found) {
          return back()->withErrors(['cart' => 'Item not found in cart.']);
        }
        session(['guest_cart' => $guestCart]);
      } else {
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)->where('product_variant_id', $variantId)->first();
        if (!$cartItem) {
          return back()->withErrors(['cart' => 'Item not found in cart.']);
        }
        $cartItem->update(['quantity' => $inventory->max_selling_quantity, 'updated_by' => $userId]);
      }
      return back()->withErrors(['quantity.' . $variantId => "Max {$inventory->max_selling_quantity} quantity allowed."]);
    }

    // Update quantity if all validations pass
    if (!Auth::check()) {
      $guestCart = session('guest_cart', []);
      $found = false;
      foreach ($guestCart as &$item) {
        if ($item['product_variant_id'] == $variantId) {
          $item['quantity'] = $quantity;
          $found = true;
          break;
        }
      }
      if (!$found) {
        return back()->withErrors(['cart' => 'Item not found in cart.']);
      }
      session(['guest_cart' => $guestCart]);
    } else {
      $userId = Auth::id();
      $cartItem = Cart::where('user_id', $userId)->where('product_variant_id', $variantId)->first();
      if (!$cartItem) {
        return back()->withErrors(['cart' => 'Item not found in cart.']);
      }
      $cartItem->update(['quantity' => $quantity, 'updated_by' => $userId]);
    }

    return back()->with('success', 'Quantity updated successfully.');
  }

  public function updateQuantityApi(int $variantId, int $quantity): JsonResponse
  {
    $userId = Auth::id();

    if (!$userId) {
      return response()->json([
        'success' => false,
        'message' => 'Authentication required.'
      ], 401);
    }

    $inventory = Inventory::where('product_variant_id', $variantId)->first();
    $availableQty = $inventory->quantity ?? 0;
    $maxAllowed = $inventory->max_selling_quantity ?? 0;

    $cartItem = Cart::where('user_id', $userId)
      ->where('product_variant_id', $variantId)
      ->where('is_saved_for_later', false)
      ->first();

    if (!$cartItem) {
      return response()->json([
        'success' => false,
        'data' => [],
        'message' => 'Item not found in cart.'
      ], 404);
    }

    // Check inventory availability
    if (!$inventory || $quantity > $availableQty) {
      $cartItem->update([
        'quantity' => $availableQty,
        'updated_by' => $userId
      ]);

      return response()->json([
        'success' => false,
        'data' => [
          'available_quantity' => $availableQty
        ],
        'message' => 'Requested quantity exceeds available inventory.',
      ], 422);
    }

    // Check max selling quantity limit
    if ($maxAllowed > 0 && $quantity > $maxAllowed) {
      $cartItem->update([
        'quantity' => $maxAllowed,
        'updated_by' => $userId
      ]);

      return response()->json([
        'success' => false,
        'data' => [
          'allowed_quantity' => $maxAllowed
        ],
        'message' => "Max {$maxAllowed} quantity allowed.",

      ], 422);
    }

    // Valid quantity, update it
    $cartItem->update([
      'quantity' => $quantity,
      'updated_by' => $userId
    ]);

    return response()->json([
      'success' => true,
      'data' => [
        'quantity' => $quantity
      ],
      'message' => 'Quantity updated successfully.',

    ]);
  }



  public function clearCart(): void
  {
    if (!Auth::check()) {
      session()->forget('guest_cart');
    } else {
      Cart::where('user_id', Auth::id())->where('is_saved_for_later', false)->delete();
    }
  }

  private function handleResponse(Request $request, string $action, bool $isSavedForLater, string $variantName, string $defaultMessage): RedirectResponse|JsonResponse
  {
    if ($action === 'buy_now') {
      return redirect()->route('cart.index')->with('success', 'Item added to cart. Redirecting to checkout...');
    }

    if ($request->ajax() || $request->wantsJson()) {
      $cartItems = Auth::check() ? Cart::with('productVariant')
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', false)
        ->get() : collect();

      $wishlistItems = Auth::check() ? Cart::with('productVariant')
        ->where('user_id', Auth::id())
        ->where('is_saved_for_later', true)
        ->get() : collect();

      $cartAction = $isSavedForLater ? false : true;
      return response()->json([
        'success' => true,
        'message' => $isSavedForLater ? "{$variantName} added to wishlist!" : "{$variantName} added to cart!",
        'cart_count' => $this->getCounts()['count'],
        'wishlist_count' => $this->getCounts()['saved_count'],
        'cart_html' => view('frontend.includes.cart_items', ['items' => $cartItems, 'cart_action' => $cartAction, 'display_quantity' => $cartAction])->render(),
        'wishlist_html' => view('frontend.includes.wishlist_items', ['items' => $wishlistItems])->render(),
        'cart_summary_html' => view('components.cart-summary', ['cart_items' => $cartItems])->render(),
        'isInCart'     => isInCart($request->product_variant_id, false),
        'isInWishlist' => isInCart($request->product_variant_id, true),
      ]);
    }

    return back()->with('success', $defaultMessage);
  }
}
