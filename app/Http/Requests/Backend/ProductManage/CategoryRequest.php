<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class CategoryRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id');
    $uniqueRule = ",$id,id,deleted_at,NULL";
    $imageRule = 'string';
    if ($this->hasFile('category_image')) {
      $imageRule = 'mimes:jpg,jpeg,png,webp|max:2048';
    }
    $parentID = $this->request->get('parent_id') ? Hashids::decode($this->request->get('parent_id'))[0] : '';

    return [
      'parent_id' => [
        'nullable',
        'max:10',
      ],
      'categorytitle' => [
        'required',
        'max:100',
        'unique:product_categories,title,' . $id . ',id,deleted_at,NULL,parent_id,' .  $parentID,
      ],
      'slug' => [
        'required',
        'max:100',
        'unique:product_categories,slug,' . $id . ',id,deleted_at,NULL,parent_id,' .  $parentID,
      ],
      'meta_title' => [
        'nullable',
        'max:240',
      ],
      'meta_keywords' => [
        'nullable',
        'max:240',
      ],
      'meta_description' => [
        'nullable',
        'max:240',
      ],
      'categoryicon' => [
        'nullable',
        'max:100',
      ],
      'sequence' => [
        'required',
        'numeric',
      ],
      'tax_percentage' => [
        'required',
        'numeric',
        'between:0,100',
      ],
      'category_image' => 'nullable|' . $imageRule,
    ];
  }

  public function messages(): array
  {
    return [
      'parent_id.max' => __('validation.maxlength', ['attribute' => 'Parent ID', 'max' => '10']),
      'categorytitle.required' => __('validation.required', ['attribute' => 'Category Name']),
      'categorytitle.max' => __('validation.maxlength', ['attribute' => 'Category Name', 'max' => '100']),
      'categorytitle.unique' => __('validation.unique', ['attribute' => 'Category Name']),
      'slug.required' => __('validation.required', ['attribute' => 'Slug']),
      'slug.max' => __('validation.maxlength', ['attribute' => 'Slug', 'max' => '100']),
      'slug.unique' => __('validation.unique', ['attribute' => 'Slug']),
      'meta_title.max' => __('validation.maxlength', ['attribute' => 'Meta Title', 'max' => '240']),
      'meta_keywords.max' => __('validation.maxlength', ['attribute' => 'Meta Keywords', 'max' => '240']),
      'meta_description.max' => __('validation.maxlength', ['attribute' => 'Meta Description', 'max' => '240']),
      'icon.max' => __('validation.maxlength', ['attribute' => 'Icon', 'max' => '100']),
      'sequence.required' => __('validation.required', ['attribute' => 'Sequence']),
      'sequence.numeric' => __('validation.numeric', ['attribute' => 'Sequence']),
      'tax_percentage.required' => __('validation.required', ['attribute' => 'Tax Percentage']),
      'tax_percentage.numeric' => __('validation.numeric', ['attribute' => 'Tax Percentage']),
      'tax_percentage.between' => __('validation.between', ['attribute' => 'Tax Percentage', 'min' => '0', 'max' => '100']),
      'category_image.mimes' => __('validation.mimes', ['attribute' => 'Image', 'values' => 'jpg,jpeg,png,gif,webp']),
      'category_image.max' => __('validation.max', ['attribute' => 'Image', 'max' => '2048']),
    ];
  }
}
