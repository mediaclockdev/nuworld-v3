<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\DB;

class BestSellerRequest extends BaseRequest
{
  public function rules(): array
  {
    $id = $this->route('id'); // Assumes 'id' is route parameter
    $uniqueRule = "unique:best_sellers,product_variant_id,$id,id,deleted_at,NULL";

    return [
      'description' => ['nullable', 'string', 'max:500'],
      'product_id' => ['required'],
      'product_variant_id' => [
        'required',
        'array',
        'min:1',
      ],
      'product_variant_id.*' => [
        'required',
        $uniqueRule,
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'description.nullable' => __('validation.nullable', ['attribute' => 'Description']),
      'description.string' => __('validation.string', ['attribute' => 'Description']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => 500]),
      'product_id.required' => __('validation.required', ['attribute' => 'Product ID']),
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant ID']),
      'product_variant_id.array' => __('validation.array', ['attribute' => 'Product Variant ID']),
      'product_variant_id.min' => __('validation.min', ['attribute' => 'Product Variant ID', 'min' => 1]),
      'product_variant_id.*.required' => __('validation.required', ['attribute' => 'Product Variant ID']),
      'product_variant_id.*.unique' => __('validation.unique', ['attribute' => 'Product Variant ID']),


    ];
  }
}
