<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class CheckoutCouponRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'coupon_code' => 'required|string',
      'order_amount' => 'required|numeric',
    ];
  }

  public function messages(): array
  {
    return [
      'coupon_code.required' => __('validation.required', ['attribute' => 'Coupon Code']),
      'order_amount.required' => __('validation.required', ['attribute' => 'Order Amount']),
      'order_amount.numeric' => __('validation.numeric', ['attribute' => 'Order Amount']),
    ];
  }
}
