<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class ReviewRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
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
      'productreview.max' => __('validation.maxlength', ['attribute' => 'Review', 'max' => 500]),
      'rating.required'   => __('validation.required', ['attribute' => 'Rating']),
      'images.*.mimes'    => __('validation.mimes', ['attribute' => 'Image', 'values' => 'jpg,jpeg,png,webp']),
    ];
  }
}
