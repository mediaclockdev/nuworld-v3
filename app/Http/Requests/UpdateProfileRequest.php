<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'firstname' => 'required|min:3',
      'middlename' => 'nullable|string',
      'lastname' => 'nullable|string',
      'usermobile' => 'required|min:8|max:16',
      'useremail' => 'required|email',
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
      'firstname.required' => __('validation.required', ['attribute' => 'First Name']),
      'firstname.min' => __('validation.minlength', ['attribute' => 'First Name', 'min' => 3]),
      'usermobile.required' => __('validation.required', ['attribute' => 'Phone Number']),
      'usermobile.min' => __('validation.mindigits', ['attribute' => 'Phone Number', 'min' => 8]),
      'usermobile.max' => __('validation.maxdigits', ['attribute' => 'Phone Number', 'max' => 16]),
      'useremail.required' => __('validation.required', ['attribute' => 'Email']),
      'useremail.email' => __('validation.invalid', ['attribute' => 'Email Format']),
      'address1.required' => __('validation.required', ['attribute' => 'Address']),
      'address1.max' => __('validation.maxlength', ['attribute' => 'Address Line 1']),
      'address2.max' => __('validation.maxlength', ['attribute' => 'Address Line 2']),
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
