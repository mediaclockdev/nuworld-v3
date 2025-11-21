<?php

namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ReviewApiRequest;
use App\Http\Requests\Frontend\ReviewRequest;

use App\Services\Frontend\ReviewService;
use Illuminate\Http\{JsonResponse, Request};
use Vinkla\Hashids\Facades\Hashids;

class ReviewController extends Controller
{
  public function __construct(private ReviewService $reviewService) {}


  public function reviewDetails(Request $request)
  {
    //pd($request->all());
    $review = $this->reviewService->getReviewById($request->review_id);
    //pd($review);

    if ($review) {
      return response()->json([
        'success' => true,
        'data' => [
          'id' => $review->id,
          'rating' => $review->rating,
          'productreview' => $review->productreview,
          'images' => $review->reviewImages->map(function ($img) {
            return url('public/storage/reviews/' . $img->image);
          })->toArray()
        ],
        'message' => 'Review found Successfully'
      ]);
    }

    return response()->json([
      'success' => false,
      'data' => [],
      'message' => 'Review not found'
    ]);
  }


  public function submitReview(ReviewApiRequest $request): JsonResponse
  {
    $result = $this->reviewService->createOrUpdateProductReview($request);

    return response()->json($result);
  }
}
