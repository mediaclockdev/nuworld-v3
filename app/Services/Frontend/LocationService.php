<?php

namespace App\Services\Frontend;

use App\Models\Pincode;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LocationService
{
  public function setLocation(Request $request): array
  {
    // Validate the request
    $validator = Validator::make($request->all(), [
      'pincode' => 'required|min:3|max:15',
    ]);

    if ($validator->fails()) {
      return [
        'success' => false,
        'message' => $validator->errors()->first()
      ];
    }

    $pincode = $request->input('pincode');
    $locationApiUrl = config('defaults.location_api');
    $response = Http::get($locationApiUrl . '/' . $pincode);

    if ($response->successful() && !empty($response->json()[0]['PostOffice'])) {
      $data = $response->json();
      $locationData = [
        'Name' => $data[0]['PostOffice'][0]['Name'],
        'District' => $data[0]['PostOffice'][0]['District'],
        'State' => $data[0]['PostOffice'][0]['State'],
        'Country' => $data[0]['PostOffice'][0]['Country'],
        'Pincode' => $pincode,
      ];

      // Store in session
      session(['user_pincode' => $locationData]);

      return [
        'success' => true,
        'location' => $locationData
      ];
    }

    return [
      'success' => false,
      'message' => 'Invalid or unavailable pincode'
    ];
  }

  public function checkPincode(Request $request): array
  {
    $pincodeRecord = Pincode::where(['code' => $request->pincode, 'status' => true])->first();

    return [
      'success' => true,
      'is_serviceable' => (bool) $pincodeRecord,
      'data' => $pincodeRecord
    ];
  }

  public function getAddressModal(): array
  {
    $states = State::all();

    return [
      'html' => view('components.create-address-modal', compact('states'))->render()
    ];
  }
}
