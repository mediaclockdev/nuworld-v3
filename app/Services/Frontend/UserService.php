<?php

namespace App\Services\Frontend;

use App\Http\Requests\Frontend\SetDefaultLocationRequest;
use App\Http\Resources\Api\Frontend\AddressResource;
use App\Models\Address;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class UserService
{
  public function updateProfile($request): JsonResponse
  {
    $user = Auth::user();
    $user->fill($request->except('email'));
    $user->save();

    return response()->json([
      'success' => true,
      'message' => 'Profile updated successfully.'
    ]);
  }

  public function getAddressPageData(): array
  {
    return [
      'title'     => 'Address',
      'states'    => State::all(),
      'addresses' => AddressResource::collection(Auth::user()->addresses),
    ];
  }

  public function getProfileDetailsData(): array
  {
    return [
      'title' => 'Profile Details',
      'user'  => User::find(user()->id),
    ];
  }

  public function createOrUpdateAddress($request): JsonResponse
  {
    try {
      $address = DB::transaction(function () use ($request) {
        // If is_default is checked, set all other addresses to non-default (primary = 0)
        if (!empty($request->is_default)) {
          Address::where('user_id', Auth::id())
            ->where('id', '!=', $request->id ?? 0) // Exclude the current address if updating
            ->update(['primary' => 0]);
        }

        $address = Address::updateOrCreate(
          ['id' => $request->id],
          [
            'user_id'     => Auth::id(),
            'created_by'  => Auth::id(),
            'state_id'    => $request->state,
            'country_id'  => config('defaults.country_id') ?? 0,
            'pin'         => $request->pincode,
            'address_1'   => $request->address,
            'landmark'    => $request->landmark,
            'name'        => $request->name,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'primary'     => !empty($request->is_default) ? 1 : 0,
          ]
        );
        return $address;
      });

      session([
        'user_pincode' => [
          'Name'    => $address->city,
          'Pincode' => $address->pin,
        ],
        'user_addresses' => Auth::user()->addresses
      ]);
      $html = view('frontend.includes.address-block', ['singleAddress' => $address])->render();
      return response()->json([
        'success' => true,
        'message' => $request->id ? 'Address updated successfully.' : 'Address added successfully.',
        'html'    => $html,
        'id'      => $address->id
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function deleteAddress($request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer|exists:addresses,id'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => $validator->errors()->first()
      ]);
    }

    try {
      $address = Address::where('id', $request->id)->where('user_id', Auth::id())->first();

      if (!$address) {
        return response()->json([
          'success' => false,
          'message' => 'Address not found.'
        ]);
      }

      $address->delete();

      return response()->json([
        'success' => true,
        'message' => 'Address deleted successfully.'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Something went wrong: ' . $e->getMessage()
      ], 500);
    }
  }

  public function updatePassword($request): JsonResponse
  {
    $user = Auth::user();
    if (password_verify($request->current_password, $user->password)) {
      $user->password = bcrypt($request->new_password);
      $user->save();
      return response()->json([
        'success' => true,
        'message' => 'Password updated successfully.'
      ]);
    }

    return response()->json([
      'success' => false,
      'message' => 'Current password is incorrect.'
    ]);
  }

  public function updateUserAddress($request): JsonResponse
  {
    try {
      $address = DB::transaction(function () use ($request) {
        // If is_default is checked, set all other addresses to non-default (primary = 0)
        if (!empty($request->is_default)) {
          Address::where('user_id', Auth::id())
            ->where('id', '!=', $request->id ?? 0)
            ->update(['primary' => 0]);
        }

        $address = Address::updateOrCreate(
          ['id' => $request->id],
          [
            'user_id'     => Auth::id(),
            'created_by'  => Auth::id(),
            'state_id'    => $request->state,
            'country_id'  => config('defaults.country_id') ?? 0,
            'pin'         => $request->pincode,
            'address_1'   => $request->address,
            'landmark'    => $request->landmark,
            'name'        => $request->name,
            'phone'       => $request->phone,
            'city'        => $request->city,
            'primary'     => !empty($request->is_default) ? 1 : 0,
          ]
        );
        return $address;
      });

      $sessionData = ['user_addresses' => Auth::user()->addresses];
      if (!empty($request->is_default)) {
        $sessionData['user_pincode'] = [
          'Name'    => $address->city,
          'Pincode' => $address->pin,
        ];
      }
      session($sessionData);

      return response()->json([
        'success' => true,
        'message' => $request->id ? 'Address updated successfully.' : 'Address added successfully.',
        'id'      => $address->id,
        'user_pincode' => session('user_pincode'),
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function getAddressList(): JsonResponse
  {
    try {
      $userAddresses = Auth::user()->addresses;
      $html = view('frontend.includes.list-of-address', ['userAddresses' => $userAddresses])->render();

      $data = $this->getAddressPageData();
      $html2 = view('frontend.includes.list-of-profile-address', ['addresses' => $data['addresses']])->render();


      return response()->json([
        'success' => true,
        'html' => $html,
        'html2' => $html2
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function setDefaultAddress(SetDefaultLocationRequest $request): JsonResponse
  {
    try {
      $address = DB::transaction(function () use ($request) {
        // Reset all other addresses to non-default
        Address::where('user_id', Auth::id())
          ->where('id', '!=', $request->id)
          ->update(['primary' => 0]);

        $address = Address::findOrFail($request->id);
        $address->update([
          'primary'    => !empty($request->is_default) ? 1 : 0,
          'updated_by' => Auth::id(),
        ]);

        return $address;
      });

      session([
        'user_pincode' => [
          'Name'    => $address->city,
          'Pincode' => $address->pin,
        ],
        'user_addresses' => Auth::user()->addresses
      ]);

      return response()->json([
        'success'      => true,
        'message'      => 'Default address updated successfully.',
        'id'           => $address->id,
        'user_pincode' => session('user_pincode'), // Added for header update
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
      ], 500);
    }
  }

  public function setSelectedAddress($request): JsonResponse
  {
    $type = $request->input('type');
    $id = $request->input('id');

    if ($type === 'shipping') {
      session(['selected_shipping_address' => $id]);
    }
    if ($type === 'billing') {
      session(['selected_billing_address' => $id]);
    }
    return response()->json(['status' => 'success']);
  }
}
