<?php

namespace App\Http\Requests\Backend\ProductManage;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'csv_products_file' => 'required|file|mimes:csv,txt|max:2048',
    ];
  }

  // Static method for validating each row of the CSV
  public static function rowRules(): array
  {
    // return [];
    return [
      'category_name' => 'required|string|max:255',
      'product_name' => 'required|string|max:255',
      'product_description' => 'required|string',
      'product_sku' => 'required|string|max:255',
    ];
  }
}
