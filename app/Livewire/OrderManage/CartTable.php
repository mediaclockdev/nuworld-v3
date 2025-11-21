<?php

namespace App\Livewire\OrderManage;

use App\Livewire\BaseComponent;
use App\Models\Cart;
use Vinkla\Hashids\Facades\Hashids;

class CartTable extends BaseComponent
{
  public $filename = '';
  public $userId;

  public $columnsWithAliases = [
    'sl'                        => 'Sl.',
    'userDetails->full_name'    => 'Customer Name',
    'userDetails->email'        => 'Email',
    'productVariant->name'      => 'Product Name',
    'quantity'                  => 'Quantity',
    'created_at'                => 'Added On',
  ];

  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'moduleChangedComponent' => 'moduleChanged',
    ]);

    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }

  public function moduleChanged($userId)
  {
    $this->userId = $userId ? Hashids::decode($userId)[0] : '';
  }

  public function getQuery($filtered = false)
  {
    $sortColumnMap = [
      'userDetails->first_name' => 'users.first_name',
      'userDetails->email' => 'users.email',
    ];

    $sortColumn = $sortColumnMap[$this->sortColumn] ?? $this->sortColumn;

    $query = Cart::query()
      ->where('carts.is_saved_for_later', 0)
      ->where('carts.status', 0)
      ->when(
        in_array($this->sortColumn, array_keys($sortColumnMap)),
        function ($query) {
          $query->join('users', 'carts.user_id', '=', 'users.id')
            ->select(
              'carts.*',
              'users.first_name as user_first_name',
              'users.email as user_email'
            );
        },
        function ($query) {
          $query->with(['userDetails', 'productVariant']);
        }
      );

    if ($this->userId) {
      $query->where('carts.user_id', $this->userId);
    }

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('carts.created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('carts.updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    return $query->orderBy($sortColumn, $this->sortDirection);
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $carts = $this->getQuery(true)->paginate($this->perPage);
    // $customers = Cart::where('is_saved_for_later', 1)->groupBy('user_id')->get();
    $serialNumber = ($carts->currentPage() - 1) * $carts->perPage() + 1;
    return view('livewire.order-manage.cart-table', ['carts' => $carts, 'serialNumber' => $serialNumber]);
  }
}
