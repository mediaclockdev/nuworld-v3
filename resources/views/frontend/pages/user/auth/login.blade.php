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
            <div class="commonforms flow-rootX" id="login">
              <div class="righthead">
                <h3 class="font25">Welcome Back!</h3>
                <p class="c--menuc">Log in to track orders, save items to your wishlist.</p>
              </div>

              <form id="loginForm" class="allForm" method="POST" action="{{ route('signuplogin') }}" autocomplete="off">
                @csrf

                <div class="form-element">
                  <label class="form-label">Email Address <em>*</em></label>
                  <input name="email" type="text" class="form-field" autocomplete="off" id="emailField" />
                  <i class="msg-error"></i>
                </div>

                <div class="form-element position-relative">
                  <label class="form-label">Password <em>*</em></label>
                  <input name="password" type="password" class="form-field password-element" id="passwordInput"
                    autocomplete="new-password" />
                  <span id="togglePassword" style="position: absolute; right: 10px; top: 15px; cursor: pointer;">
                    <span class="material-symbols-outlined font25">
                      visibility
                    </span>
                  </span>
                  <i class="msg-error"></i>
                </div>

                <div class="rememberwrap d-flex justify-content-between align-items-center w-100">
                  <div class="form-check m-0">
                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="rememberme">
                    <label class="form-check-label font14" for="rememberme">Remember me</label>
                  </div>
                  <div class="forgotpass"><a href="{{ route('password.request') }}" title="Forgot password?">Forgot
                      password?</a>
                  </div>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-lg py-2">Log In</button>

                {{-- <div class="googlelogin mt-4">
                  <a href="{{ route('auth.google') }}"
                    class="btn w-100 d-flex align-items-center justify-content-center border border-danger text-danger"
                    style="height: 48px; border-radius: 6px; font-weight: 500; gap: 8px; background-color: transparent;">
                    <i class="fab fa-google"></i>
                    <span>Continue with Google</span>
                  </a>
                </div> --}}
                <div class="googlelogin mt-4">
                  <a href="{{ route('auth.google') }}">
                    <span><img src="{{ asset('public/frontend/assets/img/icons/google-icon.svg') }}" alt="Google"
                        title="Google" class="" /></span> <span>Continue with Google</span>
                  </a>
                </div>
              </form>

              <div class="existing text-center">Don't have an account? <a href="{{ route('register') }}"
                  title="Register">Register</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('styles')
  {{-- AlertifyJS CDN (Semantic Theme) --}}
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
  {{-- AlertifyJS CDN (Semantic Theme) --}}
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
      const loginForm = $('#loginForm');
      const emailField = $('#emailField');
      const rememberCheckbox = $('#rememberme');

      // Prefill if remembered
      const savedEmail = localStorage.getItem('rememberedEmail');
      if (savedEmail) {
        emailField.val(savedEmail).trigger('change');
        rememberCheckbox.prop('checked', true);
      }

      loginForm.validate({
        rules: {
          email: {
            required: true,
            regex: /^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/
          },
          password: {
            required: true,
            minlength: 6,
            maxlength: 20,
            // regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/
          }
        },
        messages: {
          email: {
            required: "Email is required.",
            regex: "Please enter a valid email address."
          },
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

      loginForm.on('submit', function(e) {
        e.preventDefault();

        if (!loginForm.valid()) {
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
            // Save email to localStorage if checkbox is checked
            if (rememberCheckbox.is(':checked')) {
              localStorage.setItem('rememberedEmail', emailField.val());
            } else {
              localStorage.removeItem('rememberedEmail');
            }

            iziNotify("", 'Logged in successfully! Redirecting...', "success");
            setTimeout(() => {
              window.location.href = "{{ route('home') }}";
            }, 2000);
          },
          error: function(xhr) {
            let response = xhr.responseJSON;
            let errors = response.errors || {};
            let message = response.message || 'Something went wrong. Please try again.';

            // Show only the first error
            let firstField = Object.keys(errors)[0];
            if (firstField) {
              iziNotify("Oops!", errors[firstField][0], "error");
            }

            for (let field in errors) {
              let input = form.find(`[name="${field}"]`);
              input.addClass('is-invalid');
              input.siblings('.msg-error').text(errors[field][0]);
            }

            form.find('button[type="submit"]').prop('disabled', false).text('Log In');
          }
        });
      });
    });
  </script>
@endpush
