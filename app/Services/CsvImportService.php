<?php

namespace App\Services;

class CsvImportService
{
  public function parse($file): array
  {
    $rows = [];
    if (($handle = fopen($file, 'r')) !== false) {
      $header = null;
      while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        if (!$header) {
          $header = $data;
        } else {
          $rows[] = array_combine($header, $data);
        }
      }
      fclose($handle);
    }
    return $rows;
  }
}
