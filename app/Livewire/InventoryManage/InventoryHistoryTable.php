<?php

namespace App\Livewire\InventoryManage;

use App\Livewire\BaseComponent;
use App\Models\Inventory;
use App\Models\InventoryHistory;


class InventoryHistoryTable extends BaseComponent
{
  public $inventoryValue;
  public function mount(Inventory $inventoryValue)
  {
    $this->inventoryValue = $inventoryValue;
    $this->sortColumn = 'updated_at';
    $this->sortDirection = 'DESC';
  }

  public function getQuery()
  {
    return InventoryHistory::where('inventory_id', $this->inventoryValue->id)->orderBy($this->sortColumn, $this->sortDirection);
  }
  public function render()
  {
    $inventoryHistory = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($inventoryHistory->currentPage() - 1) * $inventoryHistory->perPage() + 1;

    return view('livewire.inventory-manage.inventory-history-table', ['inventoryHistory' => $inventoryHistory, 'serialNumber' => $serialNumber]);
  }
}
