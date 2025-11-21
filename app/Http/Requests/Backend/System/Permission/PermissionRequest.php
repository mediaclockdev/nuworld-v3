<?php

namespace App\Http\Requests\Backend\System\Permission;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class PermissionRequest extends BaseRequest
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
      'permissionname' => [
        'required',
        'max:100',
        "unique:permissions,name$uniqueRule",
      ],
      'permissionslug' => [
        'required',
        'string',
        'max:100',
        "unique:permissions,slug$uniqueRule",
      ],
      'description' => ['nullable', 'string', 'max:1000'],
    ];
  }

  public function messages(): array
  {
    return [
      'permissionname.required' => __('validation.required', ['attribute' => 'Permission Name']),
      'permissionname.max' => __('validation.max', ['attribute' => 'Permission Name', 'max' => '100']),
      'permissionname.unique' => __('validation.unique', ['attribute' => 'Permission Name']),
      'permissionslug.required' => __('validation.required', ['attribute' => 'Permission Slug']),
      'permissionslug.max' => __('validation.max', ['attribute' => 'Permission Slug', 'max' => '50']),
      'permissionslug.unique' => __('validation.unique', ['attribute' => 'Permission Slug']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => '1000']),
    ];
  }
}
