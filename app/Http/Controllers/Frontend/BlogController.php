<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\BlogService;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
  function __construct(protected BlogService $blogService) {}
  public function index(): View | \Illuminate\Http\JsonResponse
  {
    $filters = request()->only(['from_date', 'to_date', 'search']);
    $data    = $this->blogService->getBlogs($filters);

    if (request()->ajax() || request()->wantsJson()) {
        $html = view('frontend.pages.blog.blog-list', $data)->render();
        return response()->json([
            'html' => $html,
        ]);
    }
    return view('frontend.pages.blog.index', $data);
  }

  public function blogDetails(?string $slug): View
  {
    $data = $this->blogService->getBlogDetails($slug);
    return view('frontend.pages.blog.blog-details', $data);
  }
}
