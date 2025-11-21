<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PermissionList extends Component
{
  public $modules;
  public $checkedPermissions;
  /**
   * Create a new component instance.
   */
  public function __construct($modulesList, $checkedPermissions = [])
  {
    $this->modules = $modulesList;
    $this->checkedPermissions = $checkedPermissions;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.permission-list');
  }
}
