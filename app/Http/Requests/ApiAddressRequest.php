<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ApiAddressRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'id' => 'nullable',
      'name' => 'required|max:50',
      'email' => 'nullable|email|max:255',
      'phone' => 'required',
      'pincode' => 'required|min:3|max:15',
      'state_id' => 'required|exists:states,id',
      'country_id' => 'required|exists:countries,id',
      'address_line_1' => 'required|max:100',
      'address_line_2' => 'nullable|max:100',
      'landmark' => 'nullable|max:100',
      'city_name' => 'required|max:100',
      'primary' => 'nullable|boolean',
    ];
  }

  public function messages(): array
  {
    return [
      '*.required' => __('validation.required', ['attribute' => ':attribute']),
      'name.max' => __('validation.maxlength', ['attribute' => 'Name', 'max' => 50]),
      'email.email' => __('validation.email', ['attribute' => 'Email']),
      'email.max' => __('validation.maxlength', ['attribute' => 'Email', 'max' => 255]),
      'pincode.min' => __('validation.minlength', ['attribute' => 'Pincode', 'min' => 3]),
      'pincode.max' => __('validation.maxlength', ['attribute' => 'Pincode', 'max' => 15]),
      'state_id.exists' => __('validation.exists', ['attribute' => 'State']),
      'country_id.exists' => __('validation.exists', ['attribute' => 'Country']),
      'address_line_1.max' => __('validation.maxlength', ['attribute' => 'Address', 'max' => 100]),
      'address_line_2.max' => __('validation.maxlength', ['attribute' => 'Address Line 2', 'max' => 100]),
      'landmark.max' => __('validation.maxlength', ['attribute' => 'Landmark', 'max' => 100]),
      'city_name.max' => __('validation.maxlength', ['attribute' => 'City', 'max' => 100]),
    ];
  }

  protected function prepareForValidation(): void
  {
    // `id` comes as plain integer
    if ($this->has('id') && !empty($this->get('id'))) {
      $this->merge(['id' => (int) $this->get('id')]);
    }

    // `state_id` and `country_id` come as Hashids
    foreach (['state_id', 'country_id'] as $field) {
      if ($this->has($field) && !empty($this->get($field))) {
        $this->merge([
          $field => Hashids::decode((string) $this->get($field))[0] ?? null,
        ]);
      }
    }
  }
}
