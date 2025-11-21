<?php

namespace App\Http\Requests\Backend\System\SubModule;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class SubModuleRequest extends BaseRequest
{
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL";
    if ($this->has('parentmodule') && is_string($this->parentmodule)) {
      $this->merge(['parentmodule' => Hashids::decode($this->parentmodule)[0] ?? null]);
    }

    return [
      'parentmodule' => 'required',
      'submodulename' => 'required|max:100|unique:sub_modules,name' . $uniqueRule,
      'submoduleslug' => 'nullable|string|max:100|unique:sub_modules,route_name' . $uniqueRule,
      'submodulesequence' => 'required|integer|min:1|max:127',
      'submoduleicon' => 'nullable|string|max:100',
      'description' => 'nullable|string|max:1000',
      'status' => 'required|in:1,0',
    ];
  }

  public function messages(): array
  {
    return [
      'parentmodule.required' => __('validation.required', ['attribute' => 'Parent Module']),
      'parentmodule.integer' => __('validation.numeric', ['attribute' => 'Parent Module']),
      'submodulename.required' => __('validation.required', ['attribute' => 'SubModule Name']),
      'submodulename.max' => __('validation.max', ['attribute' => 'SubModule Name', 'max' => '100']),
      'submodulename.unique' => __('validation.unique', ['attribute' => 'SubModule Name']),
      'submoduleslug.unique' => __('validation.unique', ['attribute' => 'SubModule Slug']),
      'submoduleslug.max' => __('validation.max', ['attribute' => 'SubModule Slug', 'max' => '100']),
      'submodulesequence.required' => __('validation.required', ['attribute' => 'SubModule Sequence']),
      'submodulesequence.integer' => __('validation.numeric', ['attribute' => 'SubModule Sequence']),
      'submodulesequence.min' => __('validation.min', ['attribute' => 'SubModule Sequence', 'min' => '1']),
      'submodulesequence.max' => __('validation.max', ['attribute' => 'SubModule Sequence', 'max' => '127']),
      'submoduleicon.max' => __('validation.max', ['attribute' => 'SubModule Icon', 'max' => '100']),
      'description.max' => __('validation.max', ['attribute' => 'Description', 'max' => '1000']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
