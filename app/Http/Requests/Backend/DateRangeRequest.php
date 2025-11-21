<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;

class DateRangeRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'start_date' => 'required|date',
      'end_date' => 'required|date',
    ];
  }

  public function messages(): array
  {
    return [
      'start_date.required' => __('validation.required', ['attribute' => 'Start date']),
      'end_date.required' => __('validation.required', ['attribute' => 'End date']),
    ];
  }
}
