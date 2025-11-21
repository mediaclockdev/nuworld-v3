<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
  public function index(): View
  {
    $data = [
      'title' => 'Wishlist',
      'productVariantData' => collect(),
    ];

    if (!Auth::check()) {
      return view('frontend.pages.user.auth.login-register');
    }

    $savedItems = Cart::with('productVariant')
      ->where('user_id', Auth::id())
      ->where('is_saved_for_later', 1)
      ->get();

    $productVariantData = $savedItems->map(function ($item) {
      // Fetch variant attribute with attribute_id = 1
      $attribute = DB::table('product_variant_attributes')
        ->where('product_variant_id', $item->product_variant_id)
        ->where('attribute_id', 1)
        ->first();

      // Fetch value details if attribute exists
      $attributeValue = $attribute
        ? DB::table('product_attribute_values')
        ->select('value', 'value_details')
        ->where('id', $attribute->attribute_value_id)
        ->first()
        : (object) ['value' => null, 'value_details' => null];

      // Fetch image
      $variantImage = DB::table('product_variant_images')
        ->where('product_variant_id', $item->product_variant_id)
        ->where('is_default', 1)
        ->first();

      // Get media gallery details
      $media = $variantImage
        ? DB::table('media_galleries')
        ->select('file_name', 'file_type')
        ->where('id', $variantImage->media_gallery_id)
        ->first()
        : (object) ['file_name' => null, 'file_type' => null];

      return (object) array_merge((array) $attributeValue, (array) $media);
    });

    $data['saved_for_later_items'] = $savedItems;
    $data['productVariantData'] = $productVariantData;

    return view('frontend.pages.wishlist.index', $data);
  }
}
