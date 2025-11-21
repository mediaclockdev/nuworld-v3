<?php

namespace App\Livewire\BlogManage;

use App\Livewire\BaseComponent;
use App\Models\Blog;
use Livewire\Component;

class BlogTable extends BaseComponent
{

  public $filename = '';
  public $orderStatus = ['InActive', 'Active'];
  public $statusColor = ['bg-danger', 'bg-success'];

  public $columnsWithAliases = [
    'sl'                  => 'Sl.',
    'title'               => 'Title',
    'status'              => 'Status',
    'created_at'          => 'Added On',
  ];

  public function __construct()
  {
    $this->sortColumn = 'title';
    $this->sortDirection = 'Desc';
  }

  public function getQuery($filtered = false)
  {
    $query = Blog::query();

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
    $blogs = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($blogs->currentPage() - 1) * $blogs->perPage() + 1;
    return view('livewire.blog-manage.blog-table', ['blogs' => $blogs, 'serialNumber' => $serialNumber]);
  }
}
