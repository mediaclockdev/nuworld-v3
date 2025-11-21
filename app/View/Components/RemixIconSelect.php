<?php

namespace App\View\Components;

use App\Models\RemixIcon;
use Illuminate\View\Component;

class RemixIconSelect extends Component
{
  public $id;
  public $name;
  public $remixIcons;
  public $selected;

  public function __construct(string $id = 'remixicon', string $name = 'remixicon', string $selected = null)
  {
    $this->id = $id;
    $this->name = $name;
    $this->selected = $selected;
    $this->remixIcons = RemixIcon::all();
  }

  public function render()
  {
    return view('components.remix-icon-select');
  }
}
