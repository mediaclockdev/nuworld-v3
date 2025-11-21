@extends('backend.layouts.guest')
@section('page-styles')
  <style>
    .password-error-container {
      display: block;
    }

    .timer {
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
    }
  </style>
@endsection
@section('content')
  <div class="text-left">
    <h3 class="text-primary pb-0 fw-medium mt-0">OTP</h3>
    <p class="text-dark mb-3">OTP sent to your registered email.</p>
  </div>
  <form method="POST">
    <div class="mb-5 d-flex justify-content-around align-items-center otp-grid">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-0 otp-input only-numbers"
        type="text" name="otp[0]" id="otp1" inputmode="numeric">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-2 otp-input only-numbers"
        type="text" name="otp[1]" id="otp2" inputmode="numeric">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-2 otp-input only-numbers"
        type="text" name="otp[2]" id="otp3" inputmode="numeric">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-2 otp-input only-numbers"
        type="text" name="otp[3]" id="otp4" inputmode="numeric">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-2 otp-input only-numbers"
        type="text" name="otp[4]" id="otp5" inputmode="numeric">
      <input class="form-control form-control-lg rounded-0 text-dark w-25 text-center me-0 ms-2 otp-input only-numbers"
        type="text" name="otp[5]" id="otp6" inputmode="numeric">
      <i class="error" id="otpError"></i>
    </div>

    <div class="mb-4 d-flex justify-content-between align-items-center">
      <div class="leftarea">
        <button class="btn btn-light" type="button" id="resendBtn" disabled onclick="resendOtp()">Resend OTP</button>
      </div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </div>
    <p class="text-black m-0"><a href="{{ route('admin.login') }}"
        class="text-black back-btn d-flex align-items-center"><i class="uil-arrow-circle-left me-1 large"></i> Back to
        Login</a></p>
  </form>
@endsection
@section('page-scripts')
  <script src={{ asset('/public/common/js/custom_input.js?v=1' . time()) }}></script>
  <script>
    $(document).ready(function() {
      clearPreviousInput();
      const $resendBtn = $('#resendBtn');
      const countdownDuration = 20; // in seconds
      startCountdown(countdownDuration);

      // When pasting into the first OTP input
      $('#otp1').on('paste', function(e) {
        let pasteData = e.originalEvent.clipboardData.getData('text');
        if (pasteData.length === 6) {
          $('#otp1').val(pasteData[0]);
          $('#otp2').val(pasteData[1]);
          $('#otp3').val(pasteData[2]);
          $('#otp4').val(pasteData[3]);
          $('#otp5').val(pasteData[4]);
          $('#otp6').val(pasteData[5]);
        }
        e.preventDefault();
      });

      // Handle manual typing and ensure only one digit is entered
      $('.otp-input').on('input', function() {
        let $this = $(this);
        let value = $this.val();

        if (value.length > 1) {
          $this.val(value.slice(0, 1));
        } else if (value.length === 1) {
          $this.next('.otp-input').focus();
        }
      });
      // Handle backspace to move to previous field
      $('.otp-input').on('keyup', function(e) {
        if (e.key === 'Backspace' && !$(this).val()) {
          let prevInput = $(this).prev('.otp-input');
          if (prevInput.length) {
            prevInput.focus();
          }
        }
      });

      //Handle Enter key to Submit
      $('.otp-input').on('keydown', function(e) {
        if (e.key === 'Enter') {
          $('form').submit();
        }
      })

      function startCountdown(duration) {
        let timer = duration,
          minutes, seconds;
        const interval = setInterval(function() {
          minutes = parseInt(timer / 60, 10);
          seconds = parseInt(timer % 60, 10);

          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;

          $resendBtn.text('Resend OTP in ' + seconds + 's');
          $resendBtn.prop('disabled', true);

          if (--timer < 0) {
            clearInterval(interval);
            $resendBtn.text('Resend OTP');
            $resendBtn.prop('disabled', false); // Enable resend button
          }
        }, 1000);
      }

      $('#resendBtn').click(function() {
        resendOtp();
        clearPreviousInput();
      });

      function clearPreviousInput() {
        $('#otp1').val('');
        $('#otp2').val('');
        $('#otp3').val('');
        $('#otp4').val('');
        $('#otp5').val('');
        $('#otp6').val('');
        $('#otpError').text('');
      }

      function resendOtp() {
        $resendBtn.text('Resending OTP...');
        $.ajax({
          url: "{{ route('admin.resendOtp') }}",
          method: "POST",
          data: {
            _token: "{{ csrf_token() }}",
            type: 'login'
          },
          success: function(response) {
            if (response.success) {
              $('#otpSuccess').text(response.message);
            }
            startCountdown(countdownDuration);
          },
          error: function(error) {
            console.log(error);
            $('#otpError').text('Error: ' + error);
            $resendBtn.text('Resend OTP');
          }
        });
      }
    });

    $('form').submit(function(e) {
      e.preventDefault();
      let otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + $('#otp4').val() + $('#otp5').val() + $(
        '#otp6').val();
      if (otp.length !== 6) {
        $('#otpError').text('{{ __('validation.required', ['attribute' => 'OTP']) }}');
        return;
      }
      $('#otpError').text('');
      $('button[type="submit"]').prop('disabled', true).text('Validating...');
      $.ajax({
        url: "{{ route('admin.validateLoginOtp') }}",
        method: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          email_otp: otp
        },
        success: function(response) {
          if (response.success) {
            $('#otpSuccess').text(response.message);
            window.location.href = "{{ route('admin.dashboard') }}";
          } else {
            $('#otpError').text(response.message);
            $('button[type="submit"]').prop('disabled', false).text('Submit');
          }
        },
        error: function(error) {
          console.log(error);
          $('#otpError').text('Error: ' + error);
        }
      });
    });
  </script>
@endsection
