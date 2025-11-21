<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseRequest;

class AdminUserRequest extends BaseRequest
{
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL";

    return [
      'firstname' => 'required|max:100',
      'middlename' => 'nullable|max:100',
      'lastname' => 'nullable|max:100',
      'adminemail' => 'required|max:100|email:rfc,dns|unique:admins,email' . $uniqueRule,
      'adminphone' => 'required|max:20|unique:admins,phone' . $uniqueRule,
      'adminrole' => 'required|array',
      'status' => 'required|in:1,0',
      'department' => 'nullable|string',
    ];
  }

  public function messages(): array
  {
    return [
      'firstname.required' => __('validation.required', ['attribute' => 'First Name']),
      'firstname.max' => __('validation.maxlength', ['attribute' => 'First Name', 'max' => '100']),
      'middlename.max' => __('validation.maxlength', ['attribute' => 'Middle Name', 'max' => '100']),
      'lastname.max' => __('validation.maxlength', ['attribute' => 'Last Name', 'max' => '100']),
      'adminemail.required' => __('validation.required', ['attribute' => 'Email']),
      'adminemail.email' => __('validation.email', ['attribute' => 'Email']),
      'adminemail.unique' => __('validation.unique', ['attribute' => 'Email']),
      'adminemail.max' => __('validation.maxlength', ['attribute' => 'Email', 'max' => '100']),
      'adminphone.required' => __('validation.required', ['attribute' => 'Phone']),
      'adminphone.unique' => __('validation.unique', ['attribute' => 'Phone']),
      'adminphone.max' => __('validation.maxlength', ['attribute' => 'Phone', 'max' => '20']),
      'adminrole.required' => __('validation.required', ['attribute' => 'Roles']),
      'adminrole.array' => __('validation.array', ['attribute' => 'Roles']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
