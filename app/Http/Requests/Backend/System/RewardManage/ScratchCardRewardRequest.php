<?php

namespace App\Http\Requests\Backend\System\RewardManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ScratchCardRewardRequest extends BaseRequest
{

  public function rules(): array
  {
    if ($this->has('id') && !empty($this->get('id'))) {
      $this->merge(['id' => Hashids::decode($this->get('id'))[0] ?? null]);
    }
    return [
      'type'            => 'required|in:fixed,percentage,coupon',
      'value'           => 'required|numeric|min:0.01',
      'coupon_code'     => 'required_if:type,coupon|max:20|alpha_num|unique:scratch_card_rewards,coupon_code' . ($this->id ? ',' . $this->id : ''),
      'product_type'    => 'required|in:any,specific',
      'product_ids'     => 'required_if:product_type,specific|array',
      'product_ids.*'   => 'integer|exists:products,id',
      'valid_from'      => 'required|date',
      'valid_to'        => 'required|date|after_or_equal:valid_from',
      'validity_period' => 'required|integer|min:1',

    ];
  }

  public function messages(): array
  {
    return [
      'type.required' => __('validation.required', ['attribute' => __('Reward Type')]),
      'type.in' => __('validation.in', ['attribute' => __('Reward Type'), 'values' => 'fixed,percentage,coupon']),
      'value.required' => __('validation.required', ['attribute' => __('Reward Value')]),
      'value.numeric' => __('validation.numeric', ['attribute' => __('Reward Value')]),
      'value.min' => __('validation.minvalue', ['attribute' => __('Reward Value'), 'min' => 0.01]),
      'coupon_code.required_if' => __('validation.required_if', ['attribute' => __('Coupon Code'), 'other' => 'type', 'value' => 'coupon']),
      'coupon_code.max' => __('validation.maxlength', ['attribute' => __('Coupon Code'), 'max' => 20]),
      'coupon_code.alpha_num' => __('validation.alpha_num', ['attribute' => __('Coupon Code')]),
      'coupon_code.unique' => __('validation.unique', ['attribute' => __('Coupon Code')]),
      'product_type.required' => __('validation.required', ['attribute' => __('Product Type')]),
      'product_type.in' => __('validation.in', ['attribute' => __('Product Type'), 'values' => 'any,specific']),
      'product_ids.required_if' => __('validation.required_if', ['attribute' => __('Product IDs'), 'other' => 'product_type', 'value' => 'specific']),
      'product_ids.array' => __('validation.array', ['attribute' => __('Product IDs')]),
      'product_ids.*.integer' => __('validation.integer', ['attribute' => __('Product IDs')]),
      'product_ids.*.exists' => __('validation.exists', ['attribute' => __('Product IDs'), 'table' => 'products', 'column' => 'id']),
      'valid_from.required' => __('validation.required', ['attribute' => __('Valid From')]),
      'valid_from.date' => __('validation.date', ['attribute' => 'Valid From']),
      'valid_to.required' => __('validation.required', ['attribute' => __('Valid To')]),
      'valid_to.date' => __('validation.date', ['attribute' => 'Valid To']),
      'valid_to.after_or_equal' => __('validation.after_or_equal', ['attribute' => __('Valid To'), 'value' => __('Valid From')]),
      'validity_period.required' => __('validation.required', ['attribute' => __('Validity Period')]),
      'validity_period.integer' => __('validation.integer', ['attribute' => __('Validity Period')]),
      'validity_period.min' => __('validation.min', ['attribute' => __('Validity Period'), 'min' => 1]),
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
