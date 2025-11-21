<script src={{ asset('/public/backend/assetss/js/vendor.min.js') }}></script>

<script src={{ asset('/public/backend/assetss/js/app.min.js') }}></script>
<script src="{{ asset('/public/common/js/custom_sweet_alert.js') }}"></script>
<script src="{{ asset('/public/backend/assetss/js/apexcharts.min.js') }}"></script>
<script>
  $(document).ready((function() {
    $(document).ajaxStart((function() {

      $("body").append(
        '\n      <div id="full-page-spinner" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">\n          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">\n              <div class="bouncing-loader">\n                  <div></div>\n                  <div></div>\n                  <div></div>\n              </div>\n          </div>\n      </div>\n  '
      )
    })).ajaxStop((function() {
      $("#full-page-spinner").remove()
    }))
  }));
</script>
@yield('page-scripts')
@stack('component-scripts')
