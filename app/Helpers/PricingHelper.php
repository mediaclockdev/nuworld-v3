<?php

use App\Models\Currency;
use App\Models\ProductVariant;
use App\Models\PromotionDetail;
use App\Models\SiteSetting;
use Carbon\Carbon;


if (!function_exists('getSymbolByCurrencyId')) {
  function getSymbolByCurrencyId($currencyId = null)
  {
    $currency = Currency::find($currencyId);
    return $currency?->symbol ?? '';
  }
}

if (!function_exists('formatPrice')) {
  function formatPrice($amount): string
  {
    $currencyId = SiteSetting::where('key', 'currency_id')->value('value');
    $currency = $currencyId ? Currency::find($currencyId) : null;

    $symbol = $currency->symbol ?? '₹';
    $position = $currency->position ?? 'left';
    $decimals = $currency->decimal_places ?? 2;

    $formattedAmount = number_format((float) $amount, $decimals);

    return $position === 'left'
      ? $symbol . $formattedAmount
      : $formattedAmount . $symbol;
  }
}

if (!function_exists('convertDateNew')) {
  function convertDateNew($date)
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

if (!function_exists('findSalePrice')) {
  function findSalePrice($variantID)
  {
    if (!is_numeric($variantID) || $variantID <= 0) {
      throw new InvalidArgumentException('Invalid variant ID');
    }

    $variant = ProductVariant::find($variantID);
    if (!$variant) return null;

    $now = now();
    $regular = (float) $variant->regular_price;
    $sale = $variant->sale_price !== null ? (float) $variant->sale_price : null;

    // Check sale date range
    $saleStart = $variant->sale_start_date ? convertDateNew($variant->sale_start_date) : null;
    $saleEnd = $variant->sale_end_date ? convertDateNew($variant->sale_end_date) : null;

    $isSaleActive = true;
    if ($saleStart && $now->lt($saleStart)) $isSaleActive = false;
    if ($saleEnd && $now->gt($saleEnd)) $isSaleActive = false;
    if (!$isSaleActive || $sale === null) $sale = null;

    // Check for active promotion
    $promotion = PromotionDetail::where('product_variant_id', $variantID)
      ->where('status', '1')
      ->whereHas('promotion', function ($q) use ($now) {
        $q->where('status', '1')
          ->where('promotion_start_from', '<=', $now)
          ->where('promotion_end_to', '>=', $now);
      })
      ->first();

    $promotionPrice = null;
    $promotionId = null;

    if ($promotion && $regular > 0) {
      $promotionId = $promotion->promotion_id;
      $amount = round($promotion->discount_amount, 2);

      $promotionPrice = $promotion->type === 'Percentage'
        ? $regular * (1 - $amount / 100)
        : max(0, $regular - $amount);
    }

    // Determine lowest valid price
    $displayPrice = $regular;
    if ($sale !== null && $promotionPrice !== null) {
      $displayPrice = min($sale, $promotionPrice);
    } elseif ($sale === null && $promotionPrice !== null) {
      $displayPrice = $promotionPrice;
    } elseif ($sale !== null) {
      $displayPrice = $sale;
    }

    // Round values
    $displayPrice = round($displayPrice, 2);
    $regular = round($regular, 2);

    // Discount in percentage
    $discountPercentage = $regular > 0
      ? (($regular - $displayPrice) / $regular) * 100
      : 0;

    $displayDiscount = null;

    if ($discountPercentage >= 1) {
      $displayDiscount = round($discountPercentage) . '%';
    } elseif ($discountPercentage > 0) {
      $displayDiscount = displayPrice($regular - $displayPrice);
    }

    return [
      'display_price'       => $displayPrice,
      'display_discount'    => $displayDiscount,
      'regular_price'       => $regular,
      'promotion_id'        => $promotionId,
      'special_price'        => !empty($promotionId) &&  $promotionId ? true : false,
      'regular_price_true'  => $displayPrice === $regular,
    ];
  }

  if (!function_exists('displayPrice')) {
    function displayPrice($amount, $code = false): string
    {
      $currencySetting = SiteSetting::where('key', 'currency_id')->first();

      $currency = null;

      if ($currencySetting) {
        $currency = Currency::find($currencySetting->value);
      }

      $symbol = $currency?->symbol ?? '₹';
      $position = $currency?->position ?? 'left';
      $currencyCode = $currency?->code ?? 'INR';

      $formattedAmount = number_format((float) $amount, $currency?->decimal_places ?? 2);
      if ($code) {
        return $symbol . ' ' . $formattedAmount . '~' . $currencyCode;
      }

      if ($position === 'left') {
        return $symbol . ' ' . $formattedAmount;
      } else {
        return $formattedAmount . ' ' . $symbol;
      }
    }
  }
}
