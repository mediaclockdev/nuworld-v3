<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;

class AttributeValueRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    return [
      'parent_attribute' => 'required|string',
      'value_name' => 'required|string|max:100',
      'extra_value' => 'nullable|string|max:100',
      'sequence' => 'required|numeric|max:50000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'parent_attribute.required' => __('validation.required', ['attribute' => 'Parent Attribute']),
      'value_name.required' => __('validation.required', ['attribute' => 'Value Name']),
      'value_name.max' => __('validation.maxlength', ['attribute' => 'Value Name', 'max' => '100']),
      'extra_value.max' => __('validation.maxlength', ['attribute' => 'Extra Value', 'max' => '100']),
      'sequence.required' => __('validation.required', ['attribute' => 'Sequence']),
      'sequence.numeric' => __('validation.numeric', ['attribute' => 'Sequence']),
      'sequence.max' => __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => '50000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status']),
    ];
  }
}
