<?php

namespace App\Services\Frontend;

use App\Models\Blog;
use App\Models\CmsPage;
use App\Models\FaqCategory;

class CmsPageService
{

  public function __construct(protected HomePageService $homePageService) {}

  public function getCmsPageData(string $slug): array
  {
    $page = CmsPage::where('slug', $slug)->firstOrFail();
    $homeSections = $this->homePageService->getHomePageData();
    $blogs = Blog::where('status', 1)->orderBy('created_at', 'desc')->get();
    $faqCategories = FaqCategory::with('faqs')->where('status', 1)->get();

    return [
      'page'          => $page,
      'keepFlowing'   => $homeSections['keepFlowing'],
      'subscribe'     => $homeSections['subscribe'],
      'title'         => $page->title ?? 'CMS',
      'blogs'         => $blogs,
      'faqCategories' => $faqCategories
    ];
  }
}
