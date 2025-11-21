<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormCard extends Component
{
  public $formTitle, $backRoute = '', $formId = '';
  /**
   * Create a new component instance.
   */
  public function __construct($formTitle, $backRoute = '', $formId = '')
  {
    $this->formTitle = $formTitle;
    $this->backRoute = $backRoute;
    $this->formId = $formId;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.form-card');
  }
}
