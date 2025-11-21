<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ExportCSV
{
  public $search = '';

  public function exportCSVTrait($query, $columns, $sortColumn = null, $sortDirection = 'ASC', $filename = null)
  {
    if (!$query) {
      return response()->json(['error' => 'Invalid query provided.'], 400);
    }

    // Fetch and sort data
    $data = $query->orderBy($sortColumn, $sortDirection)->get();

    if ($data->isEmpty()) {
      return response()->json(['error' => 'No data found for export.'], 404);
    }

    // Set filename if not provided
    $filename = $filename ?: $this->generateFilename($query->getModel());

    $headers = [
      "Content-type" => "text/csv",
      "Content-Disposition" => "attachment; filename=$filename",
      "Pragma" => "no-cache",
      "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
      "Expires" => "0",
    ];

    return response()->streamDownload(function () use ($data, $columns) {
      $this->generateCSV($data, $columns);
    }, $filename, $headers);
  }

  private function generateFilename($model)
  {
    return strtolower(class_basename($model)) . '_export_' . now()->format('Ymd_His') . '.csv';
  }

  private function generateCSV($data, $columns)
  {
    $handle = fopen('php://output', 'w');

    fwrite($handle, "\xEF\xBB\xBF");

    // Write header row
    fputcsv($handle, array_values($columns));

    // Write data rows
    $serialNumber = 1;
    foreach ($data as $row) {
      $rowData = array_map(function ($key) use ($row, &$serialNumber) {
        return $this->getRowData($row, $key, $serialNumber);
      }, array_keys($columns));

      // Ensure UTF-8 encoding for symbols before writing to CSV
      $rowData = array_map(function ($value) {
        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
      }, $rowData);

      fputcsv($handle, $rowData);
    }

    fclose($handle);
  }

  private function getRowData($row, $key, &$serialNumber)
  {
    if ($key === 'sl') {
      return $serialNumber++;
    }

    if (in_array($key, ['created_by', 'updated_by', 'reviewed_by'])) {
      return $row->$key ? userNameById('admin', $row->$key) : 'N/A';
    }

    if (in_array($key, ['created_at', 'updated_at', 'reviewed_at', 'requested_at'], true)) {
      return $row->$key ? convertDateTimeHours($row->$key) : 'N/A';
    }

    if ($key === 'status') {
      return match ($row->$key) {
        1 => 'Active',
        0 => 'Inactive',
        default => $row->$key,
      };
    }

    // Handle deeply nested relationships using -> (e.g., variant->product->category->title)
    if (strpos($key, '->') !== false) {
      $segments = explode('->', $key);
      $value = $row;

      foreach ($segments as $segment) {
        if (is_null($value)) break;
        $value = $value->{$segment} ?? null;
      }

      return $value ?? 'N/A';
    }

    // Optional: handle multi-column fallback with dot (e.g., name.full)
    if (strpos($key, '.') !== false) {
      $columns = explode('.', $key);
      $values = array_map(function ($column) use ($row) {
        return $row->$column ?? '';
      }, $columns);

      return implode(' ', $values);
    }

    return $row->{$key};
  }
}
