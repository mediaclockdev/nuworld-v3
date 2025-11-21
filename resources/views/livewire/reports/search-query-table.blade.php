<div>
    <div class="d-flex filter-small justify-content-end align-items-center mb-2">
        <div class="d-flex me-2">
            <div class="input-group input-group-text font-14 bg-white" id="reportrange" wire:ignore>
                <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                <span class=""></span>
            </div>
        </div>

        @include('livewire.includes.datatable-search')
    </div>

    @include('livewire.includes.datatable-pagecount')

    <div class="table-responsive">
        <table class="table table-centered mb-0">
            <thead>
                <tr>
                    <th>Sl.</th>
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'query',
                        'displayName' => 'Search Query',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'count',
                        'displayName' => 'Search Count',
                    ])
                    @include('livewire.includes.datatable-header-sort', [
                        'colName' => 'created_at',
                        'displayName' => 'Last Searched',
                    ])
                </tr>
            </thead>
            <tbody>
                @if (count($searchQueries) > 0)
                    @foreach ($searchQueries as $searchQuery)
                        <tr>
                            <td>{{ $serialNumber++ }}</td>
                            <td>{{ $searchQuery->query }}</td>
                            <td>{{ $searchQuery->count }}</td>
                            <td>{{ $searchQuery->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            <div role="alert" class="alert alert-danger text-center text-danger">No Search Queries Found</div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{ $searchQueries->links('vendor.livewire.bootstrap') }}
</div>
