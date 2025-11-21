<?php

namespace App\Http\Requests\Backend\Promotions;

use App\Http\Requests\BaseRequest;

class CouponRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id');

    return [
      'code' => [
        'required',
        'max:50',
        'regex:/^[A-Z0-9]+$/',
        'unique:coupons,code,' . $id . ',id,deleted_at,NULL',
      ],
      'type' => [
        'required',
        'in:Flat,Percentage',
      ],
      'discount_amount' => [
        'required',
        'numeric',
        'min:0.01',
      ],
      'max_discount' => [
        'nullable',
        'numeric',
        'min:0',
        // 'required_if:type,Percentage',
      ],
      'min_order_value' => [
        'nullable',
        'numeric',
        'min:0',
      ],
      'max_uses' => [
        'nullable',
        'integer',
        'min:1',
      ],
      'per_user_limit' => [
        'nullable',
        'integer',
        'min:1',
      ],
      'is_active' => [
        'required',
        'boolean',
      ],
      'valid_from' => [
        'required',
        'date',
      ],
      'valid_to' => [
        'required',
        'date',
        'after_or_equal:valid_from',
      ],
    ];
  }

  /**
   * Custom validation messages.
   */
  public function messages(): array
  {
    return [
      'code.required' => __('validation.required', ['attribute' => 'Coupon Code']),
      'code.max' => __('validation.maxlength', ['attribute' => 'Coupon Code', 'max' => '50']),
      'code.regex' => __('validation.regex', ['attribute' => 'Coupon Code']),
      'code.unique' => __('validation.unique', ['attribute' => 'Coupon Code']),

      'type.required' => __('validation.required', ['attribute' => 'Discount Type']),
      'type.in' => __('validation.in', ['attribute' => 'Discount Type']),

      'discount_amount.required' => __('validation.required', ['attribute' => 'Discount Amount']),
      'discount_amount.numeric' => __('validation.numeric', ['attribute' => 'Discount Amount']),
      'discount_amount.min' => __('validation.min', ['attribute' => 'Discount Amount', 'min' => '0.01']),

      'max_discount.numeric' => __('validation.numeric', ['attribute' => 'Max Discount']),
      'max_discount.min' => __('validation.min', ['attribute' => 'Max Discount', 'min' => '0']),
      'max_discount.required_if' => __('validation.required_if', ['attribute' => 'Max Discount', 'other' => 'type', 'value' => 'Percentage']),

      'min_order_value.required' => __('validation.required', ['attribute' => 'Minimum Order Value']),
      'min_order_value.numeric' => __('validation.numeric', ['attribute' => 'Minimum Order Value']),
      'min_order_value.min' => __('validation.min', ['attribute' => 'Minimum Order Value', 'min' => '0']),

      'max_uses.integer' => __('validation.integer', ['attribute' => 'Max Uses']),
      'max_uses.min' => __('validation.min', ['attribute' => 'Max Uses', 'min' => '1']),

      'per_user_limit.integer' => __('validation.integer', ['attribute' => 'Per User Limit']),
      'per_user_limit.min' => __('validation.min', ['attribute' => 'Per User Limit', 'min' => '1']),

      'is_active.required' => __('validation.required', ['attribute' => 'Status']),
      'is_active.boolean' => __('validation.boolean', ['attribute' => 'Status']),

      'valid_from.required' => __('validation.required', ['attribute' => 'Valid From']),
      'valid_from.date' => __('validation.date', ['attribute' => 'Valid From']),

      'valid_to.required' => __('validation.required', ['attribute' => 'Valid To']),
      'valid_to.date' => __('validation.date', ['attribute' => 'Valid To']),
      'valid_to.after_or_equal' => __('validation.after_or_equal', ['attribute' => 'Valid To', 'date' => 'Valid From']),
    ];
  }

  protected function prepareForValidation(): void
  {
    $this->merge([
      'valid_from' => $this->formatDate($this->valid_from),
      'valid_to' => $this->formatDate($this->valid_to),
    ]);
  }

  private function formatDate(?string $date): ?string
  {
    if (!$date) return null;

    $parts = explode('/', $date);
    if (count($parts) === 3) {
      // dd/mm/yyyy => yyyy-mm-dd
      return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }

    return $date; // fallback
  }
}
