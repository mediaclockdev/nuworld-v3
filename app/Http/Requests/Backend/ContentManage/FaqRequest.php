<?php

namespace App\Http\Requests\Backend\ContentManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class FaqRequest extends BaseRequest
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
    if ($this->has('faq_category_id') && !empty($this->get('faq_category_id'))) {
      $this->merge(['faq_category_id' => Hashids::decode($this->get('faq_category_id'))[0] ?? null]);
    }
    return [
      'faq_category_id' => 'required|exists:faq_categories,id',
      'question' => 'required|string|max:255',
      'answer' => 'nullable|string|max:65535',
    ];
  }

  public function messages(): array
  {
    return [
      'faq_category_id.required' => __('validation.required', ['attribute' => 'Category']),
      'faq_category_id.exists' => __('validation.exists', ['attribute' => 'Category']),
      'question.required' => __('validation.required', ['attribute' => 'Question']),
      'question.max' => __('validation.max', ['attribute' => 'Question', 'max' => '255']),
      'answer.max' => __('validation.max', ['attribute' => 'Answer', 'max' => '65535']),
    ];
  }
}
