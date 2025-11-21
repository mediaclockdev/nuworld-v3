<?php

namespace App\Http\Requests\Backend\ContentManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class FaqCategoryRequest extends BaseRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    return [
      'name' => 'required|string|max:255|unique:faq_categories,name,' . $id . ',id,deleted_at,NULL',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => __('validation.required', ['attribute' => 'Category']),
      'name.max' => __('validation.max', ['attribute' => 'Category', 'max' => '255']),
      'name.unique' => __('validation.unique', ['attribute' => 'Category']),

    ];
  }
}
