@php
  $settings = [];
  if (!empty($subscribe) && isset($subscribe->settings)) {
      $settings = json_decode($subscribe->settings, true);
  }
  // Decode the settings JSON to access image, alt_text, and hyper_link
@endphp
<section class="furniture__subscription_wrap">
  <div class="container">
    <div class="row flow-rootX2">
      <div class="col-lg-5 ms-auto">
        <h2 class="fw-normal font35">{!! $settings['content'] ?? '' !!}</h2>
      </div>
      <div class="col-lg-8 me-auto">
        <div class="emailformwrp">
          <form class="allForm" id="subscribeForm">
            @csrf
            <div class="form-element mb-1">
              <input name="email" id="email" type="text" class="form-field font45"
                placeholder="Enter your email">
            </div>
            <div id="email-error-container"></div>
            <a href="javascript:void();" class="btn btn-outline-dark px-5 py-3 font18 subscribeEmail"
              title="Subscribe">Subscribe</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@push('scripts')
  {{-- <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script> --}}
  <script>
    $(document).ready(function() {
      let validator = $('#subscribeForm').validate({
        rules: {
          email: {
            required: true,
            email: true,
            maxlength: 100
          }
        },
        messages: {
          email: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email Format']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Email', 'max' => 100]) }}"
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length)
            error.appendTo(errorContainer);
          else
            error.insertAfter(element);
        },
      });

      $('.subscribeEmail').on('click', function(e) {
        e.preventDefault();
        if ($('#subscribeForm').valid()) {
          let form = $('#subscribeForm')[0];
          let url = "{{ route('subscribeEmail') }}";
          $.ajax({
            type: "POST",
            url: url,
            data: $('#subscribeForm').serialize(),
            success: function(response) {
              if (response.success) {
                $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
                iziNotify("", response.message, "success");
                form.reset();
              } else {
                iziNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              iziNotify("Error!", error.responseJSON.message, "error");
            }
          });
        }
      });
    });
  </script>
@endpush
