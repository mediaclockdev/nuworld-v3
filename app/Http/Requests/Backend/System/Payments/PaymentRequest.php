<?php

namespace App\Http\Requests\Backend\System\Payments;

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
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL,gateway_mode," . $this->request->get('gatewaymode');

    return [
      'gatewayname' => [
        'required',
        'max:100',
        'unique:payment_settings,gateway_name' . $uniqueRule,
      ],
      'gatewaymode' => [
        'required',
        'max:50',
      ],
      'gatewaykey' => [
        // 'required',
        'max:255',
      ],
      'gatewaysecret' => [
        // 'required',
        'max:255',
      ],
      'other_info' => [
        'nullable',
        'string',
        'max:500',
      ],
      'status' => [
        'required',
        'in:1,0',
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'gatewayname.required' => __('validation.required', ['attribute' => 'Gateway Name']),
      'gatewayname.max' => __('validation.maxlength', ['attribute' => 'Gateway Name', 'max' => '100']),
      'gatewayname.unique' => __('validation.unique', ['attribute' => 'Gateway Mode for this Gateway']),
      'gatewaymode.required' => __('validation.required', ['attribute' => 'Gateway Mode']),
      'gatewaymode.max' => __('validation.maxlength', ['attribute' => 'Gateway Mode', 'max' => '50']),
      'gatewaykey.required' => __('validation.required', ['attribute' => 'Gateway Key']),
      'gatewaykey.max' => __('validation.maxlength', ['attribute' => 'Gateway Key', 'max' => '255']),
      'gatewaysecret.required' => __('validation.required', ['attribute' => 'Gateway Secret']),
      'gatewaysecret.max' => __('validation.maxlength', ['attribute' => 'Gateway Secret', 'max' => '255']),
      'other_info.max' => __('validation.maxlength', ['attribute' => 'Other Info', 'max' => '500']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
