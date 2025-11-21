@props(['filter' => true, 'link' => false])
<div class="card card-h-100">
  <div class="d-flex card-header justify-content-between align-items-center">
    <h4 class="header-title">Sales Trends
      @if ($link)
        <a href="{{ route('admin.sales-analytics') }}" title="Sales Analytics"><i class="ri-arrow-right-up-line"></i></a>
      @endif
    </h4>
    @if ($filter)
      <div class="dropdown ms-1">
        <div class="d-flex me-2">
          <div class="input-group input-group-text font-14 bg-white" id="topSellingProductsRange" wire:ignore>
            <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
            <span></span>
          </div>
        </div>
      </div>
    @endif
    <div class="dropdown ms-1">
      <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="mdi mdi-dots-vertical"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-end">
        <a href="javascript:void(0);" class="dropdown-item" id="downloadPngTop">
          Download PNG
        </a>
        <a href="javascript:void(0);" class="dropdown-item" id="downloadCsvTop">
          Download CSV
        </a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="text-center">
      <div id="topSellingProductsChart"></div>
    </div>
  </div>
</div>
@push('component-scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const chartElement = document.getElementById("topSellingProductsChart");
      let chart = null;
      document.getElementById('downloadPngTop').addEventListener('click', () => {
        if (chart) {
          chart.dataURI().then(({
            imgURI
          }) => {
            const link = document.createElement("a");
            const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
            link.href = imgURI;
            link.download = `top-selling-products-${timestamp}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
        }
      });

      document.getElementById('downloadCsvTop').addEventListener('click', () => {
        if (chart) {
          chart.exportToCSV();
        }
      });

      const fetchChartData = (start, end) => {
        fetch('{{ route('admin.reports.top-selling-products-json') }}', {
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
          .then(r => r.ok ? r.json() : Promise.reject(r))
          .then(options => {
            if (options.dataLabels?.enabled) {
              options.dataLabels.formatter = val => val?.toLocaleString() || '';
            }

            if (options.xaxis?.categories) {
              options.xaxis.categories = options.xaxis.categories.map(name =>
                name.length > 40 ? name.slice(0, 37) + '...' : name
              );
            }

            options.tooltip = {
              y: {
                formatter: (val) => Number(val).toLocaleString(undefined, {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 2
                })
              }
            };
            options.plotOptions = {
              bar: {
                horizontal: true,
                barHeight: '70%',
                distributed: true
              }
            };

            if (chart) chart.destroy();
            chart = new ApexCharts(chartElement, options);
            chart.render();
          })
          .catch(err => console.error('Chart load failed', err));
      };

      const startOfYear = moment().startOf('year').format('YYYY-MM-DD');
      const today = moment().format('YYYY-MM-DD');

      @if ($filter)
        const picker = $('#topSellingProductsRange').daterangepicker({
          startDate: startOfYear,
          endDate: today,
          opens: 'left',
          locale: {
            format: 'YYYY-MM-DD'
          },
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
              'month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')]
          }
        }, (start, end) => {
          $('#topSellingProductsRange span').html(
            `${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`);
          fetchChartData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        $('#topSellingProductsRange span').html(
          `${moment(startOfYear).format('MMMM D, YYYY')} - ${moment(today).format('MMMM D, YYYY')}`);
        fetchChartData(startOfYear, today);
      @else
        fetchChartData(startOfYear, today);
      @endif
    });
  </script>
@endpush
