<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;
use App\Models\AdminActivity;

class LoginHistoryTable extends BaseComponent
{
  public $filename = 'Login_History';

  public $columnsWithAliases = [
    'sl'          => 'Sl.',
    'created_at'  => 'Login Time',
    'ip_address'  => 'IP Address',
    'location'    => 'Location',
    'browser'     => 'Browser',
    'os'          => 'OS',
    'device'      => 'Device',
  ];

  public function __construct()
  {
    $this->sortColumn = 'created_at';
    $this->sortDirection = 'DESC';
  }

  public function getQuery($filtered = false)
  {
    $query = AdminActivity::where('user_id', user('admin')->id)->orderBy($this->sortColumn, $this->sortDirection);

    if ($filtered) {
      $query->search($this->search)
        ->when($this->startDate && $this->endDate, function ($query) {
          $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
    $History = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($History->currentPage() - 1) * $History->perPage() + 1;

    return view('livewire.admin.login-history-table', ['histories' => $History, 'serialNumber' => $serialNumber]);
  }
}
