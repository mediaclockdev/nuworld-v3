<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProfilePictureUpload extends Component
{
  /**
   * Create a new component instance.
   */
  public $imageLink;
  public $route;
  public function __construct($imageLink, $route)
  {
    $this->imageLink = $imageLink;
    $this->route = $route;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return view('components.profile-picture-upload');
  }
}
