<th class="" style="cursor: pointer;" wire:click="sortByCol('{{ $colName }}')">
  <div class="d-flex align-items-center">
    {{ $displayName }}
    @if ($sortColumn != $colName)
      <i class="ri-arrow-up-down-line ms-1" style="font-size: 15px; color: lightgray"></i>
    @elseif($sortDirection == 'ASC')
      <i class="ri-arrow-up-line ms-1" style="font-size: 15px"></i>
    @else
      <i class="ri-arrow-down-line ms-1" style="font-size: 15px"></i>
    @endif
  </div>
</th>
