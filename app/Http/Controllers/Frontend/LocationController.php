<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Frontend\LocationService;
use Illuminate\Http\Request;
use Illuminate\Http\{JsonResponse};

class LocationController extends Controller
{
  public function __construct(protected LocationService $locationService) {}
  public function set(Request $request)
  {
    return response()->json($this->locationService->setLocation($request));
  }

  public function checkPincode(Request $request): JsonResponse
  {
    return response()->json($this->locationService->checkPincode($request));
  }

  public function getAddressModal(Request $request)
  {
    return response()->json($this->locationService->getAddressModal());
  }
}
