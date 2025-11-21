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
            <div class="commonforms flow-rootX" id="signup">
              <div class="righthead">
                <h3 class="font25">Let’s Get You Started!</h3>
                <p class="c--menuc">Register in seconds and start exploring amazing collections.</p>
              </div>

              <form id="registerForm" class="allForm" method="POST" action="{{ route('register') }}" autocomplete="off">
                @csrf

                <div class="form-element">
                  <label class="form-label">First Name <em>*</em></label>
                  <input name="first_name" type="text" class="form-field" autocomplete="new-name" />
                  <i class="msg-error"></i>
                </div>

                <div class="form-element">
                  <label class="form-label">Last Name</label>
                  <input name="last_name" type="text" class="form-field" autocomplete="new-name" />
                  <i class="msg-error"></i>
                </div>

                <div class="form-element">
                  <label class="form-label">Email Address <em>*</em></label>
                  <input name="email" type="email" class="form-field" autocomplete="new-email" />
                  <i class="msg-error"></i>
                </div>

                <div class="form-element">
                  {{-- <label class="form-label">Phone</label> --}}
                  {{-- <input name="phone" type="text" class="form-field" autocomplete="new-phone" /> --}}
                  {{-- <i class="msg-error"></i> --}}
                  <x-phone-number-frontend :required="false" :previousValue="''" :name="'phone'" :id="'phone'" />
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

                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" checked id="flexCheckDefault">
                  <label class="form-check-label font14" for="flexCheckDefault">
                    By continuing, you agree to our <a href="{{ url('terms-of-use') }}" target="_blank">Terms of Use</a>
                    and <a href="{{ url('privacy-policy') }}" target="_blank">Privacy
                      Policy</a>.
                  </label>
                </div>

                <button type="submit" class="btn btn-dark w-100 btn-lg py-2">Create Account</button>

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

              <div class="existing text-center">Already have an account? <a href="{{ route('signuplogin') }}"
                  title="Log In">Log
                  In</a></div>
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
      const registerForm = $('#registerForm');

      registerForm.validate({
        rules: {
          first_name: {
            required: true,
            minlength: 2,
            maxlength: 50,
            regex: /^[a-zA-Z.'\-\s]+$/
          },
          last_name: {
            minlength: 2,
            maxlength: 50,
            regex: /^[a-zA-Z.'\-\s]+$/
          },
          email: {
            required: true,
            regex: /^[\w\.\-]+@([\w\-]+\.)+[\w]{2,4}$/
          },
          phone: {
            // regex: /^[0-9+\-\s()]{7,20}$/,
            maxlength: 20,
            validPhone: true,
          },
          password: {
            required: true,
            minlength: 6,
            maxlength: 20,
            // regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/
          }
        },
        messages: {
          first_name: {
            required: "Please enter your first name.",
            minlength: "First name must be at least 2 characters.",
            maxlength: "First name can be up to 50 characters.",
            regex: "First name may only contain letters, spaces, dots (.), apostrophes ('), and hyphens (-)."
          },
          last_name: {
            minlength: "Last name must be at least 2 characters.",
            maxlength: "Last name can be up to 50 characters.",
            regex: "Last name may only contain letters, spaces, dots (.), apostrophes ('), and hyphens (-)."
          },
          email: {
            required: "Email is required.",
            regex: "Please enter a valid email address."
          },
          phone: {
            // regex: "Please enter a valid phone number.",
            maxlength: "Phone number must not exceed 20 characters.",
            validPhone: 'Please enter a valid phone number.',
          },
          password: {
            required: "Password is required.",
            minlength: "Password must be at least 6 characters.",
            maxlength: "Password cannot exceed 20 characters.",
            // regex: "Password must contain at least one uppercase letter, one lowercase letter, and one number."
          }
        },
        errorPlacement: function(error, element) {
          let errorContainer = element.closest('.phone-input-container');
          if (errorContainer.length) {
            errorContainer.find('.msg-error').text(error.text());
          }

          element.siblings('.msg-error').text(error.text());
        },
        highlight: function(element) {
          $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
          const $el = $(element);
          const $errorContainer = $el.closest('.phone-input-container');
          if ($errorContainer.length) {
            $errorContainer.find('.msg-error').text('');
          }

          $(element).removeClass('is-invalid');
          $(element).siblings('.msg-error').text('');
        }
      });

      $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        if (!registerForm.valid()) {
          return;
        }

        let form = $(this);
        let formData = form.serialize();

        if (!$('#flexCheckDefault').is(':checked')) {
          // alertify.error('Please agree to the Terms of Use and Privacy Policy.');
          iziNotify("Oops!", 'Please agree to the Terms of Use and Privacy Policy.', "error");
          return;
        }

        $.ajax({
          url: form.attr('action'),
          method: 'POST',
          data: formData,
          beforeSend: function() {
            form.find('button[type="submit"]').prop('disabled', true).text('Registering...');
            form.find('.form-field').removeClass('is-invalid');
            form.find('.msg-error').text('');
          },
          success: function(res) {
            // alertify.success('Registration successful! Redirecting...');
            iziNotify("", 'Registration successful! Redirecting...', "success");
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
              // alertify.error(errors[firstField][0]);
              iziNotify("Oops!", errors[firstField][0], "error");
            }

            for (let field in errors) {
              let input = form.find(`[name="${field}"]`);
              input.addClass('is-invalid');
              input.siblings('.msg-error').text(errors[field][0]);
            }

            form.find('button[type="submit"]').prop('disabled', false).text('Create Account');
          }
        });
      });
    });
  </script>
@endpush
