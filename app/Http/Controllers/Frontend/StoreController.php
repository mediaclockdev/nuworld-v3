<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\Frontend\LocationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\{JsonResponse};

class StoreController extends Controller
{
  public function index(): View
  {
      $stores = Store::where('status', 1)->get();
      $title  = 'Our Stores';
      return view('frontend.pages.stores.index', compact('stores', 'title'));
  }

  public function search(Request $request): JsonResponse
  {
      $search = $request->input('q');
      $query  = Store::with('country') // eager load country
                   ->where('status', 1);

      if ($search) {
          $search = trim($search);
          $query->where(function ($q) use ($search) {
            $q->where('pincode',   'like', "%{$search}%")
              ->orWhere('city',     'like', "%{$search}%")
              ->orWhere('state',    'like', "%{$search}%")
              ->orWhereHas('country', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            });
          });
      }

      $stores = $query->get();
      $html = view('frontend.pages.stores.store-list', compact('stores'))->render();
      return response()->json([
        'success' => true,
        'html'    => $html,
        'message' => $stores->isEmpty()
            ? 'No stores found for your search.'
            : 'Stores retrieved successfully.'
      ]);
  }
}
