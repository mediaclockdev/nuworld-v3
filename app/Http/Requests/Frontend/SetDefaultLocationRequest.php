<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class SetDefaultLocationRequest extends BaseRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'id' => 'required|integer|exists:addresses,id',
      'is_default' => 'required|boolean',
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'id.required' => 'Address ID is required.',
      'id.exists' => 'The selected address ID is invalid.',
      'is_default.required' => 'Default address selection is required.',
      'is_default.boolean' => 'The default address field must be true or false.',
    ];
  }
}
