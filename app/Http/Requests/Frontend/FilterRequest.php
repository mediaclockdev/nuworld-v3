<?php

namespace App\Http\Requests\Frontend;

use App\Http\Requests\BaseRequest;

class FilterRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
      'page' => ['nullable', 'integer', 'min:1'],
      'attributes' => ['nullable', 'array'],
      // 'attributes.*' => ['string', 'max:255'],
      'sort' => ['nullable', 'string', 'max:100'],
      'q' => ['nullable', 'string', 'max:100'],
      'min_price' => ['nullable', 'integer'],
      'max_price' => ['nullable', 'integer'],
    ];
  }

  public function messages(): array
  {
    return [
      'per_page.integer' => __('validation.integer', ['attribute' => 'Items per page']),
      'per_page.min' => __('validation.min', ['attribute' => 'Items per page', 'min' => '1']),
      'per_page.max' => __('validation.max', ['attribute' => 'Items per page', 'max' => '50']),
      'page.integer' => __('validation.integer', ['attribute' => 'Page number']),
      'attributes.*.string' => __('validation.string', ['attribute' => 'Filter attribute']),
      'attributes.*.max' => __('validation.maxlength', ['attribute' => 'Filter attribute', 'max' => '255']),
      'sort.string' => __('validation.string', ['attribute' => 'Sort order']),
      'sort.max' => __('validation.maxlength', ['attribute' => 'Sort order', 'max' => '100']),
      'q.max' => __('validation.maxlength', ['attribute' => 'Search query', 'max' => '100']),
      'min_price.integer' => __('validation.integer', ['attribute' => 'Min price']),
      'max_price.integer' => __('validation.integer', ['attribute' => 'Max price']),
    ];
  }
}
