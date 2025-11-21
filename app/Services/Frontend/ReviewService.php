<?php

namespace App\Services\Frontend;

use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;

class ReviewService
{
  public function getReviewById($reviewId)
  {
    return ProductReview::where('id', $reviewId)
      ->where('user_id', Auth::id())
      ->first();
  }

  public function createOrUpdateReview($request)
  {
    $review = $request->filled('review_id')
      ? ProductReview::where('id', $request->review_id)->where('user_id', Auth::id())->first()
      : new ProductReview(['user_id' => Auth::id()]);

    if (!$review) {
      return ['success' => false, 'message' => 'Review not found or access denied.'];
    }

    $review->fill([
      'variant_id' => $request->product_variant_id,
      'productreview' => $request->productreview,
      'rating' => $request->rating,
      'status' => 1,
      'created_at' => now(),
      'updated_at' => now(),
    ])->save();

    // If updating and new images are uploaded, delete old images
    if ($request->filled('review_id') && $request->hasFile('images')) {
      foreach ($review->reviewImages as $img) {
        Storage::disk('public')->delete('reviews/' . $img->image);
        $img->delete();
      }
    }

    // Upload new images
    foreach ((array) $request->file('images') as $image) {
      $filename = time() . '_' . $image->getClientOriginalName();
      $image->storeAs('reviews', $filename, 'public');
      $review->reviewImages()->create(['image' => $filename]);
    }

    return [
      'success' => true,
      'message' => $request->filled('review_id')
        ? 'Review updated successfully!'
        : 'Thank you for your review!'
    ];
  }

  public function createOrUpdateProductReview($request)
  {

    $review = $request->filled('review_id')
      ? ProductReview::where('id', $request->review_id)
      ->where('user_id', Auth::id())
      ->first()
      : new ProductReview(['user_id' => Auth::id()]);

    if (!$review) {
      return ['success' => false, 'message' => 'Review not found or access denied.'];
    }

    $review->fill([
      'variant_id' => $request->product_variant_id,
      'productreview' => $request->productreview,
      'rating' => $request->rating,
      'status' => 1,
      'created_by' => Auth::id(),
      'created_at' => now(),
      'updated_at' => now(),
      'updated_by' => Auth::id()
    ])->save();

    // If updating and new images are uploaded, delete old images
    if ($request->filled('review_id') && $request->hasFile('images')) {
      foreach ($review->reviewImages as $img) {
        Storage::disk('public')->delete('reviews/' . $img->image);
        $img->delete();
      }
    }

    // Upload new images
    if ($request->hasFile('images')) {
      $uploadedImages = is_array($request->file('images'))
        ? $request->file('images')
        : [$request->file('images')]; // Support single or multiple files

      foreach ($uploadedImages as $image) {
        if ($image && $image->isValid()) {
          $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
          $image->storeAs('reviews', $filename, 'public');
          $review->reviewImages()->create(['image' => $filename]);
        }
      }
    }

    return [
      'success' => true,
      'data' => [
        'id' => $review->id,
        'productreview' => $review->productreview ?? '',
        'rating' => $review->rating,
        'variant_id' => Hashids::encode($review->variant_id), // Encode the variant_id
      ],
      'message' => $request->filled('review_id')
        ? 'Review updated successfully!'
        : 'Thank you for your review!',
    ];
  }
}
