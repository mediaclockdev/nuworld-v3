<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListCard extends Component
{
  public $cardHeader;
  public $addBtn;
  public $exportUrl;
  public $addBtnShow;
  /**
   * Create a new component instance.
   */
  public function __construct(string $cardHeader = '', string $baseRoute = '', bool $addBtnShow = true)
  {
    $this->cardHeader = $cardHeader;
    if ($baseRoute != '') {
      $this->addBtn = $baseRoute . '.create';
      $this->exportUrl = $baseRoute . '.export';
    } else {
      $this->addBtn = null;
      $this->exportUrl = null;
    }


    $this->addBtnShow = $addBtnShow;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.list-card');
  }
}
