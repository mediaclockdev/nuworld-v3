<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;

class ReturnItemRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'choice' => 'required',
      'admin_reason' => 'nullable|max:255',
    ];
  }

  public function messages(): array
  {
    return [
      'choice.required' => __('validation.required', ['attribute' => 'Admin Process Choice']),
      'admin_reason.max' => __('validation.maxlength', ['attribute' => 'Admin Reason', 'max' => '255']),
    ];
  }
}
