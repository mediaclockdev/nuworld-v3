<?php

namespace App\Enums;

class OrderStatus
{
  public const INACTIVE = 0;
  public const CONFIRMED = 1;
  public const CANCELLATION_INITIATED = 2;
  public const CANCELLED = 3;
  public const SHIPPED = 4;
  public const DELIVERED = 5;
  public const RETURN_ACCEPTED = 6;
  public const REFUND_DONE = 7;

  public static function labels(): array
  {
    return [
      self::INACTIVE => 'Inactive',
      self::CONFIRMED => 'Confirmed',
      self::CANCELLATION_INITIATED => 'Cancellation Initiated',
      self::CANCELLED => 'Cancelled',
      self::SHIPPED => 'Shipped',
      self::DELIVERED => 'Delivered',
      self::RETURN_ACCEPTED => 'Return Accepted',
      self::REFUND_DONE => 'Refund Done',
    ];
  }

  public static function label(int $status): string
  {
    return self::labels()[$status] ?? 'Unknown';
  }

  public static function options(): array
  {
    return array_map(fn($label, $value) => ['value' => $value, 'label' => $label], self::labels(), array_keys(self::labels()));
  }
}
