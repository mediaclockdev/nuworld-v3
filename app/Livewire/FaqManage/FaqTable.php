<?php

namespace App\Livewire\FaqManage;

use App\Livewire\BaseComponent;
use App\Models\Faq;
use Livewire\Component;

class FaqTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'question'            => 'Question',
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
    $query = Faq::query();

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
    $faqs = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($faqs->currentPage() - 1) * $faqs->perPage() + 1;
    return view('livewire.faq-manage.faq-table', ['faqs' => $faqs, 'serialNumber' => $serialNumber]);
  }
}
