<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\{Banner, ProductCategory, ProductAttribute, ProductAttributeValue, Product, ProductVariant, ProductVariantAttribute};
use Database\Seeders\BaseFunctions\BaseFunctions;

// use Database\Seeders\{ProductCategorySeeder, ProductAttributeSeeder};

class NewStoreSetupController extends Controller
{
  use BaseFunctions;

  public function __construct()
  {
    view()->share('pageTitle', 'Manage Store Setup');
  }
  public function index()
  {
    return view('backend.pages.system.new-store-setup.form', ['cardHeader' => 'New Store Setup']);
  }

  public function truncateAndSeed(Request $request): JsonResponse
  {
    try {
      // $seederName = str_replace(' ', '', $request->seeder_name);
      $seederName = $request->seeder_name;
      $category = $request->category;

      if ($seederName == 'ProductCategorySeeder') {
        $this->truncateTable();
      }

      set_time_limit(0); // Disable time limit to prevent timeout

      switch ($seederName) {
        case 'ProductCategorySeeder':
          break;
        case 'ProductAttributeValueSeeder':
          break;
        case 'ProductSeeder':
          break;
        case 'ProductVariantImageSeeder':
          break;
        case 'CustomBannerSeeder':
          break;
        case 'CmsPageSeeder':
          break;
        default:
          throw new \Exception('Unknown seeder name');
      }

      $path = match ($category) {
        '1' => 'Furniture',
        '2' => 'Sunglasses',
        '3' => 'BeautyProduct',
        '4' => 'Grocery',
        default => throw new \Exception('Invalid category'),
      };

      // Call seeder
      Artisan::call('db:seed', [
        '--class' => "Database\\Seeders\\{$path}\\{$seederName}"
      ]);

      // Run optimize and clear
      Artisan::call('optimize:clear');
      return response()->json(['message' => 'Seeder completed!', 'success' => true]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
    }
  }
}
