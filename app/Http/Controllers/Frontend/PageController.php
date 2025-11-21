<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
  public function terms()
  {
    return view('frontend.pages.terms');
  }

  public function privacy()
  {
    return view('frontend.pages.privacy');
  }
  public function contactUs()
  {
    return view('frontend.pages.contact');
  }
}
