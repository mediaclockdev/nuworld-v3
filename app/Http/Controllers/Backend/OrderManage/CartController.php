<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;

class CartController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'Cart';
    $this->model = Cart::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.cart.index', ['cardHeader' => $this->name . ' List']);
  }
}
