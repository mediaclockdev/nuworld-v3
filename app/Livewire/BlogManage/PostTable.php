<?php

namespace App\Livewire\BlogManage;

use App\Livewire\BaseComponent;
use App\Models\Post;
use Livewire\Component;

class PostTable extends BaseComponent
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
    $query = Post::query();

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
    $posts = $this->getQuery(true)->paginate($this->perPage);
    $serialNumber = ($posts->currentPage() - 1) * $posts->perPage() + 1;
    return view('livewire.blog-manage.post-table', ['posts' => $posts, 'serialNumber' => $serialNumber]);
  }
}
