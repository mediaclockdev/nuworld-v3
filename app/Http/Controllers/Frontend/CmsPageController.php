<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\Frontend\CmsPageService;
use App\Services\Frontend\HomePageService;

use Illuminate\View\View;

class CmsPageController extends Controller
{
  public function __construct(protected CmsPageService $cmsPageService) {}

  public function cmsPage(string $slug): View
  {
    $data = $this->cmsPageService->getCmsPageData($slug);
    $data['stores'] = Store::where('status', 1)->take(8)->get();

    return view('frontend.pages.cms', $data);
  }
}
