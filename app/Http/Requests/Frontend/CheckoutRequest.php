<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class CheckoutRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'billing_address' => 'required',
      'shipping_address' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'billing_address.required' => __('validation.required', ['attribute' => 'Billing Address']),
      'shipping_address.required' => __('validation.required', ['attribute' => 'Shipping Address']),
    ];
  }
}
