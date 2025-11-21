<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExportController extends Controller
{
  public function export(Request $request): JsonResponse
  {
    $csvData = $request->input('csvData');
    return response()->json(['csv' => $csvData]);
  }
}
