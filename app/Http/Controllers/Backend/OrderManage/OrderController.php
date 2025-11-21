<?php

namespace App\Http\Controllers\Backend\OrderManage;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use App\Services\HuggingFaceService;

class OrderController extends Controller
{
  protected string $name;
  protected $model;
  public function __construct()
  {
    $this->name = 'Order';
    $this->model = Order::class;
    view()->share('pageTitle', 'Manage ' . $this->name);
  }

  public function index(): View
  {
    return view('backend.pages.order-manage.orders.index', ['cardHeader' => $this->name . ' List']);
  }

  public function edit(int $id = 0): View
  {
    $order = Order::find($id);
    if (!$order)
      abort(404);

    return view('backend.pages.order-manage.orders.form', ['cardHeader' => 'Edit ' . $this->name, 'order' => $order]);
  }

  public function downloadInvoice($id)
  {
    $pdf = $this->generateInvoicePdf($id, [
      'paper' => 'A4',
      'margin-right' => 0,
      'margin-left' => 0,
      'margin-top' => 0,
      'margin-bottom' => 0,
      'disable-javascript' => true,
    ]);

    return $pdf->download('Invoice-' . $this->getOrder($id)->order_number . '.pdf');
  }


  public function chatbotAsk(Request $request, HuggingFaceService $hf)
  {
    $question = $request->input('message');

    // 1. Check if it's an order-related question (order number can be numeric or alphanumeric)
    if (preg_match('/order[ #:-]*([A-Za-z0-9\-]+)/i', $question, $matches)) {
      $orderNumber = $matches[1];
      $order = Order::where('order_number', $orderNumber)->first();

      if ($order) {
        $statusLabel = OrderStatus::label($order->order_status); // ✅ Map int to readable status

        return response()->json([
          'reply' => "Order #{$order->order_number} is currently **{$statusLabel}**, placed on {$order->created_at->format('d M Y')}, with a total of " . displayPrice($order->net_total),
        ]);
      } else {
        return response()->json([
          'reply' => "Sorry, I couldn’t find order #{$orderNumber}."
        ]);
      }
    }

    // 2. Otherwise → use Hugging Face AI
    $result = $hf->query($question);
    $reply = $result['generated_text'] ?? "Sorry, I don’t understand that.";

    return response()->json(['reply' => $reply]);
  }
  /**
   * Generate and stream the invoice PDF for printing.
   *
   * @param int $id Order ID
   * @return \Illuminate\Http\Response
   */
  public function printInvoice($id)
  {
    $pdf = $this->generateInvoicePdf($id, [
      'paper' => 'A4',
      'orientation' => 'portrait',
      'dpi' => 96,
    ]);

    return $pdf->stream('Invoice-' . $this->getOrder($id)->order_number . '.pdf');
  }

  /**
   * Fetch the order with related data.
   *
   * @param int $id Order ID
   * @return \App\Models\Order
   */
  private function getOrder($id)
  {
    return Order::with([
      'orderProducts.variant.images' => function ($query) {
        $query->where('is_default', 1);
      },
      'coupon'
    ])->findOrFail($id);
  }

  /**
   * Generate the PDF instance for the invoice.
   *
   * @param int $id Order ID
   * @param array $config PDF configuration options
   * @return \Barryvdh\DomPDF\PDF
   */
  private function generateInvoicePdf($id, array $config)
  {
    $order = $this->getOrder($id);
    $pdf = Pdf::loadView('backend.pages.order-manage.orders.invoice-order', compact('order'));

    // Default options for both download and print
    $defaultOptions = [
      'isHtml5ParserEnabled' => true,
      'isRemoteEnabled' => true,
      'defaultFont' => 'DejaVu Sans',
    ];

    // Apply configuration
    if (isset($config['paper'])) {
      $pdf->setPaper($config['paper'], $config['orientation'] ?? 'portrait');
      unset($config['paper'], $config['orientation']);
    }

    $pdf->setOptions(array_merge($defaultOptions, $config));


    return $pdf;
  }
}
