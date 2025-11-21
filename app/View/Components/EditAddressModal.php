<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditAddressModal extends Component
{
  /**
   * Create a new component instance.
   */

  public $states;
  public function __construct($states = [])
  {
    $this->states = $states;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.edit-address-modal');
  }
}
