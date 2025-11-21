<?php

namespace App\Http\Controllers\Backend\ContentManage;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MenuBuilderController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'Menu Builder';
    $this->model = Menu::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    $categories = ProductCategory::generateTree('active');
    return view('backend.pages.content-manage.menu-builder.index', ['cardHeader' => $this->name . ' List', 'categories' => $categories]);
  }

  public function save(Request $request)
  {
    $items = json_decode($request->validate(['menu' => 'required|json'])['menu'], true);

    if (empty($items)) {
      return response()->json(['status' => 'error', 'message' => 'No items to save'], 400);
    }

    $menu = Menu::firstOrCreate(['name' => 'Main Menu']);
    $menu->items()->delete();
    MenuItem::saveItems($items, $menu->id);

    return response()->json(['status' => 'success'], 200);
  }

  public function menuItems()
  {
    $items = MenuItem::where('parent_id', 0)->orderBy('sequence')->get();
    return view('backend.pages.content-manage.menu-builder.menu-item', compact('items'));
  }
}
