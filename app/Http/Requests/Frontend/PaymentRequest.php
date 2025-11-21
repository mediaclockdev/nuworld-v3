<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class PaymentRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'stripeToken' => 'required',
      'order_number' => 'required',
    ];
  }

  public function messages(): array
  {
    return [
      'stripeToken.required' => __('validation.required', ['attribute' => 'Stripe Token']),
      'order_number.required' => __('validation.required', ['attribute' => 'Order Number']),
    ];
  }
}
