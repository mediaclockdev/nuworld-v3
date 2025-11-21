<?php

namespace App\Http\Requests\Backend\ProductManage;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ];
  }

  // Static method for validating each row of the CSV
  public static function rowRules(): array
  {
    return [
      'title' => 'required|string|max:255',
      'slug' => ['required', 'regex:/^[a-z0-9-]+$/'],
      'tax' => 'required|numeric|max:100',
      'meta_title' => 'nullable|string',
      'icon' => 'nullable|string',
      'sequence' => 'required|integer|between:1,127',
      'status' => 'required|in:0,1',
    ];
  }
}
