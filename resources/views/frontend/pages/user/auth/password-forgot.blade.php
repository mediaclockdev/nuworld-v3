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
                <p>Discover 30k+ varities</p>
              </div>
            </div>
            <div class="commonforms flow-rootX" id="forgot-password">
              <div class="righthead">
                <h3 class="font25">Forgot Your Password?</h3>
                <p class="c--menuc">No worries! Enter your email to get a reset link.</p>
              </div>

              <form id="forgotForm" class="allForm" method="POST" action="{{ route('password.email') }}"
                autocomplete="off">
                @csrf

                <div class="form-element">
                  <label class="form-label">Email Address <em>*</em></label>
                  <input name="email" type="text" class="form-field" autocomplete="new-email" />
                  <i class="msg-error"></i>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-lg py-2">Send Password Reset Link</button>
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
    // alertify.set('notifier', 'position', 'top-right');

    $.validator.addMethod("regex", function(value, element, pattern) {
      if (this.optional(element)) {
        return true;
      }
      return pattern.test(value);
    }, "Invalid format.");

    $(document).ready(function() {
      const forgotForm = $('#forgotForm');

      forgotForm.validate({
        rules: {
          email: {
            required: true,
            regex: /^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/
          },
        },
        messages: {
          email: {
            required: "Email is required.",
            regex: "Please enter a valid email address."
          },
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

      $('#forgotForm').on('submit', function(e) {
        e.preventDefault();

        if (!forgotForm.valid()) {
          return;
        }

        let form = $(this);
        let formData = form.serialize();

        $.ajax({
          url: form.attr('action'),
          method: 'POST',
          data: formData,
          beforeSend: function() {
            form.find('button[type="submit"]').prop('disabled', true).text('Sending...');
            form.find('.form-field').removeClass('is-invalid');
            form.find('.msg-error').text('');
          },
          success: function(res) {
            // alertify.success('Reset link sent! Please check your email.');
            iziNotify("", 'Reset link sent! Please check your email.', "success");
            form.find('button[type="submit"]').prop('disabled', false).text('Send Password Reset Link');
            form[0].reset();
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

            form.find('button[type="submit"]').prop('disabled', false).text('Send Password Reset Link');
          }
        });
      });
    });
  </script>
@endpush
