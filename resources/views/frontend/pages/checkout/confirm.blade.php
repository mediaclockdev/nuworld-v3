@extends('frontend.layouts.app')
@section('title', @$title)

@section('content')
  <main class="min-vh-50 d-flex flex-column justify-content-center align-items-center py-5">
    <div class="text-center mb-4">
      <h3 class="fw-bold">Hello {{ userNameById('', user()->id) ?? 'Guest' }}</h3>
      {{-- <p class="text-muted">{{ $order_number }}</p> --}}
    </div>

    <form id="confirm-form" class="w-100" style="max-width: 400px;">
      @csrf
      <div class="form-group mb-3 text-center">
        <h5 for="captcha" class="form-label">Enter the CAPTCHA to Confirm COD</h5>
        <div class="captcha-wrapper d-flex justify-content-center align-items-center gap-2 mb-2">
          <img src="{{ captcha_src('flat') }}" class="captcha-image border rounded" alt="captcha" height="40">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="refresh-captcha" title="Refresh CAPTCHA">
            <i class="ri-refresh-line"></i>
          </button>
        </div>
        <input type="text" name="captcha" id="captcha" class="form-control text-center" placeholder="Enter CAPTCHA">
        @error('captcha')
          <span class="text-danger small d-block mt-2">{{ $message }}</span>
        @enderror
      </div>
      <div class="text-center">
        <button type="submit" id="confirmBtn" class="btn btn-dark px-4">Submit</button>
      </div>
    </form>
  </main>

@endsection

@push('component-scripts')
  <script>
  $(document).ready(function() {
      const $refreshBtn = $('#refresh-captcha');
      const $captchaImg = $('.captcha-image');
      const $form = $('#confirm-form');

      $refreshBtn.on('click', function() {
        $refreshBtn.prop('disabled', true).addClass('loading');

        $.ajax({
          url: '{{ route('captcha.refresh') }}',
          type: 'GET',
          dataType: 'json',
          timeout: 5000,
          success: function(response) {
            if (response.captcha) {
              $captchaImg.attr('src', response.captcha + '?' + Date.now());
            } else {
              iziNotify("Oops!", 'Unexpected response. Please try again.', "warning");
            }
          },
          error: function(xhr) {
            if (xhr.status === 429) {
              iziNotify("Oops!", 'Too many attempts. Please wait a moment before refreshing.', "error");
            } else {
              iziNotify("Oops!", 'Failed to refresh CAPTCHA. Please check your connection.', "error");
            }
            console.error('CAPTCHA refresh error:', xhr);
          },
          complete: function() {
            $refreshBtn.prop('disabled', false).removeClass('loading');
          }
        });
      });

    $form.on('submit', function(e) {
        e.preventDefault();
        if (!$('#captcha').val()) {
          iziNotify("Oops!", 'Please enter the CAPTCHA.', "error");
          return;
        }
        $('#confirmBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');
        $.ajax({
          url: '{{ route('checkout.confirm') }}',
          method: 'POST',
          data: {
              '_token': '{{ csrf_token() }}',
              'captcha': $('#captcha').val(),
              'order_number': '{{ $order_number }}'
          },
          success: function(response) {
            if (response.success) {
                  $('#confirmBtn').prop('disabled', true).html('Submit');
                  iziNotify("", response.message, "success");
                  setTimeout(() => window.location.href = response.redirect + '?from=confirmation', 1000);
              } else {
                  $('#confirmBtn').prop('disabled', false).html('Submit');
                  iziNotify("Oops!", response.message, "error");
                  $('#captcha').val('');
                  $refreshBtn.trigger('click');
              }
          },
          error: function(error) {
              $('#confirmBtn').prop('disabled', false).html('Submit');
              iziNotify("Error!", error.responseJSON?.message, "error");
              $('#captcha').val('');
              $refreshBtn.trigger('click');
          }
      });
      });
    });
  </script>
@endpush
