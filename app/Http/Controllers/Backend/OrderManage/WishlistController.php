<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;

class WishlistController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'Wishlist';
    $this->model = Cart::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.wishlist.index', ['cardHeader' => $this->name . ' List']);
  }
}
