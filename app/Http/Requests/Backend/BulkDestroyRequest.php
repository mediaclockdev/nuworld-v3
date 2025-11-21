<?php

namespace App\Http\Requests\Backend;

use App\Http\Requests\BaseRequest;
use Vinkla\Hashids\Facades\Hashids;

class BulkDestroyRequest extends BaseRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'ids' => ['required', 'array', 'min:1'], // Ensure it's an array with at least one element
      'ids.*' => ['required', 'string', function ($attribute, $value, $fail) {
        $decoded = Hashids::decode($value);
        if (empty($decoded) || !is_numeric($decoded[0])) {
          $fail(__('validation.invalid', ['attribute' => $attribute]));
        }
      }],
    ];
  }

  public function messages(): array
  {
    return [
      'ids.required' => __('validation.required', ['attribute' => 'IDs']),
      'ids.array' => __('validation.array', ['attribute' => 'IDs']),
      'ids.min' => __('validation.min', ['attribute' => 'IDs', 'min' => '1']),
      'ids.*.string' => __('validation.string', ['attribute' => 'IDs']),
    ];
  }

  /**
   * Decode and return only valid numeric IDs.
   */
  public function decodedIds(): array
  {
    return array_map(fn($hashid) => Hashids::decode($hashid)[0], $this->ids);
  }
}
