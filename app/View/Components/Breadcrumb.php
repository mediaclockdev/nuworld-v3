<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
  public $breadcrumbs;
  public $pageTitle;
  public $skipLevels;

  /**
   * Create a new component instance.
   *
   * @param string $pageTitle
   */
  public function __construct(string $pageTitle, array $skipLevels = [])
  {
    $this->pageTitle = $pageTitle;
    $this->skipLevels = $skipLevels;

    $this->breadcrumbs = $this->generateBreadcrumbs();
  }

  /**
   * Generate breadcrumbs from the current URL.
   *
   * @return array
   */
  protected function generateBreadcrumbs()
  {
    $path = request()->path(); // e.g., "sundew-ecomm/admin/system/modules/create"
    $segments = array_values(array_filter(explode('/', $path))); // Split and clean
    $baseUrl = rtrim(config('app.url'), '/'); // e.g., "http://localhost/sundew-ecomm"
    $breadcrumbs = [];
    $breadcrumbUrl = $baseUrl;

    // Remove base path (e.g., "sundew-ecomm")
    $basePath = trim(parse_url($baseUrl, PHP_URL_PATH), '/');
    if ($basePath && reset($segments) === $basePath) {
      array_shift($segments);
    }

    // Handle "admin" as "Home" and include it in the URL progression
    if (reset($segments) === 'admin') {
      $breadcrumbs[] = [
        'label' => 'Home',
        'url' => "$baseUrl/admin/dashboard",
      ];
      $breadcrumbUrl .= '/admin'; // Ensure "admin" is part of the URL chain
      array_shift($segments);
    }

    // Build breadcrumbs with full path
    foreach ($segments as $index => $segment) {
      $breadcrumbUrl .= '/' . $segment;
      if (!in_array($index, $this->skipLevels)) {
        $breadcrumbs[] = [
          'label' => ucwords(str_replace('-', ' ', $segment)),
          'url' => $breadcrumbUrl,
        ];
      }
    }

    return $breadcrumbs;
  }



  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|\Closure|string
   */
  public function render()
  {
    return view('components.breadcrumb');
  }
}
