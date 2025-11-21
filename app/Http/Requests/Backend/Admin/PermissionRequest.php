<?php

namespace App\Http\Requests\Backend\Admin;

use App\Http\Requests\BaseRequest;

class PermissionRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'role_id' => 'required|string',
      'permissions' => 'array',

    ];
  }

  public function messages(): array
  {
    return [
      'role_id.required' => __('validation.required', ['attribute' => 'Role']),
      'permissions.required' => __('validation.required', ['attribute' => 'Permissions']),
    ];
  }
}
