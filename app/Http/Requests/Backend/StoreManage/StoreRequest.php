<?php

namespace App\Http\Requests\Backend\StoreManage;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class StoreRequest extends BaseRequest
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
    if ($this->has('country_id') && !empty($this->get('country_id'))) {
      $this->merge(['country_id' => Hashids::decode($this->get('country_id'))[0] ?? null]);
    }

    $isUpdate = $this->route('id') ? true : false;

    return [
      'name'          => 'required|string|max:255|unique:stores,name,' . $this->route('id') . ',id,deleted_at,NULL',
      'address'       => 'nullable|string|max:255',
      'country_id'    => 'nullable|exists:countries,id',
      'state'         => 'nullable|string|max:255',
      'city'          => 'nullable|string|max:255',
      'image'         => ($isUpdate ? 'nullable' : 'required') . '|file|image|max:2048', // Max 2MB
      'pincode'       => 'nullable|string|max:20',
      'location'      => 'nullable|url|max:255',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => __('validation.required', ['attribute' => 'Store']),
      'name.max' => __('validation.max', ['attribute' => 'Store', 'max' => '255']),
      'name.unique' => __('validation.unique', ['attribute' => 'Store']),

      'country_id.required' => __('validation.required', ['attribute' => 'Post']),
      'country_id.exists' => __('validation.exists', ['attribute' => 'Post']),

      'image.file' => __('validation.file', ['attribute' => 'Image']),
      'image.image' => __('validation.image', ['attribute' => 'Image']),
      'image.max' => __('validation.max', ['attribute' => 'Image', 'max' => '2MB']),


      'address.max' => __('validation.maxlength', ['attribute' => 'Address']),
      'city.required' => __('validation.required', ['attribute' => 'City']),
      'city.max' => __('validation.maxlength', ['attribute' => 'City']),

      'state.required' => __('validation.required', ['attribute' => 'State']),
      'state.max' => __('validation.maxlength', ['attribute' => 'State']),

      'pincode.required' => __('validation.required', ['attribute' => 'PinCode']),
      'pincode.max' => __('validation.maxlength', ['attribute' => 'PinCode']),
    ];
  }
}
