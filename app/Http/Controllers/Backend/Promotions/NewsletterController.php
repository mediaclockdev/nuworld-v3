<?php

namespace App\Http\Controllers\Backend\Promotions;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class NewsletterController extends Controller
{
  public function __construct()
  {
    view()->share('pageTitle', 'Manage Newsletters');
  }

  public function index(): View
  {
    return view('backend.pages.promotions.newsletter.index', ['cardHeader' => 'Newsletter List']);
  }
}
