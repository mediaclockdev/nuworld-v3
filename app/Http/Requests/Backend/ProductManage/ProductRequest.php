<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ProductRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {

    $id = $this->route('id') ?? null;
    $categoryID = $this->request->get('category_id') ? Hashids::decode($this->request->get('category_id'))[0] : '';
    $this->merge(['category_id' => $categoryID]);
    return [
      'category_id' => 'required|max:10|exists:product_categories,id',
      // 'product_type' => 'required|in:simple,variable',
      'product_name' => 'required|max:100|unique:products,name,' . $id . ',id,deleted_at,NULL',
      'SKU' => 'required|max:100|unique:products,SKU,' . $id . ',id,deleted_at,NULL',
      'product_desc' => 'nullable|string|max:1000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'category_id.required' => __('validation.required', ['attribute' => 'Product Category']),
      'category_id.exists' => __('validation.exists', ['attribute' => 'Product Category']),
      // 'product_type.required' => __('validation.required', ['attribute' => 'Product Type']),
      'product_name.required' => __('validation.required', ['attribute' => 'Product Name']),
      'product_name.unique' => __('validation.unique', ['attribute' => 'Product Name']),
      'SKU.required' => __('validation.required', ['attribute' => 'SKU']),
      'SKU.unique' => __('validation.unique', ['attribute' => 'SKU']),
      'product_desc.max' => __('validation.maxlength', ['attribute' => 'Product Description', 'max' => '1000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status']),
    ];
  }
}
