<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class RoleRequest extends BaseRequest
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
      'rolename' => 'required|min:3|max:100|unique:roles,name' . $uniqueRule,
      'description' => 'nullable|string|max:1000',
      'permissions' => 'nullable|array',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'rolename.required' => __('validation.required', ['attribute' => 'Role Name']),
      'rolename.min' => __('validation.minlength', ['attribute' => 'Role Name', 'min' => 3]),
      'rolename.max' => __('validation.maxlength', ['attribute' => 'Role Name', 'max' => 100]),
      'rolename.unique' => __('validation.unique', ['attribute' => 'Role Name']),
      'description.max' => __('validation.maxlength', ['attribute' => 'Description', 'max' => 1000]),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
