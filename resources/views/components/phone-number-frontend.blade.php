@push('component-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/intlTelInput/intlTelInput.min.css') }}">
  <style>
    .iti {
      width: 100%;
    }

    .iti input {
      width: 100%;
      padding: 10px;
    }
  </style>
@endpush

<div class="{{ $required ? 'required' : '' }} phone-input-container">
  {{-- <label class="form-label">Phone</label> --}}
  <input type="text" class="form-field only-numbers" name="{{ $name }}" id="{{ $id }}"
    autocomplete="new-phone" inputmode="numeric" placeholder="Phone" value="{{ $previousValue ?? '' }}">
  <i class="msg-error"></i>
  <div class="{{ $id }}-error-container"></div>
</div>

@push('component-scripts')
  <script src="{{ asset('/public/backend/assetss/intlTelInput/intlTelInput.min.js') }}"></script>

  <script>
    const input = document.querySelector("#{{ $id }}"),
      iti = window.intlTelInput(input, {
        initialCountry: "auto",
        autoPlaceholder: "off",
        formatOnDisplay: false, // Prevent reformatting to national format
        geoIpLookup: t => {
          fetch("https://ipapi.co/json").then((t => t.json())).then((i => t(i.country_code))).catch((() => t("in")))
        },
        strictMode: true,
        loadUtils: () => import("{{ asset('/public/backend/assetss/intlTelInput/utils.js') }}")
      }),
      form = input.closest("form");

    input.addEventListener('open:countrydropdown', function() {
      // 1. Prevent body scroll but keep scrollbar visible
      document.documentElement.style.overflowY = 'hidden';
      document.body.style.overflowY = 'hidden';

      // 2. Make sure dropdown is scrollable
      const dropdown = document.querySelector('.iti__country-list');
      if (dropdown) {
        dropdown.style.overflowY = 'auto';
        // dropdown.style.maxHeight = '70vh'; // Ensure reasonable height

        // 3. Prevent scroll event from bubbling to body
        dropdown.addEventListener('wheel', function(e) {
          e.stopPropagation();
        }, {
          passive: false
        });
      }
    });

    input.addEventListener('close:countrydropdown', function() {
      // Restore scrolling
      document.documentElement.style.overflowY = '';
      document.body.style.overflowY = '';

      // Clean up event listener
      const dropdown = document.querySelector('.iti__country-list');
      if (dropdown) {
        dropdown.removeEventListener('wheel', stopPropagation);
      }
    });

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
