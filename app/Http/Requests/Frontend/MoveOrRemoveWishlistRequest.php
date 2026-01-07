<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class MoveOrRemoveWishlistRequest extends BaseRequest
{
  protected function prepareForValidation(): void
  {
    if ($this->has('product_variant_id')) {
      $decoded = Hashids::decode($this->input('product_variant_id'));
      $this->merge([
        'product_variant_id' => $decoded[0] ?? null,
      ]);
    }
  }

  public function rules(): array
  {
    return [
      'product_variant_id' => 'required|exists:product_variants,id,deleted_at,NULL',
      'action' => 'required|in:add,remove',
      'quantity' => 'required_if:action,add|integer|min:1',
    ];
  }

  public function messages(): array
  {
    return [
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant']),
      'product_variant_id.exists' => __('validation.exists', ['attribute' => 'Product Variant']),
      'action.required' => __('validation.required', ['attribute' => 'Action']),
      'action.in' => __('validation.in', ['attribute' => 'Action']),
      'quantity.required_if' => __('validation.required', ['attribute' => 'Quantity']),
    ];
  }
}
