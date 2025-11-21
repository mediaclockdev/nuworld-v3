<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseRequest;

class AddressRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'address1' => 'required|max:255',
      'address2' => 'nullable|max:255',
      'landmark' => 'nullable|max:255',
      'city' => 'required|max:255',
      'state' => 'required|exists:states,id',
      'country' => 'required|max:100',
      'zip_code' => 'required|max:15',
    ];
  }

  public function messages(): array
  {
    return [
      'address1.required' => __('validation.required', ['attribute' => 'Address']),
      'address1.max' => __('validation.maxlength', ['attribute' => 'Address']),
      'address2.max' => __('validation.maxlength', ['attribute' => 'Address']),
      'landmark.max' => __('validation.maxlength', ['attribute' => 'Landmark']),
      'city.required' => __('validation.required', ['attribute' => 'City']),
      'city.max' => __('validation.maxlength', ['attribute' => 'City']),
      'state.required' => __('validation.required', ['attribute' => 'State']),
      'state.exists' => __('validation.exists', ['attribute' => 'State']),
      'country.required' => __('validation.required', ['attribute' => 'Country']),
      'country.max' => __('validation.maxlength', ['attribute' => 'Country']),
      'zip_code.required' => __('validation.required', ['attribute' => 'Zip Code']),
      'zip_code.max' => __('validation.maxlength', ['attribute' => 'Zip Code']),
    ];
  }
}
