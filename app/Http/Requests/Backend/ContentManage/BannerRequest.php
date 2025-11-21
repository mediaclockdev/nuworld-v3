<?php

namespace App\Http\Requests\Backend\ContentManage;

use App\Http\Requests\BaseRequest;

class BannerRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL";
    $imageRule = 'string';
    if ($this->hasFile('banner_image')) {
      $imageRule = 'mimes:jpg,jpeg,png,gif,webp|max:2048';
    }

    return [
      'banner_image' => 'required|' . $imageRule,
      'banner_title' => 'required|max:100|unique:banners,title' . $uniqueRule,
      'banner_alt_text' => 'nullable|string|max:100',
      'banner_hyperlink' => 'nullable|url|max:255',
      'banner_sequence' => 'required|integer',
      'banner_description' => 'nullable|string',
      'banner_position' => 'required|string',
      'banner_extra_value' => 'nullable|string|exists:product_variants,sku',
      'banner_status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'banner_image.required' => __('validation.required', ['attribute' => 'Banner Image']),
      'banner_image.mimes' => __('validation.invalid', ['attribute' => 'Banner Image Format']),
      'banner_image.max' => __('validation.maxlength', ['attribute' => 'Banner Image', 'max' => '2048']),
      'banner_title.required' => __('validation.required', ['attribute' => 'Banner Title']),
      'banner_title.max' => __('validation.maxlength', ['attribute' => 'Banner Title', 'max' => '100']),
      'banner_title.unique' => __('validation.unique', ['attribute' => 'Banner Title']),
      'banner_alt_text.max' => __('validation.maxlength', ['attribute' => 'Banner Alt Text', 'max' => '100']),
      'banner_hyperlink.max' => __('validation.maxlength', ['attribute' => 'Banner Hyperlink', 'max' => '255']),
      'banner_sequence.required' => __('validation.required', ['attribute' => 'Banner Sequence']),
      'banner_sequence.integer' => __('validation.numeric', ['attribute' => 'Banner Sequence']),
      'banner_position.required' => __('validation.required', ['attribute' => 'Banner Position']),
      'banner_position.integer' => __('validation.numeric', ['attribute' => 'Banner Position']),
      'banner_status.required' => __('validation.required', ['attribute' => 'Banner Status']),
      'banner_status.in' => __('validation.in', ['attribute' => 'Banner Status', 'values' => '1,0']),
      'banner_extra_value.exists' => __('validation.invalid', ['attribute' => 'Product SKU']),
    ];
  }
}
