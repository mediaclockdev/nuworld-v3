<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class UpdateCartQuantityRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules()
  {
    if ($this->has('product_variant_id')) {
      $this->merge(['product_variant_id' => Hashids::decode($this->get('product_variant_id'))[0]]);
    }
    return [
      'product_variant_id' => 'required|exists:product_variants,id',
      'quantity' => 'required|integer|min:1',
    ];
  }

  public function messages()
  {
    return [
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant']),
      'product_variant_id.exists' => __('validation.exists', ['attribute' => 'Product Variant']),
      'quantity.required' => __('validation.required', ['attribute' => 'Quantity']),
      'quantity.min' => __('validation.min', ['attribute' => 'Quantity', 'min' => '1']),
    ];
  }
}
