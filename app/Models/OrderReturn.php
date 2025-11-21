<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Vinkla\Hashids\Facades\Hashids;
use App\Enums\OrderStatus;

class OrderReturn extends Model
{
  protected $fillable = [
    'order_id',
    'user_id',
    'type',
    'reason',
    'status',
    'admin_response',
    'reviewed_by',
    'reviewed_at',
    'requested_at',
    'responded_at',
  ];

  protected $dates = [
    'reviewed_at',
    'requested_at',
    'responded_at',
  ];

  // Relationships
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function reviewer()
  {
    return $this->belongsTo(Admin::class, 'reviewed_by');
  }

  public function items()
  {
    return $this->hasMany(OrderReturnItem::class);
  }

  public static function store($request) {}

  public static function processUpdate($request, $id = 0): JsonResponse
  {
    $status = Hashids::decode($request->choice)[0] ? 'approved' : 'declined';
    $orderDetails = self::find($id);

    $order = Order::find($orderDetails->order_id);

    $update = $orderDetails->update([
      'status' => $status,
      'admin_response' => $request->admin_reason,
      'reviewed_by' => user('admin')->id,
      'reviewed_at' => now(),
    ]);

    if ($update && $status == 'approved') {
      $order->order_status = $orderDetails->type == 'cancel' ? OrderStatus::CANCELLATION_INITIATED : OrderStatus::RETURN_ACCEPTED;
      $order->save();

      $order->orderHistories()->create([
        'scheduled_date' => now()->format('Y-m-d'),
        'scheduled_time' => now()->format('H:i:s'),
        'description'    => $orderDetails->type == 'cancel' ? 'Cancellation Initiated' : 'Return Accepted',
        'status'         => $order->order_status,
      ]);
    }

    return $update
      ? response()->json(['success' => true, 'message' => __('response.success.update', ['item' => 'Order Return Request'])])
      : response()->json(['success' => false, 'message' => __('response.error.update', ['item' => 'Order Return Request'])]);
  }

  public function scopeSearch($query, $search)
  {
    return $query->where('type', 'like', '%' . $search . '%')
      ->orWhere('status', 'like', '%' . $search . '%')
      ->orWhereHas('order', function ($q) use ($search) {
        $q->where('order_number', 'like', '%' . $search . '%');
      })->orWhereHas('user', function ($q) use ($search) {
        $q->where('email', 'like', '%' . $search . '%');
      });
  }
}
