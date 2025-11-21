<?php

namespace App\Http\Requests\Backend\System\Department;

use App\Http\Requests\BaseRequest;

class DepartmentRequest extends BaseRequest
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
      'departmentname' => 'required|max:100|unique:departments,name' . $uniqueRule,
      'description' => 'nullable|string|max:1000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'departmentname.required' => __('validation.required', ['attribute' => 'Department Name']),
      'departmentname.max' => __('validation.max', ['attribute' => 'Department Name', 'max' => '100']),
      'departmentname.unique' => __('validation.unique', ['attribute' => 'Department Name']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => '1000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
