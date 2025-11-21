<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;

class ChargesRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name'               => 'required|string|max:100',
      'calculation_method' => 'required|in:fixed,percentage,weight_based,distance_based',
      'charge_amount'      => 'required|numeric|min:0',
      'is_mandatory'       => 'required',
      'conditions'         => 'nullable|json',
      'applicability'      => 'nullable|string|max:100',
      'status'             => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => __('validation.required', ['attribute' => 'Charge Name']),
      'name.string' => __('validation.string', ['attribute' => 'Charge Name']),
      'name.max' => __('validation.maxlength', ['attribute' => 'Charge Name', 'max' => '100']),
      'calculation_method.required' => __('validation.required', ['attribute' => 'Calculation Method']),
      'calculation_method.in' => __('validation.invalid', ['attribute' => 'Calculation Method']),
      'charge_amount.required' => __('validation.required', ['attribute' => 'Charge Amount']),
      'charge_amount.numeric' => __('validation.numeric', ['attribute' => 'Charge Amount']),
      'charge_amount.min' => __('validation.min', ['attribute' => 'Charge Amount', 'min' => '0']),
      'is_mandatory.required' => __('validation.required', ['attribute' => 'Mandatory']),
      'conditions.json' => __('validation.json', ['attribute' => 'Conditions']),
      'applicability.string' => __('validation.string', ['attribute' => 'Applicability']),
      'applicability.max' => __('validation.maxlength', ['attribute' => 'Applicability', 'max' => '100']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
    ];
  }
}
