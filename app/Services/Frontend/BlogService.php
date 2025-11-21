<?php

namespace App\Services\Frontend;

use App\Models\Blog;
use App\Models\CmsPage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\Frontend\BannerService;


class BlogService
{
  public function __construct(protected BannerService $bannerService) {}
  public function getBlogs(array $filters, int $perPage = 12): array
  {
    $query = Blog::where('status', 1);

    // Apply date filters
    $fromDate = $filters['from_date'] ?? null;
    $toDate = $filters['to_date'] ?? null;

    if ($fromDate && $toDate) {
      $query->whereBetween('created_at', [
        Carbon::parse($fromDate)->startOfDay(),
        Carbon::parse($toDate)->endOfDay()
      ]);
    } elseif ($fromDate) {
      $query->whereDate('created_at', $fromDate);
    } elseif ($toDate) {
      $query->whereDate('created_at', $toDate);
    }

    // Apply search filter
    if (!empty($filters['search'])) {
      $search = $filters['search'];
      $query->where(function (Builder $q) use ($search) {
        $q->where('title', 'like', '%' . $search . '%');
      });
    }

    // Fetch blogs and CMS page
    $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);
    $page = $this->bannerService->getBanner('blog_page_banner', true);

    return [
      'blogs' => $blogs,
      'page' => $page
    ];
  }

  public function getBlogDetails(?string $slug): array
  {
    $blog = Blog::where('slug', $slug)->firstOrFail();
    $page = CmsPage::where('slug', 'about-us')->firstOrFail();

    return [
      'blog' => $blog,
      'page' => $page
    ];
  }
}
