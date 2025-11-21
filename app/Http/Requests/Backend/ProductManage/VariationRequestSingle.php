<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class VariationRequestSingle extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;

    return [
      'regular_price' => 'required|numeric|min:1|max:999999',
      'sale_price' => 'nullable|numeric|max:999999',
      'stock' => 'required|numeric|min:1|max:999999',
      'threshold' => 'nullable|numeric|max:999999',
      'purchase_limit' => 'nullable|numeric|max:999999',
    ];
  }

  public function messages(): array
  {
    return [
      'variant_id.required' => __('validation.required', ['attribute' => 'Variation']),
      'regular_price.required' => __('validation.required', ['attribute' => 'Regular Price']),
      'regular_price.numeric' => __('validation.numeric', ['attribute' => 'Regular Price']),
      'regular_price.min' => __('validation.minvalue', ['attribute' => 'Regular Price', 'min' => 1]),
      'regular_price.max' => __('validation.maxvalue', ['attribute' => 'Regular Price', 'max' => 999999]),
      'sale_price.numeric' => __('validation.numeric', ['attribute' => 'Sale Price']),
      'sale_price.max' => __('validation.maxvalue', ['attribute' => 'Sale Price', 'max' => 999999]),
      'stock.required' => __('validation.required', ['attribute' => 'Stock']),
      'stock.numeric' => __('validation.numeric', ['attribute' => 'Stock']),
      'stock.min' => __('validation.minvalue', ['attribute' => 'Stock', 'min' => 1]),
      'stock.max' => __('validation.maxvalue', ['attribute' => 'Stock', 'max' => 999999]),
      'threshold.required' => __('validation.required', ['attribute' => 'Threshold']),
      'threshold.numeric' => __('validation.numeric', ['attribute' => 'Threshold']),
      'threshold.min' => __('validation.minvalue', ['attribute' => 'Threshold', 'min' => 1]),
      'threshold.max' => __('validation.maxvalue', ['attribute' => 'Threshold', 'max' => 999999]),
      'purchase_limit.numeric' => __('validation.numeric', ['attribute' => 'Purchase Limit']),
      'purchase_limit.max' => __('validation.maxvalue', ['attribute' => 'Purchase Limit', 'max' => 999999]),
    ];
  }
}
