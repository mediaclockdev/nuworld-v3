<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
  public $pages;
  /**
   * Create a new component instance.
   */
  public function __construct($pages)
  {
    $this->pages = $pages;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.pagination');
  }
}
