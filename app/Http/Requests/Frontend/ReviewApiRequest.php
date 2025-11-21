<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class ReviewApiRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    if ($this->has('product_variant_id') && !empty($this->get('product_variant_id'))) {
      $this->merge(['product_variant_id' => Hashids::decode($this->get('product_variant_id'))[0] ?? null]);
    }
    // if ($this->has('review_id') && !empty($this->get('review_id'))) {
    //   $this->merge(['review_id' => Hashids::decode($this->get('review_id'))[0] ?? null]);
    // }
    return [
      'review_id'          => 'nullable|exists:product_reviews,id',
      'product_variant_id' => 'required|integer|exists:product_variants,id',
      'productreview'      => 'nullable|string|max:500',
      'rating'             => 'required|integer|min:1|max:5',
      'images.*'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ];
  }

  public function messages(): array
  {
    return [
      'product_variant_id.required' => __('validation.required', ['attribute' => 'Product Variant']),
      'product_variant_id.exists' => __('validation.exists', ['attribute' => 'Product Variant']),
      'review_id.exists' => __('validation.exists', ['attribute' => 'Review']),
      'productreview.max' => __('validation.maxlength', ['attribute' => 'Review', 'max' => 500]),
      'rating.required'   => __('validation.required', ['attribute' => 'Rating']),
      'images.*.mimes'    => __('validation.mimes', ['attribute' => 'Image', 'values' => 'jpg,jpeg,png,webp']),
    ];
  }
}
