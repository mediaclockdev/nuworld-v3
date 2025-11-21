<div class="card {{ $width ? 'card-h-' . $width : '' }}">
  <div class="d-flex card-header justify-content-between align-items-center">
    <h4 class="header-title">User Demographics</h4>
    <div class="dropdown ms-1">
      <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown">
        <i class="mdi mdi-dots-vertical"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('admin.user.demography.export') }}" class="dropdown-item">Export to CSV</a>
      </div>
    </div>
  </div>

  <div class="card-body d-flex flex-column justify-content-between">
    <div id="user-demography-chart" style="height: 350px;"></div>
  </div>
</div>

@push('component-scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetch(`{{ route('admin.user.demography.chart') }}`)
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
          const options = {
            chart: {
              type: 'donut',
              height: 350
            },
            labels: data.labels, // e.g. ['India', 'USA', 'California', 'Delhi', '123456']
            series: data.series, // e.g. [50, 30, 25, 10, 5]
            legend: {
              position: 'bottom'
            },
            tooltip: {
              y: {
                formatter: val => `${val} users`
              }
            }
          };

          const chart = new ApexCharts(document.querySelector("#user-demography-chart"), options);
          chart.render();
        })
        .catch(console.error);
    });
  </script>
@endpush
