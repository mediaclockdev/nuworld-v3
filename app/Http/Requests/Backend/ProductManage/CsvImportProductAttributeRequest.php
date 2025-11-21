<?php

namespace App\Http\Requests\Backend\ProductManage;

use App\Http\Requests\BaseRequest;

class CsvImportProductAttributeRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'csv_attr_file' => 'required|file|mimes:csv,txt|max:2048',
    ];
  }

  // Static method for validating each row of the CSV
  public static function rowRules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'value' => 'required|string|max:150',
      'value_details' => 'required|string|max:255',
      'sequence' => 'required|integer|between:1,127',
      'status' => 'required|in:0,1',
    ];
  }
}
