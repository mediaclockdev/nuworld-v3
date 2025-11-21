@extends('backend.layouts.guest')
@section('page-styles')
  <style>
    .password-error-container {
      display: block;
      /* font-size: 0.9rem !important; */
    }
  </style>
@endsection
@section('content')
  <div class="text-left">
    <h3 class="text-primary pb-0 fw-medium mt-0">Welcome!</h3>
    <p class="text-dark mb-3">Admin Portal Login.
    </p>
  </div>
  <form id="loginForm" autocomplete="off">
    <div class="mb-4 required">
      <label for="emailaddress" class="form-label">Email Address</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
        value="{{ session('ecomm_email') ?? '' }}">
      <div class="invalid-feedback email-error-container"></div>
    </div>

    <div class="mb-4 required">
      <label for="password" class="form-label">Password</label>
      <div class="input-group input-group-merge">
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
        <div class="input-group-text" data-password="false">
          <span class="password-eye"></span>
        </div>
      </div>
      <div class="invalid-feedback password-error-container"></div>
      <a href={{ route('admin.forgot_password') }} class="link-primary mt-1 float-end"><small>Forgot
          your
          password?</small></a>
    </div>
    <span class="error" id="auth_error"></span>

    <div class="mb-0 mt-4 pt-2 d-flex justify-content-between align-items-center">
      <div class="form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input" checked />
        <label class="form-check-label" for="checkbox-signin">Remember me</label>
        <div class="invalid-feedback remember-error-container"></div>
      </div>

      <button class="btn btn-primary" type="submit" id="loginBtn"> Sign In </button>

    </div>
  </form>
@endsection
@section('page-scripts')
  <script src={{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}></script>
  <script>
    $(document).ready(function() {
      $('#loginForm').validate({
        rules: {
          email: {
            required: true,
            email: true
          },
          password: {
            required: true,
          }
        },
        messages: {
          email: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email Format']) }}"
          },
          password: {
            required: "{{ __('validation.required', ['attribute' => 'Password']) }}",
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          const errorContainer = element.closest('.mb-4').find(
            `.${element.attr('id')}-error-container`); // For Passwords
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback
          }
        },
        highlight: function(element, errorClass, validClass) {
          $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element, errorClass, validClass) {
          $(element).removeClass('is-invalid').removeClass('is-valid');
        },
        submitHandler: function(form) {
          $('#auth_error').text('');
          $('#loginBtn').prop('disabled', true).text('Please wait...');
          $.ajax({
            url: "{{ route('admin.login') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              email: $('#email').val(),
              password: $('#password').val(),
              remember: $('#remember').is(':checked')
            },
            success: function(response) {
              if (response.success) {
                window.location.href = "{{ route('admin.login_otp') }}";
              } else {
                $('#auth_error').text(response.message);
              }
            },
            error: function(error) {
              if (error.status === 419)
                $('#auth_error').text('Session expired. Please refresh the page or log in again.');
              else
                $('#auth_error').text(error.responseJSON.message);
            }
          });
          $('#loginBtn').prop('disabled', false).text('Sign In');
        }
      });
    });
  </script>
@endsection
