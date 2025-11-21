@extends('backend.layouts.guest')
@section('page-styles')
@endsection
@section('content')
  <div class="text-left">
    <h3 class="text-primary pb-0 fw-medium mt-0">Reset Your Password</h3>
    <p class="text-dark mb-4"> Strong Password must be at least 8 characters long, with at least 1 number, 1 capital
      letter, 1 small letter, and 1 special character.</p>
  </div>

  <form id="resetPasswordForm">
    <x-password-strength />
    <div class="mb-4 required">
      <label for="confirm_password" class="form-label">Confirm New Password</label>
      <div class="input-group input-group-merge">
        <input type="password" name="confirm_password" id="confirm_password" class="form-control"
          placeholder="Re-enter the new password">
        <div class="input-group-text" data-password="false">
          <span class="password-eye"></span>
        </div>
      </div>
      <div class="confirm_password-error-container"></div>
    </div>
    <div class="mb-0 d-flex justify-content-between align-items-center">
      <p class="text-black m-0"><a href="{{ route('admin.login') }}"
          class="text-black back-btn d-flex align-items-center"><i class="uil-arrow-circle-left me-1 large"></i> Back to
          Login</a></p>
      <button class="btn btn-primary" id="submit-button" type="submit">Reset Password</button>
    </div>
    <div class="mt-1" id="reset_response"></div>
  </form>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      const passwordInput = $("#password");
      const confirmPasswordInput = $("#confirm_password");
      const strengthMessage = $("#strength-message");
      const strengthProgress = $("#strength-progress");
      const submitButton = $("#submit-button");

      // Form validation using jQuery Validate
      $("#resetPasswordForm").validate({
        rules: {
          newpassword: {
            required: true,
            minlength: 8,
            maxlength: 60
          },
          confirm_password: {
            required: true,
            equalTo: "#newpassword"
          }
        },
        messages: {
          newpassword: {
            required: "{{ __('validation.required', ['attribute' => 'Password']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'Password', 'min' => 8]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Password', 'max' => 60]) }}"
          },
          confirm_password: {
            required: "{{ __('validation.required', ['attribute' => 'Confirm Password']) }}",
            equalTo: "{{ __('validation.match', ['attribute' => 'Password', 'target' => 'Confirm Password']) }}"
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
        highlight: function(element) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function(form) {
          $.ajax({
            url: "{{ route('admin.reset_password') }}",
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              password: $("#newpassword").val(),
            },
            success: function(response) {
              if (response.success) {
                let message = response.message + ". Redirecting to login page in 5 seconds...";
                $('#reset_response').text(message).addClass('text-success').removeClass(
                  'text-danger').show();

                // Redirect to login page after 5 seconds
                setTimeout(function() {
                  window.location.href = "{{ route('admin.login') }}";
                }, 5000);
              } else {
                $('#reset_response').text(response.message).addClass('text-danger').removeClass(
                  'text-success').show();
              }
            },
            error: function(error) {
              console.log(error);
            }
          });
        }
      });
    });
  </script>
@endsection
