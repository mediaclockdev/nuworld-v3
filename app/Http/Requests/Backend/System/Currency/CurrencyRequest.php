<?php

namespace App\Http\Requests\Backend\System\Currency;

use App\Http\Requests\BaseRequest;

class CurrencyRequest extends BaseRequest
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
      'currencyname' => [
        'required',
        'max:100',
        "unique:currencies,name$uniqueRule",
      ],
      'currencysymbol' => [
        'required',
        'max:10',
      ],
      'currencycode' => [
        'required',
        'max:10',
        "unique:currencies,code$uniqueRule",
      ],
      'currencyposition' => ['required', 'in:left,right'],
      'decimal_places' => ['required', 'integer', 'min:0', 'max:6'],
      'status' => ['required', 'in:1,0'],
    ];
  }

  public function messages(): array
  {
    return [
      'currencyname.required' => __('validation.required', ['attribute' => 'Currency Name']),
      'currencyname.max' => __('validation.maxlength', ['attribute' => 'Currency Name', 'max' => '100']),
      'currencyname.unique' => __('validation.unique', ['attribute' => 'Currency Name']),
      'currencysymbol.required' => __('validation.required', ['attribute' => 'Currency Icon']),
      'currencysymbol.max' => __('validation.maxlength', ['attribute' => 'Currency Icon', 'max' => '10']),
      'currencycode.required' => __('validation.required', ['attribute' => 'Currency Code']),
      'currencycode.max' => __('validation.maxlength', ['attribute' => 'Currency Code', 'max' => '10']),
      'currencycode.unique' => __('validation.unique', ['attribute' => 'Currency Code']),
      'currencyposition.required' => __('validation.required', ['attribute' => 'Currency Position']),
      'currencyposition.in' => __('validation.in', ['attribute' => 'Currency Position']),
      'decimal_places.required' => __('validation.required', ['attribute' => 'Decimal Places']),
      'decimal_places.integer' => __('validation.numeric', ['attribute' => 'Decimal Places']),
      'decimal_places.min' => __('validation.minvalue', ['attribute' => 'Decimal Places', 'min' => '0']),
      'decimal_places.max' => __('validation.maxvalue', ['attribute' => 'Decimal Places', 'max' => '6']),
      'status.required' => __('validation.required', ['attribute' => 'Status']),
      'status.in' => __('validation.in', ['attribute' => 'Status']),
    ];
  }
}
