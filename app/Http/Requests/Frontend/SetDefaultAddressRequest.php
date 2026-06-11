<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class SetDefaultAddressRequest extends BaseRequest
{
    /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'id' => 'required|exists:addresses,id',
      'type' => 'sometimes|in:0,1'
    ];
  }


  public function messages(): array
  {
    return [
      'id.required' => __('validation.required', ['attribute' => 'Address']),
      'id.exists' => __('validation.exists', ['attribute' => 'Address']),
      'type.in' => __('validation.in', ['attribute' => 'Address Type'])
    ];
  }
}
