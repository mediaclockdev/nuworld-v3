<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\User;
use Vinkla\Hashids\Facades\Hashids;

class ProductReviewTable extends BaseComponent
{
  public $filename = '';
  public $customerId;
  public $customerVariantId;

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'user->full_name'     => 'Customer Name',
    'user->email'                => 'Customer Email',
    'variant->name'                 => 'Product',
    'rating'                 => 'Rating',
    'productreview'       => 'Comment',
    'status'              => 'Status',
    'created_at'          => 'Created Date',
  ];
  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'customerChangedComponent' => 'customerChanged',
      'customerVariantChangedComponent' => 'customerVariantChanged',
    ]);

    $this->sortColumn = 'user_id';
    $this->sortDirection = 'ASC';
  }
  public function customerChanged($customerId)
  {
    $this->customerId = $customerId ? Hashids::decode($customerId)[0] : '';
  }

  public function customerVariantChanged($customerVariantId)
  {
    $this->customerVariantId = $customerVariantId ? Hashids::decode($customerVariantId)[0] : '';
  }

  public function getQuery($filtered = false)
  {
    $query = ProductReview::with('user', 'variant')->orderBy($this->sortColumn, $this->sortDirection);

    if ($this->customerId) {
      $query->where('user_id', $this->customerId);
    }
    if ($this->customerVariantId) {
      $query->where('variant_id', $this->customerVariantId);
    }

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }

    return $query;
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $customers = User::whereIn('status', [1, 2])->get();
    $productVariants = ProductVariant::whereHas('variantReviews')
      ->with('variantReviews')
      ->get();
    $productRevews = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($productRevews->currentPage() - 1) * $productRevews->perPage() + 1;

    return view('livewire.product-manage.product-review-table', ['productRevews' => $productRevews, 'serialNumber' => $serialNumber, 'customers' => $customers, 'productVariants' => $productVariants]);
  }
}
