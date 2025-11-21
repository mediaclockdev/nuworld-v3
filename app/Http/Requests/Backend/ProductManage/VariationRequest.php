<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class VariationRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $productID = $this->request->get('product_id') ? Hashids::decode($this->request->get('product_id'))[0] : '';
    $this->merge(['product_id' => $productID]);

    $attributeIds = $this->input('attribute_ids') ?? [];
    $decodedAttributeIds = array_map(function ($id) {
      $decoded = Hashids::decode($id);
      return $decoded[0] ?? null;
    }, $attributeIds);
    $decodedAttributeIds = array_filter($decodedAttributeIds);
    $this->merge(['attribute_ids' => $decodedAttributeIds]);


    return [
      'product_id' => 'required|exists:products,id',
      'variations' => 'required|array',
      'attribute_ids' => 'nullable|array',
      'attribute_ids.*' => 'exists:product_attributes,id',
    ];
  }

  public function messages(): array
  {
    return [
      'product_id.required' => __('validation.required', ['attribute' => 'Product']),
      'product_id.exists' => __('validation.exists', ['attribute' => 'Product']),
      'variations.required' => __('validation.required', ['attribute' => 'Variations']),
      'attribute_ids.array' => __('validation.array', ['attribute' => 'Attribute IDs']),
      'attribute_ids.*.exists' => __('validation.exists', ['attribute' => 'Attribute']),
    ];
  }
}
