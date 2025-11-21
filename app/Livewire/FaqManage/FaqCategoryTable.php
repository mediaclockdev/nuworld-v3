<?php

namespace App\Livewire\FaqManage;

use App\Livewire\BaseComponent;
use App\Models\FaqCategory;

class FaqCategoryTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'name'                => 'Category',
    'status'              => 'Status',
    'created_at'          => 'Added On',
  ];

  protected $listeners = ['refreshComponent' => '$refresh'];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'Desc';
  }

  public function getQuery($filtered = false)
  {
    $query = FaqCategory::query();

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->where(function ($q) {
            $q->whereBetween('created_at', [$this->startDate, $this->endDate])
              ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
          });
        });
    }
    return $query->orderBy($this->sortColumn, $this->sortDirection);
  }

  public function exportToCSV($filtered = false)
  {
    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $faqCategories = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($faqCategories->currentPage() - 1) * $faqCategories->perPage() + 1;
    return view('livewire.faq-manage.faq-category-table', ['faqCategories' => $faqCategories, 'serialNumber' => $serialNumber]);
  }
}
