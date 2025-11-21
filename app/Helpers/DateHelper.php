<?php

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

if (!function_exists('convertDate')) {
  /**
   * Convert UTC date to only date format.
   *
   * @param string|null $utcDate
   * @return string|null
   */
  function convertDate(?string $utcDate = null): ?string
  {
    return formatDate($utcDate, config('defaults.date_format'));
  }
}

if (!function_exists('convertDateTimeHours')) {
  /**
   * Convert UTC date to date and time in 24-hour format.
   *
   * @param string|null $utcDate
   * @return string|null
   */
  function convertDateTimeHours(?string $utcDate = null): ?string
  {
    return formatDate(
      $utcDate,
      config('defaults.date_format') . ' ' . config('defaults.time_format')
    );
  }
}

if (!function_exists('convertDateTime')) {
  /**
   * Convert UTC date to date and time in 12-hour (am/pm) format.
   *
   * @param string|null $utcDate
   * @return string|null
   */
  function convertDateTime(?string $utcDate = null): ?string
  {
    $timeFormat = str_replace('H', 'h', config('defaults.time_format')) . ' A';
    return formatDate(
      $utcDate,
      config('defaults.date_format') . ' ' . $timeFormat
    );
  }
}

if (!function_exists('formatDate')) {
  /**
   * Format a date string according to specified format.
   *
   * @param string|null $date
   * @param string $customFormat
   * @return string|null
   */
  function formatDate(?string $date, string $customFormat = 'Y-m-d H:i:s'): ?string
  {
    if (empty($date)) {
      return null;
    }

    try {
      $dateFormat = config('defaults.date_format');
      $timeFormat = config('defaults.time_format');

      $hasTime = preg_match('/\d{1,2}:\d{1,2}(:\d{1,2})?/', $date);
      $sourceFormat = $hasTime ? "{$dateFormat} {$timeFormat}" : $dateFormat;

      return Carbon::createFromFormat($sourceFormat, $date)
        ->format($customFormat);
    } catch (InvalidFormatException $e) {
      // Fallback to Carbon's native parsing if format doesn't match
      try {
        return Carbon::parse($date)->format($customFormat);
      } catch (InvalidFormatException $e) {
        logger()->error("Failed to parse date: {$date}", ['error' => $e]);
        return null;
      }
    }
  }
}

if (!function_exists('convertDate')) {
  function convertDate($date)
  {
    $formats = [
      'd/m/Y H:i:s',
      'd/m/Y H:i',
      'd/m/Y',
      'Y-m-d H:i:s',
      'Y-m-d H:i',
      'Y-m-d',
    ];

    $date = trim($date);

    foreach ($formats as $format) {
      try {
        $parsed = Carbon::createFromFormat($format, $date);
        // Check if the parsed date exactly matches the input (avoids trailing data issues)
        if ($parsed && $parsed->format($format) === $date) {
          return $parsed;
        }
      } catch (\Exception $e) {
        // Continue trying other formats
      }
    }

    throw new \InvalidArgumentException("Invalid date format or unrecognized date: {$date}");
  }
}
