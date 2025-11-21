@extends('backend.layouts.guest')
@section('page-styles')
  <style>
    .success {
      color: green;
    }
  </style>
@endsection
@section('content')
  <div class="text-left">
    <h3 class="text-primary pb-0 fw-medium mt-0">Forgot Password</h3>
    <p class="text-dark mb-4">Enter your registered email to reset password.</p>
  </div>

  <form id="forgotPasswordForm">
    <div class="mb-4 required">
      <label for="emailaddress" class="form-label">Email Address</label>
      <input class="form-control" type="email" id="emailaddress" name="emailaddress" placeholder="Enter your email">
      <span class="mt-3" id="reset_response"></span>
    </div>

    <div class="mb-0 d-flex justify-content-between align-items-center">
      <p class="text-black m-0"><a href="{{ route('admin.login') }}"
          class="text-black back-btn d-flex align-items-center"><i class="uil-arrow-circle-left me-1 large"></i> Back to
          Login</a>
      </p>
      <button class="btn btn-primary" type="submit">Submit </button>
    </div>
  </form>
@endsection
@section('page-scripts')
  <script src={{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}></script>
  <script>
    $(document).ready(function() {
      $('#reset_response').hide();

      $("#forgotPasswordForm").validate({
        rules: {
          emailaddress: {
            required: true,
            email: true,
          },
        },
        messages: {
          emailaddress: {
            required: '{{ __('validation.required', ['attribute' => 'Email Address']) }}',
            email: '{{ __('validation.invalid', ['attribute' => 'Email Format']) }}',
          },
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback
          }
        },
        highlight: function(element) {
          $(element).addClass('is-invalid').removeClass('is-valid');
        },
        unhighlight: function(element) {
          $(element).removeClass('is-invalid').addClass('is-valid');
        },
        submitHandler: function(form) {
          $.ajax({
            url: "{{ route('admin.forgot_password') }}",
            type: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              email: $('#emailaddress').val()
            },
            success: function(response) {
              $('#reset_response').text(response.message).addClass(response.success ? 'text-success' : 'text-danger').removeClass(response.success ? 'text-danger' : 'text-success').show();
              if (response.success) {
                $('#forgotPasswordForm')[0].reset();
                setTimeout(() => window.location.href = "{{ route('admin.login') }}", 2000);
              }
            },
            error: function(error) {
              $('#reset_response').text(error.responseJSON.message).addClass('text-danger').show();;
            }
          });
        }
      });
    });
  </script>
@endsection
