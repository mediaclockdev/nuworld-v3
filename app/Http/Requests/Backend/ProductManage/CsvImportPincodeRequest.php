<?php

namespace App\Http\Requests\Backend\ProductManage;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportPincodeRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'csv_pincodes_file' => 'required|file|mimes:csv,txt|max:2048',
    ];
  }

  // Static method for validating each row of the CSV
  public static function rowRules(): array
  {
    return [
      'code'          => 'required|string|min:3|max:15:',
      'estimate_days' => 'required|string|max:15',
    ];
  }
}
