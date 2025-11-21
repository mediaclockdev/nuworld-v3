<?php

namespace App\Http\Requests\Backend\InventoryManage;

use App\Http\Requests\BaseRequest;

class InventoryRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'stock' => 'required|numeric|min:0|max:999999',
      'threshold' => 'nullable|numeric|max:999999',
      'max_selling_quantity' => 'nullable|numeric|max:999999'
    ];
  }

  public function messages(): array
  {
    return [
      'stock.required' => __('validation.required', ['attribute' => 'Stock']),
      'stock.numeric' => __('validation.numeric', ['attribute' => 'Stock']),
      'stock.min' => __('validation.minvalue', ['attribute' => 'Stock', 'min' => 1]),
      'stock.max' => __('validation.maxvalue', ['attribute' => 'Stock', 'max' => 999999]),
      'threshold.required' => __('validation.required', ['attribute' => 'Threshold']),
      'threshold.numeric' => __('validation.numeric', ['attribute' => 'Threshold']),
      'threshold.min' => __('validation.minvalue', ['attribute' => 'Threshold', 'min' => 1]),
      'threshold.max' => __('validation.maxvalue', ['attribute' => 'Threshold', 'max' => 999999]),
      'max_selling_quantity.required' => __('validation.required', ['attribute' => 'Max Selling Quantity']),
      'max_selling_quantity.numeric' => __('validation.numeric', ['attribute' => 'Max Selling Quantity']),
      'max_selling_quantity.min' => __('validation.minvalue', ['attribute' => 'Max Selling Quantity', 'min' => 0]),
      'max_selling_quantity.max' => __('validation.maxvalue', ['attribute' => 'Max Selling Quantity', 'max' => 999999])
    ];
  }
}
