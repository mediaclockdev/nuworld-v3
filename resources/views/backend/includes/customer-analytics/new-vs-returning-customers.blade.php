@props(['filter' => true, 'link' => false])
<div class="card card-h-100">
  <div class="d-flex card-header justify-content-between align-items-center">
    <h4 class="header-title">New & Returning Customers This week
      @if ($link)
        <a href="{{ route('admin.customer-analytics') }}" title="Sales Analytics"><i class="ri-arrow-right-up-line"></i></a>
      @endif
    </h4>

    @if ($filter)
      <div class="dropdown ms-1">
        <div class="d-flex me-2">
          <div class="input-group input-group-text font-14 bg-white" id="newAndReturningCustomerStatusRange" wire:ignore>
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
        <a href="javascript:void(0);" class="dropdown-item" id="downloadPngCustomerAnalytics">
          Download PNG
        </a>
        <a href="javascript:void(0);" class="dropdown-item" id="downloadCsvCustomerAnalytics">
          Download CSV
        </a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="newVsReturningChart" class="h-80"></div>
  </div>
</div>

@push('component-scripts')
<script>

let allowZoomInOut = @json($zoomInOut); // Default is true if not passed
document.addEventListener('DOMContentLoaded', () => {
    const chartElement = document.getElementById("newVsReturningChart");
      let chart = null;

      document.getElementById('downloadPngCustomerAnalytics').addEventListener('click', () => {
        if (chart) {
          chart.dataURI().then(({
            imgURI
          }) => {
            const link = document.createElement("a");
            const timestamp = new Date().toLocaleString('sv-SE').replace(/[: ]/g, '-');
            link.href = imgURI;
            link.download = `customer-analytics-status-${timestamp}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
          });
        }
      });

      document.getElementById('downloadCsvCustomerAnalytics').addEventListener('click', () => {
        if (chart) {
          chart.exportToCSV();
        }
      });

    const fetchChartData = (start, end) => {
      fetch('{{ route('admin.reports.new-vs-returning-customers') }}', {
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
          // Inject zoom config
          options.chart = {
            ...options.chart,
            zoom: {
              enabled: allowZoomInOut
            }
          };

          // Optional: format dataLabels and yAxis labels
          if (options.dataLabels)
            options.dataLabels.formatter = val => val?.toLocaleString() || '';

          if (options.yaxis)
            (Array.isArray(options.yaxis) ? options.yaxis : [options.yaxis]).forEach(axis => {
              if (axis.labels) axis.labels.formatter = val => val?.toLocaleString() || '';
            });

          // Tooltip
          options.tooltip = {
            y: {
              formatter: val => Number(val).toLocaleString(undefined, {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
              })
            }
          };
          // Render chart
          if (chart) chart.destroy();
          chart = new ApexCharts(chartElement, options);
          chart.render();
        });
    };


    //const startOfWeek = moment().subtract(1, 'week').startOf('week').format('YYYY-MM-DD'); // Previous Week
    /* const startOfWeek = moment().startOf('isoWeek').format('YYYY-MM-DD 00:00:00'); Week from Monday to Sunday
    const endOfWeek = moment().endOf('isoWeek').format('YYYY-MM-DD 23:59:59'); */

    // Week from Sunday to Saturday
    /* const startOfWeek = moment().startOf('week').format('YYYY-MM-DD 00:00:00');
    const endOfWeek = moment().endOf('week').format('YYYY-MM-DD 23:59:59');

    @if ($filter)
      const picker = $('#newAndReturningCustomerStatusRange').daterangepicker({
        startDate: startOfWeek,
        endDate: endOfWeek,
        opens: 'left',
        locale: {
          format: 'YYYY-MM-DD 00:00:00'
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
        $('#newAndReturningCustomerStatusRange span').html(`${start.format('MMMM D, YYYY 00:00:00')} - ${end.format('MMMM D, YYYY 23:59:59')}`);
        fetchChartData(start.format('YYYY-MM-DD 00:00:00'), end.format('YYYY-MM-DD 23:59:59'));
      });

      $('#newAndReturningCustomerStatusRange span').html(
        `${moment(startOfWeek).format('MMMM D, YYYY 00:00:00')} - ${moment(endOfWeek).format('MMMM D, YYYY 23:59:59')}`);
      fetchChartData(startOfWeek, endOfWeek);
    @else
      fetchChartData(startOfWeek, endOfWeek);
    @endif */

    const startOfWeek = moment().startOf('week').format('YYYY-MM-DD');
    const endOfWeek = moment().endOf('week').format('YYYY-MM-DD');

    @if ($filter)
      const picker = $('#newAndReturningCustomerStatusRange').daterangepicker({
        startDate: startOfWeek,
        endDate: endOfWeek,
        opens: 'left',
        locale: {
          format: 'MMMM D, YYYY' // Changed to exclude time
        },
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
        $('#newAndReturningCustomerStatusRange span').html(`${start.format('MMMM D, YYYY')} - ${end.format('MMMM D, YYYY')}`); // Exclude time
        fetchChartData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD')); // Pass dates without time
      });

      $('#newAndReturningCustomerStatusRange span').html(
        `${moment(startOfWeek).format('MMMM D, YYYY')} - ${moment(endOfWeek).format('MMMM D, YYYY')}`); // Exclude time
      fetchChartData(startOfWeek, endOfWeek);
    @else
      fetchChartData(startOfWeek, endOfWeek);
    @endif
    });
</script>
@endpush
