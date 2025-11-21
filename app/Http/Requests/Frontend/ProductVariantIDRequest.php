<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ProductVariantIDRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    if ($this->has('product_variant_id') && !empty($this->get('product_variant_id'))) {
      $this->merge(['product_variant_id' => Hashids::decode($this->get('product_variant_id'))[0] ?? null]);
    }
    return [
      'product_variant_id' => 'required|exists:product_variants,id',
    ];
  }

  public function messages(): array
  {
    return [
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant']),
      'product_variant_id.exists' => __('validation.exists', ['attribute' => 'Product Variant']),
    ];
  }
}
