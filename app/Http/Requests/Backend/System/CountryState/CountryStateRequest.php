<?php

namespace App\Http\Requests\Backend\System\CountryState;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class CountryStateRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $id = $this->route('id') ?? null;
    $uniqueRule = ",$id,id,deleted_at,NULL,country_id," . Hashids::decode($this->request->get('country'))[0];
    return [
      'country' => [
        'required',
        'max:10',
      ],
      'statename' => [
        'required',
        'max:100',
        'unique:states,name' . $uniqueRule,
      ],
      'status' => [
        'required',
        'in:1,0',
      ]
    ];
  }

  public function messages(): array
  {
    return [
      'country.required' => __('validation.required', ['attribute' => 'Country Code']),
      'country.max' => __('validation.maxlength', ['attribute' => 'Country Code', 'max' => '10']),
      'statename.required' => __('validation.required', ['attribute' => 'State Name']),
      'statename.max' => __('validation.maxlength', ['attribute' => 'State Name', 'max' => '100']),
      'statename.unique' => __('validation.unique', ['attribute' => 'State for this Country']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status', 'values' => '1,0']),
    ];
  }
}
