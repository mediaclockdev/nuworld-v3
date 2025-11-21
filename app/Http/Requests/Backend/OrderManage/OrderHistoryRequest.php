<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;

class OrderHistoryRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {


    return [
      'scheduled_date' => [
        'required',
        'date_format:d/m/Y',
      ],
      'scheduled_time' => [
        'required',
      ],

    ];
  }

  /**
   * Custom validation messages.
   */
  public function messages(): array
  {
    return [
      'scheduled_date.required' => __('validation.required', ['attribute' => 'Coupon Code']),

      'scheduled_time.required' => __('validation.required', ['attribute' => 'Discount Type']),

    ];
  }
}
