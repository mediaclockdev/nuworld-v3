@push('component-styles')
  <style>
    #inventory-donut-chart {
      padding-top: 10px;
      padding-bottom: 10px;
    }

    #inventory-stats>div {
      font-size: 14px;
    }

    #inventory-stats span:last-child {
      white-space: nowrap;
    }
  </style>
@endpush

<div class="col-xl-4 col-lg-4">
  <div class="card card-h-100">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title">Orders Overview</h4>
      <div class="dropdown ms-1">
        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="mdi mdi-dots-vertical"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">

          <a href="{{ route('admin.inventory-export-csv') }}" class="dropdown-item">Export to CSV</a>
        </div>
      </div>
    </div>

    <div class="card-body d-flex flex-column justify-content-start">
      <div class="text-center mb-3">
        <div id="inventory-donut-chart" style="height: 200px; margin: 0 auto;"></div>
        <h2 id="inventory-total" class="fw-bold mt-3">--</h2>
        <p class="text-muted mb-2">
          Total Products <span class="text-success" id="inventory-new">+-- New</span><br>
          <span class="text-info" id="inventory-month-label">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
        </p>
      </div>

      <div class="d-flex flex-column gap-2" id="inventory-stats">
        <div class="d-flex align-items-center">
          <span class="badge rounded-circle me-2" style="background-color: #0acf97;">&nbsp;</span>
          <span class="text-nowrap">Completed - <span id="completed-count">--</span> Items</span>
        </div>
        <div class="d-flex align-items-center">
          <span class="badge rounded-circle me-2" style="background-color: #727cf5;">&nbsp;</span>
          <span class="text-nowrap">In Transit - <span id="in-transit-count">--</span> Items</span>
        </div>

        <div class="d-flex align-items-center">
          <span class="badge rounded-circle me-2" style="background-color: #ffbc00;">&nbsp;</span>
          <span class="text-nowrap">In Cart - <span id="in-cart-count">--</span> Items</span>
        </div>

        <div class="d-flex align-items-center">
          <span class="badge rounded-circle me-2" style="background-color: #fa5c7c">&nbsp;</span>
          <span class="text-nowrap">Cancelled - <span id="cancelled-count">--</span> Items</span>
        </div>
      </div>

      <div class="mt-3 d-flex justify-content-end">
        <a class="btn btn-outline-light btn-sm" href="{{ route('admin.orders') }}">View All</a>
      </div>
    </div>
  </div>
</div>



@push('component-scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      fetch("{{ route('admin.inventory-overview-json') }}")
        .then(res => res.json())
        .then(data => {
          // Update summary text
          document.getElementById('inventory-total').textContent = data.total;
          document.getElementById('inventory-new').textContent = `+${data.new} New`;

          // Update counts
          document.getElementById('completed-count').textContent = data.counts['Completed'] || 0;
          document.getElementById('in-transit-count').textContent = data.counts['In Transit'] || 0;
          document.getElementById('in-cart-count').textContent = data.counts['In Cart'] || 0;
          document.getElementById('cancelled-count').textContent = data.counts['Cancelled'] || 0;

          // Safe checks
          const series = Array.isArray(data.series) ? data.series : [];
          const labels = Array.isArray(data.labels) ? data.labels : [];

          const isAllZero = series.length === 0 || series.every(val => val === 0);
          const now = new Date();
          const monthYear = now.toLocaleString('default', {
            month: 'long',
            year: 'numeric'
          }); // e.g., "July 2025"
          const options = {
            chart: {
              type: 'donut',
              height: 200
            },
            labels: isAllZero ? ['No Data'] : labels,
            series: isAllZero ? [0] : series,
            colors: isAllZero ? ['#e0e0e0'] : ['#0acf97', '#727cf5', '#ffbc00', '#fa5c7c'],
            legend: {
              show: false
            },
            dataLabels: {
              enabled: false
            },
            plotOptions: {
              pie: {
                donut: {
                  size: '75%',
                  labels: {
                    show: true,
                    name: {
                      show: true,
                      fontSize: '12px',
                      offsetY: -10,
                      formatter: function() {
                        return 'Month';
                      }
                    },
                    value: {
                      show: true,
                      fontSize: '16px',
                      offsetY: 10,
                      formatter: function() {
                        return monthYear;
                      }
                    }
                  }
                }
              }
            }
          };

          const chart = new ApexCharts(document.querySelector("#inventory-donut-chart"), options);
          chart.render();
        })
        .catch(err => console.error('Inventory data error:', err));
    });
  </script>
@endpush
