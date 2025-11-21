<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class PincodeRequest extends BaseRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'code'          => 'required|string|min:3|max:15|unique:pincodes,code,' . $this->route('id') . ',id,deleted_at,NULL',
      'estimate_days' => 'required|string|max:15',
    ];
  }

  public function messages(): array
  {
    return [
      'code.required' => __('validation.required', ['attribute' => 'Pincode']),
      'code.min' => __('validation.min', ['attribute' => 'Pincode', 'min' => '3']),
      'code.max' => __('validation.max', ['attribute' => 'Pincode', 'max' => '15']),
      'code.unique' => __('validation.unique', ['attribute' => 'Pincode']),
      'estimate_days.required' => __('validation.required', ['attribute' => 'Estimate Days']),
      'estimate_days.max' => __('validation.max', ['attribute' => 'Estimate Days', 'max' => '15']),
      'estimate_days.string' => __('validation.string', ['attribute' => 'Estimate Days']),
    ];
  }
}
