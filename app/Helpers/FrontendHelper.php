<?php

use Illuminate\Support\Facades\{Auth, Session};
use App\Models\{Cart};
use Illuminate\Support\Str;


if (!function_exists('cartCount')) {
  function cartCount(): int
  {
    if (Auth::check()) {
      return Cart::where('user_id', Auth::id())
        ->where('is_saved_for_later', false)
        ->sum('quantity'); // Use sum to account for quantities
    }

    $cart = Session::get('guest_cart', []);

    return array_reduce($cart, function ($total, $item) {
      // Only count items not saved for later
      if (!($item['is_saved_for_later'] ?? false)) {
        return $total + ($item['quantity'] ?? 1);
      }
      return $total;
    }, 0);
  }
}
if (!function_exists('cartQuantity')) {
  function cartQuantity($variantId)
  {
    if (!auth()->check()) {
      return 0;
    }

    return Cart::where('user_id', auth()->id())
      ->where('is_saved_for_later', false)
      ->where('product_variant_id', $variantId)
      ->sum('quantity'); // or ->value('quantity')
  }
}

if (!function_exists('isInCart')) {
  function isInCart(int $productVariantId, bool $includeSavedForLater = false): bool
  {
    if (Auth::check()) {
      $query = Cart::where('user_id', Auth::id())
        ->where('product_variant_id', $productVariantId);

      if (!$includeSavedForLater) {
        $query->where('is_saved_for_later', false);
      }

      if ($includeSavedForLater) {
        $query->where('is_saved_for_later', true);
      }

      return $query->exists();
    }

    $guestCart = Session::get('guest_cart', []);

    foreach ($guestCart as $item) {
      if (($item['product_variant_id'] ?? null) == $productVariantId) {
        if ($includeSavedForLater) {
          return true;
        }
        return !($item['is_saved_for_later'] ?? false);
      }
    }

    return false;
  }
}

if (!function_exists('getCartItemQuantity')) {
  function getCartItemQuantity(int $productVariantId): int
  {
    if (Auth::check()) {
      $item = Cart::where('user_id', Auth::id())
        ->where('product_variant_id', $productVariantId)
        ->where('is_saved_for_later', false)
        ->first();

      return $item ? $item->quantity : 0;
    }

    $guestCart = Session::get('guest_cart', []);

    foreach ($guestCart as $item) {
      if (($item['product_variant_id'] ?? null) == $productVariantId &&
        !($item['is_saved_for_later'] ?? false)
      ) {
        return $item['quantity'] ?? 1;
      }
    }

    return 0;
  }
}

if (!function_exists('savedForLaterCount')) {
  function savedForLaterCount(): int
  {
    if (Auth::check()) {
      return Cart::where('user_id', Auth::id())
        ->where('is_saved_for_later', 1)
        ->sum('quantity');
    }

    $cart = Session::get('guest_cart', []);

    return array_reduce($cart, function ($total, $item) {
      if ($item['is_saved_for_later'] ?? false) {
        return $total + ($item['quantity'] ?? 1);
      }
      return $total;
    }, 0);
  }
}

if (!function_exists('generateOrderNumber')) {
  function generateOrderNumber(): string
  {
    return 'ORD-' . time() . '-' . Str::upper(Str::random(6));
  }
}
