@push('component-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/intlTelInput/intlTelInput.min.css') }}">
@endpush
<div class="mb-4 {{ $required ? 'required' : '' }} phone-input-container">
  <label for="phone" class="form-label">Phone</label>
  <input type="text" class="form-control only-numbers" name="{{ $name }}" id="{{ $id }}"
    placeholder="Enter Phone Number" value="{{ $previousValue ?? '' }}" inputmode="numeric">
  <div class="{{ $id }}-error-container"></div>
</div>

@push('component-scripts')
  <script src="{{ asset('/public/backend/assetss/intlTelInput/intlTelInput.min.js') }}"></script>

  <script>
    const input = document.querySelector("#{{ $id }}"),
      iti = window.intlTelInput(input, {
        initialCountry: "auto",
        formatOnDisplay: false, // Prevent reformatting to national format
        geoIpLookup: t => {
          fetch("https://ipapi.co/json").then((t => t.json())).then((i => t(i.country_code))).catch((() => t("in")))
        },
        strictMode: true,
        loadUtils: () => import("{{ asset('/public/backend/assetss/intlTelInput/utils.js') }}")
      }),
      form = input.closest("form");
    form && form.addEventListener("submit", (function(t) {
      if (iti.isValidNumber()) {
        const t = iti.getNumber();
        input.value = t
      }
    })), jQuery.validator.addMethod("validPhone", (function(t, i) {
      return this.optional(i) || iti.isValidNumber()
    }), "{{ __('validation.invalid', ['attribute' => 'Phone Number']) }}");
  </script>
@endpush
