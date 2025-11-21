<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class CustomerRewardStatusRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    if ($this->has('id') && !empty($this->get('id'))) {
      $this->merge(['id' => Hashids::decode($this->get('id'))[0] ?? null]);
    }
    if ($this->has('status') && !empty($this->get('status'))) {
      $this->merge(['status' => Hashids::decode($this->get('status'))[0] ?? null]);
    }
    return [
      'id' => 'required|integer|exists:customer_rewards,id',
      'status' => 'required|in:1,2,3',
    ];
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array<string, string>
   */
  public function messages(): array
  {
    return [
      'id.required' => __('validation.required', ['attribute' => 'Customer Reward ID']),
      'id.integer' => __('validation.integer', ['attribute' => 'Customer Reward ID']),
      'id.exists' => __('validation.exists', ['attribute' => 'Customer Reward ID']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status']),
    ];
  }
}
