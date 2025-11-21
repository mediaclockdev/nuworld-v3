<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PhoneNumber extends Component
{
  public $required;
  public $previousValue;
  public $id, $name;
  public $label;
  /**
   * Create a new component instance.
   */
  public function __construct($required = true, $previousValue = null, $id = 'phone', $name = 'phone', $label = 'Phone')
  {
    $this->required = $required;
    $this->previousValue = $previousValue;
    $this->id = $id;
    $this->name = $name;
    $this->label = $label;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.phone-number');
  }
}
