<?php

namespace App\Http\Controllers\Backend\ProductManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProductManage\CsvImportPincodeRequest;
use App\Http\Requests\Backend\ProductManage\CsvImportProductAttributeRequest;
use App\Http\Requests\Backend\ProductManage\CsvImportProductRequest;
use App\Http\Requests\Backend\ProductManage\CsvImportRequest;
use App\Models\Pincode;
use App\Models\ProductCategory;
use Illuminate\Contracts\View\View;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Services\CsvImportService;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CsvImportController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'CSV Import';
    $this->model = ProductCategory::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    return view('backend.pages.product-manage.import-csv.index', ['cardHeader' => $this->name]);
  }

  private function downloadCsvZipTemplate(string $filenamePrefix, string $csvFilename, string $txtFilename, string $zipFilename, string $csvContent, string $txtContent)
  {
    $folderPath = storage_path('app/public');

    if (!File::exists($folderPath)) {
      File::makeDirectory($folderPath, 0755, true);
    }

    $csvPath = $folderPath . '/' . $csvFilename;
    $txtPath = $folderPath . '/' . $txtFilename;
    $zipPath = $folderPath . '/' . $zipFilename;

    file_put_contents($csvPath, $csvContent);
    chmod($csvPath, 0644);

    file_put_contents($txtPath, $txtContent);
    chmod($txtPath, 0644);

    $zip = new \ZipArchive;
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
      $zip->addFile($csvPath, $csvFilename);
      $zip->addFile($txtPath, $txtFilename);
      $zip->close();
    }

    return response()->download($zipPath)->deleteFileAfterSend(true);
  }

  public function downloadCsvTemplate()
  {
    $csvContent = "Parent_Category,Category Name,Category Slug,Tax Percentage,Meta Title,Meta Keyword,Meta Description,Icon\n";
    $txtContent = "Field Descriptions:
        - Parent_Category:''
        - Category Name: max 255 chars
        - Category Slug: lowercase with hyphens
        - Tax Percentage: float max 100
        - Meta Title: optional
        - Meta Keyword: optional
        - Meta Description: optional
        - Icon: string or URL
        ";

    return $this->downloadCsvZipTemplate(
      'category',
      'template.csv',
      'template_info.txt',
      'csv_template.zip',
      $csvContent,
      $txtContent
    );
  }

  public function downloadProductCsvTemplate()
  {
    $csvContent = "Product Category,Product Name,Product Description,Product Base SKU\n";
    $txtContent = "Field Descriptions:
        - Product Category : max 255 chars
        - Product Name: max 255 chars
        - Product Description: Text
        - Product Base SKU: max 100 chars
        ";

    return $this->downloadCsvZipTemplate(
      'product',
      'products_template.csv',
      'products_info.txt',
      'csv_products_template.zip',
      $csvContent,
      $txtContent
    );
  }


  public function downloadProductAttributesCsvTemplate()
  {
    $csvContent = "Attribute Name,Attribute Value,Attribute Details\n";
    $txtContent = "Field Descriptions:
      - Attribute Name: max 255 chars
      - Attribute Value: max 255 chars
      - Attribute Details: max 255 chars
      ";

    return $this->downloadCsvZipTemplate(
      'attributes',
      'attributes_template.csv',
      'attributes_info.txt',
      'csv_attributes_template.zip',
      $csvContent,
      $txtContent
    );
  }

  public function downloadPincodeCsvTemplate()
  {
    $csvContent = "Pincode,Estimate Days";
    $txtContent = "Field Descriptions:
        - Pincode : max 255 chars
        - Estimate Days: max 15 chars
        ";

    return $this->downloadCsvZipTemplate(
      'pincode',
      'pincodes_template.csv',
      'pincodes_info.txt',
      'csv_pincodes_template.zip',
      $csvContent,
      $txtContent
    );
  }

  public function importCsvTemplate(CsvImportRequest $request, CsvImportService $csvImportService)
  {
    $rows = $csvImportService->parse($request->file('csv_file'));
    $errors = [];

    $existingCategories = ProductCategory::pluck('id', 'title')->toArray();
    $insertedCount = 0;
    $updatedCount = 0;

    foreach ($rows as $index => $row) {
      $rowNumber = $index + 2;

      $categoryTitle = trim($row['Category Name'] ?? '');
      $parentTitle = trim($row['Parent_Category'] ?? '');
      $parentId = $existingCategories[$parentTitle] ?? 0;

      $data = [
        'Parent_Category'    => $parentTitle,
        'Category Name'      => $categoryTitle,
        'Category Slug'      => trim($row['Category Slug'] ?? ''),
        'Tax Percentage'     => $row['Tax Percentage'] ?? null,
        'Meta Title'         => $row['Meta Title'] ?? null,
        'Meta Keyword'       => $row['Meta Keyword'] ?? null,
        'Meta Description'   => $row['Meta Description'] ?? null,
        'Icon'               => $row['Icon'] ?? null,
        'Sequence'           => $row['Sequence'] ?? 1,
        'Status'             => $row['Status'] ?? 1,
      ];

      $rules = [
        'Parent_Category'    => 'nullable|string',
        'Category Name'      => 'required|string|max:255',
        'Category Slug'      => 'required|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        'Tax Percentage'     => 'required|numeric|max:100',
        'Meta Title'         => 'required|string',
        'Meta Keyword'       => 'required|string',
        'Meta Description'   => 'required|string'
      ];

      $validationErrors = $this->validateRow($data, $rules, $rowNumber);
      if (!empty($validationErrors)) {
        $errors = array_merge($errors, $validationErrors);
        continue;
      }

      $mappedRow = [
        'parent_id'      => $parentId,
        'title'          => $categoryTitle,
        'slug'           => $data['Category Slug'],
        'tax'            => $data['Tax Percentage'],
        'meta_title'     => $data['Meta Title'],
        'meta_keywords'  => $data['Meta Keyword'],
        'meta_desc'      => $data['Meta Description'],
        'icon'           => $data['Icon'],
        'sequence'       => 1,
        'status'         => 1,
      ];

      if (isset($existingCategories[$categoryTitle])) {
        ProductCategory::where('title', $categoryTitle)->update($mappedRow);
        $updatedCount++;
      } else {
        $newCategory = ProductCategory::create($mappedRow);
        $existingCategories[$categoryTitle] = $newCategory->id;
        $insertedCount++;
      }
    }

    if (!empty($errors)) {
      return response()->json([
        'status' => false,
        'msg' => 'Some rows failed validation',
        'errors' => $errors,
      ], 422);
    }

    return response()->json([
      'status' => true,
      'msg' => "CSV uploaded successfully. Inserted: {$insertedCount}, Updated: {$updatedCount}.",
    ]);
  }

  public function importProductAttributeCsvTemplate(CsvImportProductAttributeRequest $request, CsvImportService $csvImportService)
  {
    $rows = $csvImportService->parse($request->file('csv_attr_file'));
    $insertedCount = $updatedCount = 0;
    $rowErrors = [];
    $attributeList = [];

    foreach ($rows as $index => $row) {
      $rowNumber = $index + 2;
      $name = trim($row['Attribute Name'] ?? '');
      $value = trim($row['Attribute Value'] ?? '');
      $details = trim($row['Attribute Details'] ?? '');
      $data = [
        'Attribute Name' => $name,
        'Attribute Value' => $value,
        'Attribute Details' => $details,
      ];

      $rules = [
        'Attribute Name' => 'required|max:255',
        'Attribute Value' => 'required|max:255',
        'Attribute Details' => 'nullable|max:255',
      ];

      $errors = $this->validateRow($data, $rules, $rowNumber);
      if (!empty($errors)) {
        $rowErrors = array_merge($rowErrors, $errors);
        continue;
      }
      // Step 1: Get or create attribute
      $attributeId = $attributeList[$name] ?? ProductAttribute::where('name', $name)->value('id');
      if (!$attributeId) {
        $attributeId = ProductAttribute::create(['name' => $name])->id;
      }
      $attributeList[$name] = $attributeId;

      // Step 2: Update or create value
      $attributeValue = ProductAttributeValue::firstOrNew([
        'attribute_id' => $attributeId,
        'value' => $value,
      ]);

      if ($attributeValue->exists) {
        $attributeValue->value_details = $details;
        $attributeValue->save();
        $updatedCount++;
      } else {
        $attributeValue->value_details = $details;
        $attributeValue->save();
        $insertedCount++;
      }
    }

    return !empty($rowErrors)
      ? response()->json(['status' => false, 'msg' => 'CSV import failed due to validation errors.', 'errors' => $rowErrors], 422)
      : response()->json(['status' => true, 'msg' => "CSV processed. Inserted: {$insertedCount}, Updated: {$updatedCount}."]);
  }

  public function importProductsCsvTemplate(CsvImportProductRequest $request, CsvImportService $csvImportService)
  {
    $rows = $csvImportService->parse($request->file('csv_products_file'));
    $insertedCount = $updatedCount = 0;
    $rowErrors = [];
    $categoryCache = [];

    foreach ($rows as $index => $row) {
      $rowNumber = $index + 2;

      // Prepare row data
      $data = [
        'Product Category' => trim($row['Product Category'] ?? ''),
        'Product Name' => trim($row['Product Name'] ?? ''),
        'Product Description' => trim($row['Product Description'] ?? ''),
        'Product Base SKU' => trim($row['Product Base SKU'] ?? ''),
      ];

      $rules = [
        'Product Category' => 'required|max:255',
        'Product Name' => 'required|max:255',
        'Product Description' => 'nullable',
        'Product Base SKU' => 'required|max:100',
      ];

      $errors = $this->validateRow($data, $rules, $rowNumber);

      if (!empty($errors)) {
        $rowErrors = array_merge($rowErrors, $errors);
        continue;
      }

      $categoryName = $data['Product Category'];
      $productName = $data['Product Name'];
      $productDescription = $data['Product Description'];
      $productSku = $data['Product Base SKU'];

      // Find or create category
      $category = $categoryCache[$categoryName] ??= ProductCategory::firstOrCreate(
        ['title' => $categoryName],
        ['slug' => Str::slug($categoryName), 'status' => 1, 'sequence' => 1]
      );

      // Prepare product data
      $productData = [
        'name' => $productName,
        'description' => $productDescription,
        'sku' => $productSku,
        'status' => 1,
      ];

      // Find product inside category by sku or name
      $product = $category->products()
        ->where('sku', $productSku)
        ->orWhere('name', $productName)
        ->first();

      // Update or create product
      if ($product) {
        $product->update($productData);
        $updatedCount++;
      } else {
        $category->products()->create($productData);
        $insertedCount++;
      }
    }

    if (!empty($rowErrors)) {
      return response()->json([
        'status' => false,
        'msg' => 'CSV import failed due to validation errors.',
        'errors' => $rowErrors,
      ], 422);
    }

    return response()->json([
      'status' => true,
      'msg' => "CSV processed successfully. Inserted: {$insertedCount}, Updated: {$updatedCount}.",
    ]);
  }

  public function importPincodesCsvTemplate(CsvImportPincodeRequest $request, CsvImportService $csvImportService)
  {
    $rows = $csvImportService->parse($request->file('csv_pincodes_file'));
    $insertedCount = $updatedCount = 0;
    $rowErrors = [];


    foreach ($rows as $index => $row) {
      $rowNumber = $index + 2;

      // Prepare row data
      $data = [
        'Pincode' => trim($row['Pincode'] ?? ''),
        'Estimate Days' => trim($row['Estimate Days'] ?? ''),
      ];

      $rules = [
        'Pincode' => 'required|string|max:15',
        'Estimate Days' => 'required|string|max:15',
      ];

      $errors = $this->validateRow($data, $rules, $rowNumber);

      if (!empty($errors)) {
        $rowErrors = array_merge($rowErrors, $errors);
        continue;
      }

      $pincode = $data['Pincode'];
      $estimateDays = $data['Estimate Days'];

      // Find or create category
      $category = Pincode::firstOrCreate(
        ['code' => $pincode],
        [
          'status' => 1,
          'estimate_days' => $estimateDays
        ]
      );
    }

    if (!empty($rowErrors)) {
      return response()->json([
        'status' => false,
        'msg' => 'CSV import failed due to validation errors.',
        'errors' => $rowErrors,
      ], 422);
    }

    return response()->json([
      'status' => true,
      'msg' => "CSV processed successfully. Inserted: {$insertedCount}, Updated: {$updatedCount}.",
    ]);
  }

  private function validateRow(array $data, array $rules, int $rowNumber): array
  {
    $errors = [];

    // Forbidden SQL keywords
    $forbiddenKeywords = ['truncate', 'drop', 'delete', 'insert', 'update', 'alter', 'exec', 'create', 'replace'];

    // Prepare custom attribute names to preserve original case
    $customAttributes = array_combine(array_keys($data), array_keys($data));

    // Validate with custom attributes
    $validator = Validator::make($data, $rules, [], $customAttributes);

    if ($validator->fails()) {
      foreach ($validator->errors()->all() as $error) {
        $errors[] = "Row {$rowNumber}: {$error}";
      }
    }

    // Check forbidden keywords
    foreach ($data as $label => $value) {
      foreach ($forbiddenKeywords as $keyword) {
        if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $value)) {
          $errors[] = "Row {$rowNumber}: {$label} contains forbidden SQL keyword '{$keyword}'.";
          break;
        }
      }
    }

    return $errors;
  }
}
