<?php

namespace App\Livewire\ProductManage;

use App\Livewire\BaseComponent;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;

class AttributeValueTable extends BaseComponent
{
  public $filename = '';

  public $columnsWithAliases = [
    'sl'                    => 'Sl.',
    'attribute_name'       => 'Attribute Name',
    'value'                 => 'Value',
    'value_details'         => 'Extra Value',
    'sequence'              => 'Sequence',
    'status'                => 'Status',
    'created_by'            => 'Created By',
    'created_at'            => 'Created Date',
    'updated_by'            => 'Updated By',
    'updated_at'            => 'Updated Date',
  ];

  public function __construct()
  {
    $this->sortColumn = 'attribute_name';
    $this->sortDirection = 'ASC';
  }

  public function getQuery($filtered = false)
  {
    $query = ProductAttributeValue::with('attribute')
      ->addSelect([
        'attribute_name' => ProductAttribute::select('name')
          ->whereColumn('id', 'product_attribute_values.attribute_id')
          ->limit(1)
      ])
      ->orderBy($this->sortColumn, $this->sortDirection);

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
    $attributeValues = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($attributeValues->currentPage() - 1) * $attributeValues->perPage() + 1;

    return view('livewire.product-manage.attribute-value-table', ['attributeValues' => $attributeValues, 'serialNumber' => $serialNumber]);
  }
}
