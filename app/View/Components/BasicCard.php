<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BasicCard extends Component
{
  public $cardHeader;
  /**
   * Create a new component instance.
   */
  public function __construct(string $cardHeader = '')
  {
    $this->cardHeader = $cardHeader;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.basic-card');
  }
}
