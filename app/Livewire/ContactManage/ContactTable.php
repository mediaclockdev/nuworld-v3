<?php

namespace App\Livewire\ContactManage;

use App\Livewire\BaseComponent;
use App\Models\Suppoort;
use App\Models\Support;
use Livewire\Component;

class ContactTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'first_name'          => 'First Name',
    'last_name'           => 'Last Name',
    'email'               => 'Email',
    'created_at'          => 'Added On',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'Desc';
  }

  public function getQuery($filtered = false)
  {
    $query = Support::query();
    $query->selectRaw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS name, supports.*");

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
    $contacts = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($contacts->currentPage() - 1) * $contacts->perPage() + 1;
    return view('livewire.contact-manage.contact-table', ['contacts' => $contacts, 'serialNumber' => $serialNumber]);
  }
}
