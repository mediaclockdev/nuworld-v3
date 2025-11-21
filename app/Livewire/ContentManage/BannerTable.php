<?php

namespace App\Livewire\ContentManage;

use App\Livewire\BaseComponent;
use App\Models\Banner;
use App\Models\ValueListMaster;
use Vinkla\Hashids\Facades\Hashids;

class BannerTable extends BaseComponent
{
  public $filename = '';
  public $position;

  public $columnsWithAliases = [
    'sl'              => 'Sl.',
    'title'           => 'Title',
    'position_name'   => 'Position',
    'sequence'        => 'Sequence',
    'hyper_link'      => 'Hyperlink',
    'status'          => 'Status',
    'created_by'      => 'Created By',
    'created_at'      => 'Created Date',
    'updated_by'      => 'Updated By',
    'updated_at'      => 'Updated Date',
  ];

  public function __construct()
  {
    $this->listeners = array_merge($this->listeners, [
      'positionChangedComponent' => 'positionChanged',
    ]);

    $this->sortColumn = 'title';
    $this->sortDirection = 'ASC';
  }

  public function positionChanged($position)
  {
    $this->position = $position ? Hashids::decode($position)[0] : null;
  }

  public function getQuery($filtered = false)
  {
    $query = Banner::with('positionValue')
      ->select('banners.*', 'value_lists.name as position_name')
      ->join('value_lists', 'banners.position', '=', 'value_lists.id')
      ->orderBy($this->sortColumn, $this->sortDirection);

    if ($this->position)
      $query->where('position', $this->position);

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
    $bannerPostitions = ValueListMaster::getValueList('BANNER-POSITION');

    $Banners = $this->getQuery(true)->paginate($this->perPage);

    $serialNumber = ($Banners->currentPage() - 1) * $Banners->perPage() + 1;

    return view('livewire.content-manage.banner-table', ['banners' => $Banners, 'serialNumber' => $serialNumber, 'bannerPostitions' => $bannerPostitions]);
  }
}
