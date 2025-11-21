<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\OrderReturnItem;
use Illuminate\Http\Request;

class CustomerOrderReturnController extends Controller
{
  public function store(Request $request)
  {
    $validated = $request->validate([
      'order_id' => 'required|exists:orders,id',
      'type' => 'required|in:cancel,return',
      'reason' => 'nullable|string',
      'items' => 'required|array',
      'items.*.order_item_id' => 'required|exists:order_items,id',
      'items.*.quantity' => 'required|integer|min:1',
      'items.*.reason' => 'nullable|string',
    ]);

    $OrderReturn = OrderReturn::create([
      'order_id' => $validated['order_id'],
      'user_id' => user()->id,
      'type' => $validated['type'],
      'reason' => $validated['reason'] ?? null,
      'requested_at' => now(),
    ]);

    foreach ($validated['items'] as $item) {
      OrderReturnItem::create([
        'order_request_id' => $OrderReturn->id,
        'order_item_id' => $item['order_item_id'],
        'quantity' => $item['quantity'],
        'reason' => $item['reason'] ?? null,
      ]);
    }

    return response()->json(['message' => 'Your request has been submitted successfully.']);
  }
}
