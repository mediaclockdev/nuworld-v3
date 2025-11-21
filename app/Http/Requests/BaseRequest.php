<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  protected function failedValidation(Validator $validator)
  {
    $errors = $validator->errors()->all();
    $response = response()->json([
      'success' => false,
      'data' => [],
      'message' => $errors[0] // Return only the first error
    ], 422);
    throw new HttpResponseException($response);
  }
}
