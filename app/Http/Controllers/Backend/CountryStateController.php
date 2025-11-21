<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;

class CountryStateController extends Controller
{

  public function getStatesByCountry($country_id): JsonResponse
  {
    $country_id = Hashids::decode($country_id)[0];

    $states = State::where([['country_id', $country_id], ['status', 1]])->get();
    return response()->json($states);
  }
}
