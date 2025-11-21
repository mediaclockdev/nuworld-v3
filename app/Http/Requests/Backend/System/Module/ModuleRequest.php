<?php

namespace App\Http\Requests\Backend\System\Module;

use App\Http\Requests\BaseRequest;

class ModuleRequest extends BaseRequest
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

    return [
      'modulename' => 'required|max:255|unique:modules,name' . $uniqueRule,
      'moduleicon' => 'required|max:255',
      'modulesequence' => 'required|min:1|max:127',
      'description' => 'nullable|string|max:1000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'modulename.required' => __('validation.required', ['attribute' => 'Module Name']),
      'modulename.max' => __('validation.max', ['attribute' => 'Module Name', 'max' => '255']),
      'modulename.unique' => __('validation.unique', ['attribute' => 'Module Name']),
      'moduleicon.required' => __('validation.required', ['attribute' => 'Module Icon']),
      'moduleicon.max' => __('validation.max', ['attribute' => 'Module Icon', 'max' => '255']),
      'modulesequence.required' => __('validation.required', ['attribute' => 'Sequence']),
      'modulesequence.min' => __('validation.min', ['attribute' => 'Sequence', 'min' => '1']),
      'modulesequence.max' => __('validation.max', ['attribute' => 'Sequence', 'max' => '127']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => '1000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
