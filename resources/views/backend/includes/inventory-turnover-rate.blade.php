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

    .date-range-picker {
      margin-bottom: 10px;
    }

    .no-data-message {
      text-align: center;
      color: #666;
      font-size: 16px;
      margin-top: 20px;
    }
  </style>
@endpush

<div class="card card-h-100 mb-3">
  <div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
      <h4 class="header-title">Inventory Turnover Rate</h4>
      <div class="dropdown ms-1">
        <div class="d-flex me-2">
          <div class="input-group input-group-text font-14 bg-white" id="stockLeftRange" wire:ignore>
            <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
            <span></span>
          </div>
        </div>
      </div>
      <div class="dropdown ms-1">
        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="mdi mdi-dots-vertical"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <a href="javascript:void(0);" class="dropdown-item" id="downloadPngStockLeft">Download PNG</a>
          <a href="javascript:void(0);" class="dropdown-item" id="downloadCsvStockLeft">Export to CSV</a>
        </div>
      </div>
    </div>
  </div>

  <div class="card-body d-flex flex-column justify-content-between">
    <div id="stock-left-chart" style="height: 500px;"></div>
    <div id="no-data-message" class="no-data-message" style="display: none;">No stock data available for the selected date range.</div>
  </div>
</div>

@push('component-scripts')
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chartElement = document.getElementById("stock-left-chart");
      const noDataMessage = document.getElementById("no-data-message");
      let chart = null;

      // Fetch chart data
      let fetchChartData = (start, end) => {
        fetch(`{{ route('admin.inventory.turnover.rate') }}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            start_date: start,
            end_date: end
          })
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(data => {
          if (data.labels.length === 0) {
            chartElement.style.display = 'none';
            noDataMessage.style.display = 'block';
            noDataMessage.textContent = data.message || 'No stock data available.';
            if (chart) chart.destroy();
            return;
          }

          chartElement.style.display = 'block';
          noDataMessage.style.display = 'none';

          const options = {
            chart: {
              type: 'pie',
              height: 500,
              toolbar: { show: false },
              zoom: { enabled: false }
            },
            series: data.series,
            labels: data.labels,
            colors: ['#4B0082', '#8A2BE2', '#9932CC', '#BA55D3', '#DA70D6', '#EE82EE', '#DDA0DD', '#C71585'],
            legend: {
              position: 'bottom',
              horizontalAlign: 'center',
              formatter: function(label, opts) {
                const count = opts.w.globals.series[opts.seriesIndex];
                return `${label}: ${count} product${count === 1 ? '' : 's'}`;
              }
            },
            tooltip: {
              y: {
                formatter: function(val) {
                  return `${val} product${val === 1 ? '' : 's'}`;
                }
              }
            },
            dataLabels: {
              enabled: true,
              formatter: function(val, opts) {
                const count = opts.w.globals.series[opts.seriesIndex];
                return `${val.toFixed(2)}% (${count} product${count === 1 ? '' : 's'})`;
              }
            }
          };

          if (chart) chart.destroy();
          chart = new ApexCharts(chartElement, options);
          chart.render();
        })
        .catch(error => {
          console.error('Error fetching chart data:', error);
          chartElement.style.display = 'none';
          noDataMessage.style.display = 'block';
          noDataMessage.textContent = 'Error loading data. Please try again.';
        });
      };

      const startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
      const endOfMonth = moment().endOf('month').format('YYYY-MM-DD');

      const picker = $('#stockLeftRange').daterangepicker({
        startDate: startOfMonth,
        endDate: endOfMonth,
        autoUpdateInput: false,
        opens: 'left',
        locale: { format: 'YYYY-MM-DD' },
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')]
        }
      }, (start, end) => {
        $('#stockLeftRange span').html(`${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`);
        fetchChartData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
      });

      $('#stockLeftRange span').html(
        `${moment(startOfMonth).format('MMMM D, YYYY')} - ${moment(endOfMonth).format('MMMM D, YYYY')}`);
      fetchChartData(startOfMonth, endOfMonth);

      // Download PNG
      document.getElementById('downloadPngStockLeft').addEventListener('click', () => {
        if (chart) {
          chart.dataURI().then(({ imgURI }) => {
            const link = document.createElement("a");
            const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
            link.href = imgURI;
            link.download = `stock-left-${timestamp}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
        }
      });

      // Download CSV
      document.getElementById('downloadCsvStockLeft').addEventListener('click', () => {
        if (chart) {
          const labels = chart.w.config.labels;
          const series = chart.w.config.series;
          const csvContent = [
            "Stock Level,Product Count",
            ...labels.map((label, index) => `${label},${series[index]}`)
          ].join("\n");
          const blob = new Blob([csvContent], { type: 'text/csv' });
          const link = document.createElement("a");
          const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
          link.href = URL.createObjectURL(blob);
          link.download = `stock-left-${timestamp}.csv`;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
      });
    });
  </script>
@endpush
