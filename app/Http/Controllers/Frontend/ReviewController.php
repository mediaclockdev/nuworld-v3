<?php

namespace App\Http\Controllers\Frontend;

use App\Services\Frontend\ReviewService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ReviewRequest;
use App\Models\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
  public function __construct(protected ReviewService $reviewService) {}
  public function getReview(Request $request)
  {
    $review = $this->reviewService->getReviewById($request->review_id);

    if ($review) {
      return response()->json([
        'success' => true,
        'data' => [
          'review' => $review->review,
          'rating' => $review->rating,
          'productreview' => $review->productreview,
          'images' => $review->reviewImages->map(function ($img) {
            return url('public/storage/reviews/' . $img->image);
          })->toArray()
        ]
      ]);
    }

    return response()->json(['success' => false]);
  }

  public function submitReview(ReviewRequest $request): JsonResponse
  {
    $result = $this->reviewService->createOrUpdateReview($request);

    return response()->json($result);
  }
}
