<?php

namespace App\Http\Requests\Backend\OrderManage;

use App\Http\Requests\BaseRequest;

class CustomerRequest extends BaseRequest
{
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL";

    return [
      'firstname' => 'required|max:100',
      'middlename' => 'nullable|max:100',
      'lastname' => 'nullable|max:100',
      'customeremail' => 'required|max:100|email:rfc,dns|unique:users,email' . $uniqueRule,
      'customerphone' => 'nullable|max:20|unique:users,phone' . $uniqueRule,
      'status' => 'required|in:1,2',
    ];
  }

  public function messages(): array
  {
    return [
      'firstname.required' => __('validation.required', ['attribute' => 'First Name']),
      'firstname.max' => __('validation.maxlength', ['attribute' => 'First Name', 'max' => '100']),
      'middlename.max' => __('validation.maxlength', ['attribute' => 'Middle Name', 'max' => '100']),
      'lastname.max' => __('validation.maxlength', ['attribute' => 'Last Name', 'max' => '100']),
      'customeremail.required' => __('validation.required', ['attribute' => 'Email']),
      'customeremail.email' => __('validation.email', ['attribute' => 'Email']),
      'customeremail.unique' => __('validation.unique', ['attribute' => 'Email']),
      'customeremail.max' => __('validation.maxlength', ['attribute' => 'Email', 'max' => '100']),
      'customerphone.unique' => __('validation.unique', ['attribute' => 'Phone']),
      'customerphone.max' => __('validation.maxlength', ['attribute' => 'Phone', 'max' => '20']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,2']),
    ];
  }
}
