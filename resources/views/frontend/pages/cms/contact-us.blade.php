@php
  $image1 = config('defaults.contact_us_image1');
  $image2 = config('defaults.contact_us_image2');
@endphp
<main class="pg_contact">
  <section class="getIntouch">
    <div class="container">
      <div class="inner-container">
        <div class="row justify-content-between">
          <div class="left image col-lg-5">
            <figure><img
                src="{{ !empty($page->feature_image) ? asset('public/storage/uploads/cms_pages/' . $page->feature_image) : asset('public/frontend/assets/img/contact/banner.webp') }}"
                alt="{{ $page->title ?? '' }}" title="{{ $page->title ?? '' }}" />
            </figure>
          </div>
          <div class="content col-lg-6">
            <h1 class="fw-normal font45">Get in touch</h1>
            <p class="desc c--gry">Reach out today with any questions regarding catering, wedding and event food and
              decorations, or about our Indian and Asian marketplace. All are welcome at Mayuri! </p>
            <form class="allForm" id="contactForm">
              <div class="form-element fname">
                <label class="form-label">First Name <em>*</em></label>
                <input name="first_name" id="first_name" type="text" class="form-field">
              </div>
              <div class="form-element lname">
                <label class="form-label">Last Name </label>
                <input name="last_name" id="last_name" type="text" class="form-field">
              </div>
              <div class="form-element email">
                <label class="form-label">Enter Your Email <em>*</em></label>
                <input name="email" id="email" type="email" class="form-field">
              </div>
              <div class="form-element textarea">
                <label class="form-label">Enter Your Message</label>
                <textarea class="form-field" name="message" id="message" type="text"></textarea>
              </div>
              <div class="form-element captcha">
                <div class="captcha-wrapper">
                  <img src="{{ captcha_src('flat') }}" alt="Captcha" id="captcha-image" class="captcha-img">
                  <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshCaptcha()"
                    title="Refresh CAPTCHA">
                    <i class="ri-refresh-line"></i>
                  </button>
                </div>
                <input type="text" name="captcha" id="captcha" class="form-control mt-2"
                  placeholder="Enter CAPTCHA">
              </div>
              <div class="action">
                <button type="submit" class="btn btn-dark btn-lg py-2 px-4" id="sendMessageBtn">Send Message</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="contact">
    <div class="container">
      <div class="inner-container">
        <div class="row justify-content-between">
          <div class="col-3">
            <h1 class="fw-normal font45">Contact Us</h1>
            <p class="desc c--gry">Lorem ipsum dolor sit amet consectetur. Urna ac nulla in praesent eu maecenas
              viverra.</p>
          </div>
          <div class="col-9">
            <div class="row contactBoxwrap">
              <div class="col-4">
                <p class="count font24 c--gry mb-1">.01</p>
                <p class="text font24 mb-5">Call Us</p>
                <a href="tel:+14258613800" class=cta font20"><svg width="13" height="13" viewBox="0 0 13 13"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                  </svg>
                  (425) 861-3800</a>
              </div>
              <div class="col-4">
                <p class="count font24 c--gry mb-1">.02</p>
                <p class="text font24 mb-5">Send us an email</p>
                <a href="mailto:contact@mayuriseattle.com" class=cta font20"><svg width="13" height="13"
                    viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                  </svg>
                  contact@mayuriseattle.com</a>
              </div>
              <div class="col-4">
                <p class="count font24 c--gry mb-1">.03</p>
                <p class="text font24 mb-5">Live chat with us</p>
                <a href="javascript: void(0);" class=cta font20"><svg width="13" height="13" viewBox="0 0 13 13"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                  </svg>
                  Open Live Chat</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @include('frontend.includes.banners.showrooms')
</main>

@push('scripts')
  <script>
    $(document).ready(function() {
      $("#contactForm").validate({
        rules: {
          first_name: {
            required: true,
            minlength: 2
          },
          last_name: {
            minlength: 2
          },
          email: {
            required: true,
            email: true
          },
          message: {
            required: true,
            minlength: 10
          },
          captcha: {
            required: true,
          }
        },
        messages: {
          first_name: {
            required: "{{ __('validation.required', ['attribute' => 'First Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'First Name', 'min' => 2]) }}"
          },
          last_name: {
            // required: "{{ __('validation.required', ['attribute' => 'Last Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'Last Name', 'min' => 2]) }}"
          },
          email: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.email', ['attribute' => 'Email']) }}"
          },
          message: {
            required: "{{ __('validation.required', ['attribute' => 'Message']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'Message', 'min' => 10]) }}"
          },
          captcha: {
            required: "{{ __('validation.required', ['attribute' => 'Captcha']) }}"
          }
        },
        errorElement: "span",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
          $('#sendMessageBtn').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...'
          );
          let formData = new FormData(form);
          $.ajax({
            url: `{{ route('contact-us.store') }}`,
            type: "POST",
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                iziNotify("", response.message, "success");
                form.reset();
                refreshCaptcha();
              } else {
                iziNotify("Oops!", response.message, "error");
              }
              $('#sendMessageBtn').prop('disabled', false).html('Send Message');
            },
            error: function(error) {
              iziNotify("Oops!", error.responseJSON.message, "error");
              $('#captcha').val('');
              refreshCaptcha();
              $('#sendMessageBtn').prop('disabled', false).html('Send Message');
            }
          });
        }
      });

      function refreshCaptcha() {
        const captchaImage = document.getElementById('captcha-image');
        captchaImage.src = '{{ captcha_src('flat') }}?' + new Date().getTime();
      }
      window.refreshCaptcha = refreshCaptcha;
    });
  </script>
@endpush
@push('styles')
  <style>
    .captcha-wrapper {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .captcha-img {
      max-width: 150px;
      height: auto;
      margin-right: 10px;
    }

    .text-danger {
      font-size: 0.875rem;
      margin-top: 5px;
      display: block;
    }
  </style>
@endpush
