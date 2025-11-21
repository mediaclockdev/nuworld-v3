@push('component-styles')
  <style>
    .apexcharts-legend {
      flex-wrap: wrap;
    }

    .apexcharts-legend-text {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 180px;
      display: inline-block;
    }
  </style>
@endpush

<div class="card {{ $width ? 'card-h-' . $width : '' }} mb-3">
  <div class="d-flex card-header justify-content-between align-items-center">
    <h4 class="header-title">Inventory Overview</h4>
    <div class="dropdown ms-1">
      <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <a href="javascript:void(0);" class="dropdown-item" id="downloadPngInventoryAnalytics">Download PNG</a>
        <a href="javascript:void(0);" class="dropdown-item" id="downloadCsvInventoryAnalytics">Export to CSV</a>
      </div>
    </div>
  </div>

  <div class="card-body d-flex flex-column justify-content-between">
    <div id="inventory-pie-chart" style="height: 500px;"></div>
  </div>
</div>

@push('component-scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chartElement = document.getElementById("inventory-pie-chart");
      let chart = null;

      // Download PNG functionality
      document.getElementById('downloadPngInventoryAnalytics').addEventListener('click', () => {
        if (chart) {
          chart.dataURI().then(({ imgURI }) => {
            const link = document.createElement("a");
            const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
            link.href = imgURI;
            link.download = `inventory-analytics-${timestamp}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
        }
      });

      // Download CSV functionality
      document.getElementById('downloadCsvInventoryAnalytics').addEventListener('click', () => {
        if (chart) {
          const labels = chart.w.config.labels;
          const series = chart.w.config.series;
          const counts = chart.w.config.counts || []; // Assuming counts are stored in config
          const csvContent = [
            "Label,Percentage,Count",
            ...labels.map((label, index) => `${label},${series[index]}%,${counts[index] || 0}`)
          ].join("\n");
          const blob = new Blob([csvContent], { type: 'text/csv' });
          const link = document.createElement("a");
          const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
          link.href = URL.createObjectURL(blob);
          link.download = `inventory-analytics-${timestamp}.csv`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
      });

      const fetchChartData = () => {
        fetch(`{{ route('admin.inventory.bar.chart') }}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
          const options = {
            chart: {
              type: 'pie',
              height: 500,
              toolbar: {
                show: false
              },
              zoom: {
                enabled: false
              }
            },
            title: {
              // text: 'Stock Levels by Product/Category',
              align: 'center'
            },
            labels: data.labels,
            series: data.series,
            counts: data.counts, // Store counts in config for CSV export
            legend: {
              position: 'bottom',
              horizontalAlign: 'center',
              formatter: function(label, opts) {
                const value = opts.w.globals.series[opts.seriesIndex];
                const count = data.counts[opts.seriesIndex] || 0;
                return `${label}: ${value}% (${count} units)`;
              }
            },
            tooltip: {
              y: {
                formatter: function(val, opts) {
                  const count = data.counts[opts.dataPointIndex] || 0;
                  return `${Number(val).toLocaleString(undefined, {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                  })}% (${count} units)`;
                }
              }
            },
            dataLabels: {
              enabled: true,
              formatter: function(val, opts) {
                const count = data.counts[opts.seriesIndex] || 0;
                return `${Number(val).toLocaleString(undefined, {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
                })}% (${count} units)`;
              }
            }
          };

          if (chart) chart.destroy();
          chart = new ApexCharts(chartElement, options);
          chart.render();
        });
      };

      fetchChartData();
    });
  </script>
@endpush
