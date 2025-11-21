@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')
  <section class="living__signupwrap">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-md-1">
          <div class="inswrp">
            <div class="left">
              <figure class="mb-0">
                <img src="{{ asset('public/frontend/assets/img/home/signup_popup_thumb.jpg') }}" alt="Mayuri"
                  title="Mayuri" class="imageFit" />
              </figure>
              <div class="txt">
                <h2 class="font45">LUXURY</h2>
                <p>Discover 30k+ varieties</p>
              </div>
            </div>
            <div class="commonforms flow-rootX" id="reset-password">
              <div class="righthead">
                <h3 class="font25">Reset Your Password</h3>
                <p class="c--menuc">Enter your new password below.</p>
              </div>

              <form id="resetForm" class="allForm" method="POST" action="{{ route('password.update') }}"
                autocomplete="off">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-element">
                  <label class="form-label">Email Address</label>
                  <input name="email" type="text" class="form-field" value="{{ request()->get('email') }}" readonly>
                </div>

                <div class="form-element position-relative">
                  <label class="form-label">New Password <em>*</em></label>
                  <input name="password" type="password" class="form-field password-element" id="passwordInput"
                    autocomplete="new-password" />
                  <span id="togglePassword" style="position: absolute; right: 10px; top: 15px; cursor: pointer;">
                    <span class="material-symbols-outlined font25">
                      visibility
                    </span>
                  </span>
                  <i class="msg-error"></i>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-lg py-2">Reset Password</button>
              </form>

              <div class="existing text-center mt-4">Remembered your password? <a href="{{ route('signuplogin') }}"
                  title="Login">Login</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('styles')
  {{-- <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" /> --}}
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

  {{-- <style>
    .alertify-notifier .ajs-message {
      text-align: left !important;
    }
  </style> --}}
@endpush

@push('scripts')
  <script src="{{ asset('public/common/js/custom_sweet_alert.js') }}"></script>
  {{-- <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script> --}}

  <script>
    $('#togglePassword').on('click', function() {
      const input = $('#passwordInput');
      const span_icon = $(this).find('.material-symbols-outlined');

      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        span_icon.text('visibility_off');
      } else {
        input.attr('type', 'password');
        span_icon.text('visibility');
      }
    });

    // alertify.set('notifier', 'position', 'top-right');

    $.validator.addMethod("regex", function(value, element, pattern) {
      if (this.optional(element)) {
        return true;
      }
      return pattern.test(value);
    }, "Invalid format.");

    $(document).ready(function() {
      const resetForm = $('#resetForm');

      resetForm.validate({
        rules: {
          password: {
            required: true,
            minlength: 6,
            maxlength: 20,
            // regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/
          }
        },
        messages: {
          password: {
            required: "Password is required.",
            minlength: "Password must be at least 6 characters.",
            maxlength: "Password cannot exceed 20 characters.",
            // regex: "Password must contain at least one uppercase letter, one lowercase letter, and one number."
          }
        },
        errorPlacement: function(error, element) {
          element.siblings('.msg-error').text(error.text());
        },
        highlight: function(element) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
          $(element).removeClass('is-invalid');
          $(element).siblings('.msg-error').text('');
        }
      });

      $('#resetForm').on('submit', function(e) {
        e.preventDefault();

        if (!resetForm.valid()) {
          return;
        }

        let form = $(this);
        let formData = form.serialize();

        $.ajax({
          url: form.attr('action'),
          method: 'POST',
          data: formData,
          beforeSend: function() {
            form.find('button[type="submit"]').prop('disabled', true).text('Processing...');
            form.find('.form-field').removeClass('is-invalid');
            form.find('.msg-error').text('');
          },
          success: function(res) {
            // alertify.success(res.message || 'Password has been reset!');
            iziNotify("", res.message || 'Password has been reset!', "success");
            form.find('button[type="submit"]').prop('disabled', false).text('Reset Password');
            window.location.href = res.redirect || "{{ route('signuplogin') }}";
          },
          error: function(xhr) {
            let response = xhr.responseJSON;
            let errors = response.errors || {};
            let message = response.message || 'Something went wrong. Please try again.';

            // alertify.error(message);
            iziNotify("Oops!", message, "error");

            for (let field in errors) {
              let input = form.find(`[name="${field}"]`);
              input.addClass('is-invalid');
              input.siblings('.msg-error').text(errors[field][0]);
            }

            form.find('button[type="submit"]').prop('disabled', false).text('Reset Password');
          }
        });
      });
    });
  </script>
@endpush
