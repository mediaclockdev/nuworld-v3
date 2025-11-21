<?php

namespace App\Livewire\System;

use App\Livewire\BaseComponent;
use App\Models\PaymentSettings;

class PaymentsTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'              => 'Sl.',
    'gateway_name'    => 'Name',
    'gateway_mode'    => 'Mode',
    'gateway_key'     => 'Key',
    'gateway_secret'  => 'Secret',
    'status'          => 'Status',
    'created_by'      => 'Created By',
    'created_at'      => 'Created Date',
    'updated_by'      => 'Updated By',
    'updated_at'      => 'Updated Date',
  ];
  public function __construct()
  {
    $this->sortColumn = 'gateway_name';
    $this->sortDirection = 'ASC';
  }
  public function getQuery($filtered = false)
  {
    $query = PaymentSettings::orderBy($this->sortColumn, $this->sortDirection);

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
    $this->filename = 'Payment Options_export_' . now()->format('Ymd_His');

    return $this->exportCSVTrait($this->getQuery($filtered), $this->columnsWithAliases, $this->sortColumn, $this->sortDirection, $this->filename);
  }

  public function render()
  {
    $paymentGateways = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($paymentGateways->currentPage() - 1) * $paymentGateways->perPage() + 1;

    return view('livewire.system.payments-table', ['payment_gateways' => $paymentGateways, 'serialNumber' => $serialNumber]);
  }
}
