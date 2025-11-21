<?php

namespace App\Http\Requests\Backend\ContentManage;

use App\Http\Requests\BaseRequest;

class CMSPageRequest extends BaseRequest
{

  public function rules(): array
  {

    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL";
    $imageRule = 'string';
    if ($this->hasFile('cms_image')) {
      $imageRule = 'mimes:jpg,jpeg,png,gif,webp|max:2048';
    }

    return [
      'cms_title' => 'required|max:255|unique:cms_pages,title' . $uniqueRule,
      'cms_slug' => 'required|max:255|unique:cms_pages,slug' . $uniqueRule,
      'meta_title' => 'nullable|string|max:255',
      'meta_description' => 'nullable|string',
      'meta_keywords' => 'nullable|string|max:255',
      'cms_status' => 'required|in:1,0',
      'meta_description' => 'nullable|string',
      'cms_image' => 'nullable|' . $imageRule,
      'cms_description' => 'nullable|string',
    ];
  }

  public function messages(): array
  {
    return [
      'cms_title.required' => __('validation.required', ['attribute' => 'Title']),
      'cms_title.max' => __('validation.max.string', ['attribute' => 'Title', 'max' => '255']),
      'cms_title.unique' => __('validation.unique', ['attribute' => 'Title']),
      'cms_slug.required' => __('validation.required', ['attribute' => 'Slug']),
      'cms_slug.max' => __('validation.max.string', ['attribute' => 'Slug', 'max' => '255']),
      'cms_slug.unique' => __('validation.unique', ['attribute' => 'Slug']),
      'meta_title.max' => __('validation.max.string', ['attribute' => 'Meta Title', 'max' => '255']),
      'meta_keywords.max' => __('validation.max.string', ['attribute' => 'Meta Keywords', 'max' => '255']),
      'cms_status.required' => __('validation.required', ['attribute' => 'Status']),
      'cms_status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
      'cms_image.mimes' => __('validation.mimes', ['attribute' => 'Image', 'values' => 'jpg,jpeg,png,gif,webp']),
      'cms_image.max' => __('validation.max', ['attribute' => 'Image', 'max' => '2048']),
    ];
  }
}
