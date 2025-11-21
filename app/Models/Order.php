<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Order extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'user_notes',
    'order_number',
    'transaction_id',
    'payment_method',
    'payment_status',
    'payment_failure_reason',
    'payment_details',
    'coupon_type',
    'coupon_id',
    'coupon_discount',
    'order_total',
    'shipping_charge',
    'net_total',
    'total_tax',
    'order_status',
    'shipping_address',
    'billing_address',
    'other_charges',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  protected $casts = [
    'coupon_discount' => 'decimal:2',
    'order_total' => 'decimal:2',
    'shipping_charge' => 'decimal:2',
    'net_total' => 'decimal:2',
  ];
  protected $appends = ['order_status_text', 'payment_method_display'];

  public function getPaymentMethodDisplayAttribute(): string
  {
    $payment_method = array(
      0 => 'Cash On Delivery (COD)',
      1 => 'Online Payment',
    );

    return $payment_method[$this->payment_method];
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function orderProducts()
  {
    return $this->hasMany(OrderProduct::class);
  }

  public function coupon()
  {
    return $this->belongsTo(Coupon::class);
  }

  public function createdBy()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function updatedBy()
  {
    return $this->belongsTo(User::class, 'updated_by');
  }

  public function requests()
  {
    return $this->hasMany(OrderReturn::class);
  }


  public static function scopeSearch($query, $search)
  {
    return $query->where('order_number', 'like', '%' . $search . '%')
      ->orWhere('order_total', 'like', '%' . $search . '%');
  }
  public function orderHistories()
  {
    return $this->hasMany(OrderHistory::class, 'order_id');
  }


  public static function orderStatusUpdate($data, int $id = 0)
  {
    $statuses = [
      0 => 'Awaiting Confirmation',
      1 => 'Confirmed',
      2 => 'Cancellation Initiated',
      3 => 'Cancelled',
      4 => 'Shipped',
      5 => 'Delivered',
      6 => 'Return Accepted',
      7 => 'Refund Done',
    ];
    $order = self::find($id);

    if (!$order) {
      return response()->json([
        'success' => false,
        'message' => __('response.not_found', ['item' => 'Order'])
      ]);
    }

    $decodedStatus = Hashids::decode($data->order_status)[0] ?? null;

    if ($decodedStatus === null) {
      return response()->json([
        'success' => false,
        'message' => __('response.not_found', ['item' => 'Order Status'])
      ]);
    }

    // Get last status (from history or current)
    $lastStatus = $order->orderHistories()
      ->orderByDesc('created_at')
      ->value('status') ?? $order->order_status;

    // Define valid transitions
    $validTransitions = [
      0 => [1, 2],     // Awaiting Confirmation → Confirmed or Cancel Initiated
      1 => [4, 2],     // Confirmed → Shipped or Cancel Initiated
      2 => [3],        // Cancel Initiated → Cancelled
      4 => [5],        // Shipped → Delivered
      5 => [6],        // Delivered → Return Accepted
      6 => [7],        // Return Accepted → Refund Done
    ];


    $finalStates = [3, 7];
    if (in_array($lastStatus, $finalStates)) {
      return response()->json([
        'success' => false,
        'message' => 'No further updates allowed after order is ' . __($statuses[$lastStatus]) . '.'
      ]);
    }


    if (!in_array($decodedStatus, $validTransitions[$lastStatus] ?? [])) {
      return response()->json([
        'success' => false,
        'message' => 'Invalid transition from ' . __($statuses[$lastStatus]) . ' to ' . __($statuses[$decodedStatus]) . '.'
      ]);
    }

    $existingHistory = $order->orderHistories()
      ->where('status', $decodedStatus)
      ->first();

    if ($existingHistory) {
      return response()->json([
        'success' => false,
        'message' => 'This status has already been recorded.'
      ]);
    }

    // ✅ Update Order & History
    $order->update([
      'order_status' => $decodedStatus,
      'updated_by' => user('admin')->id,
    ]);

    $order->orderHistories()->create([
      'scheduled_date' => formatDate($data->scheduled_date),
      'scheduled_time' => $data->scheduled_time ?? null,
      'description' => $data->description ?? '',
      'status' => $decodedStatus,
    ]);

    return response()->json([
      'success' => true,
      'message' => __('response.success.update', ['item' => 'Order Status']),
      'value' => Hashids::encode($order->id)
    ]);
  }


  public function getOrderStatusTextAttribute()
  {
    $statuses = [
      0 => 'Awaiting Confirmation',
      1 => 'Confirmed',
      2 => 'Cancellation Initiated',
      3 => 'Cancelled',
      4 => 'Shipped',
      5 => 'Delivered',
      6 => 'Return Accepted',
      7 => 'Refund Done',
    ];

    return $statuses[$this->order_status] ?? 'N/A';
  }

  public function getVariantSkusAttribute()
  {
    return $this->orderProducts
      ->map(fn($op) => optional($op->variant)->sku)
      ->filter()
      ->implode(', ');
  }

  public function variant()
  {
    return $this->belongsTo(ProductVariant::class);
  }

  public static function saleStatus($daterange = null)
  {
    return self::query()
      ->selectRaw('MONTH(created_at) as month')
      ->selectRaw('MONTHNAME(created_at) as month_name')
      ->selectRaw('COUNT(*) as order_count')
      ->selectRaw('SUM(net_total) as total_sales')
      ->where('payment_status', 1)
      ->groupByRaw('MONTH(created_at), MONTHNAME(created_at)')
      ->orderByRaw('MONTH(created_at)')
      ->when($daterange, function ($query) use ($daterange) {
        return $query->whereBetween('created_at', $daterange);
      })
      ->get();
  }

  public static function revenueGenerate($daterange = null)
  {
    return self::query()
      ->selectRaw('MONTH(created_at) as month')
      ->selectRaw('MONTHNAME(created_at) as month_name')
      ->selectRaw('COUNT(*) as order_count')
      ->selectRaw('SUM(net_total) as total_sales')
      ->where('payment_status', 1)
      ->whereIn('order_status', [1, 5])
      ->groupByRaw('MONTH(created_at), MONTHNAME(created_at)')
      ->orderByRaw('MONTH(created_at)')
      ->when($daterange, function ($query) use ($daterange) {
        return $query->whereBetween('created_at', $daterange);
      })
      ->get();
  }

  public static function topSellingProducts($daterange = null)
  {
    return OrderProduct::with('variant.product')
      ->join('product_variants', 'order_products.variant_id', '=', 'product_variants.id')
      ->join('products', 'product_variants.product_id', '=', 'products.id')
      ->whereHas('order', function ($query) use ($daterange) {
        $query->where('payment_status', 1)
          ->when($daterange && count($daterange) === 2, function ($q) use ($daterange) {
            $q->whereBetween('created_at', $daterange);
          });
      })
      ->selectRaw('products.name as product_name, COUNT(*) as order_count')
      ->groupBy('products.name')
      ->orderByDesc('order_count')
      ->limit(10)
      ->get();
  }
}
