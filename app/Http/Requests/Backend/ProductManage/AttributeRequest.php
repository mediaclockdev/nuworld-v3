<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;

class AttributeRequest extends BaseRequest
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
      'attribute_title' => 'required|max:100|unique:product_attributes,name,' . $id . ',id,deleted_at,NULL',
      'sequence' => 'required|numeric|max:50000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'attribute_title.required' => __('validation.required', ['attribute' => 'Attribute Name']),
      'attribute_title.max' => __('validation.maxlength', ['attribute' => 'Attribute Name', 'max' => '100']),
      'attribute_title.unique' => __('validation.unique', ['attribute' => 'Attribute Name']),
      'sequence.required' => __('validation.required', ['attribute' => 'Sequence']),
      'sequence.numeric' => __('validation.numeric', ['attribute' => 'Sequence']),
      'sequence.max' => __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => '50000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status']),
    ];
  }
}
