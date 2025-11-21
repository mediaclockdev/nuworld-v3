<!-- JavaScript Libraries -->
<script src="{{ asset('/public/backend/assetss/js/vendor.min.js') }}"></script>

<!-- For Marquee -->
<script src="{{ asset('public/frontend/assets/js/jquery.marquee.min.js') }}"></script>

<!-- Bootstrat JS -->
<script src="{{ asset('public/frontend/assets/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('public/frontend/assets/lib/swiper@9/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('public/frontend/assets/js/easyzoom.min.js') }}"></script>
<script src="{{ asset('public/frontend/assets/js/gsap.min.js') }}"></script>
<script src="{{ asset('public/frontend/assets/js/SplitText.min.js') }}"></script>
<script src="{{ asset('public/frontend/assets/js/ScrollTrigger.min.js') }}"></script>
<script src="{{ asset('public/frontend/assets/js/lenis.min.js') }}"></script>

<script src="{{ asset('public/backend/assetss/js/jquery.validate.min.js') }}"></script>

<!-- Custom Javascript -->
<script type="text/javascript" src="{{ asset('public/frontend/assets/js/alert.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/frontend/assets/js/main.js?v=1') }}"></script>
<script>
  $('.has-megamenu').click(function() {
    $(this).find('.dropdown-toggle, .megamenu').toggleClass('show');
  })
</script>

{{-- Address Modal JS --}}
<script>
    $(document).on('click', '.list-address', function(e) {
        e.preventDefault();
        $('#listOfAddressModal').modal('show');
    });

    if (document.getElementById('openCreateAddressModalBtn')) {
        document.getElementById('openCreateAddressModalBtn').addEventListener('click', function () {
        const scriptUrl = `{{ asset('/public/backend/assetss/intlTelInput/intlTelInput.min.js') }}`;
        if (!document.querySelector(`script[src="${scriptUrl}"]`)) {
            const script = document.createElement('script');
            script.src = scriptUrl;
            script.onload = loadAddressModal; // Load modal after script is ready
            document.body.appendChild(script);
        } else {
            loadAddressModal();
        }
      });
    }

    function loadAddressModal() {
      fetch('{{ route('load.address.modal') }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('createNewAddressModalContainer').innerHTML = data.html;

            // Show modal
            const modalElement = document.getElementById('addressModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            $('#listOfAddressModal').modal('hide');

            initAddressFormValidation();
            populateForm();
            initAddressMobileJs();
        })
        .catch(error => {
            console.error('Error loading modal:', error);
            iziNotify('Error!', 'Failed to load the address modal.', 'error');
        });
    }
    function initAddressMobileJs() {
      const input = document.querySelector("#custom-phone");
      if (!input) return;

      let iti = window.intlTelInput(input, {
          initialCountry: "auto",
          autoPlaceholder: "off",
          formatOnDisplay: false,
          geoIpLookup: function(callback) {
              fetch("https://ipapi.co/json")
                  .then(res => res.json())
                  .then(data => callback(data.country_code))
                  .catch(() => callback("IN"));
          },
          strictMode: true,
          loadUtils: () => import("{{ asset('/public/backend/assetss/intlTelInput/utils.js') }}")
      });

      const form = input.closest("form");

      input.addEventListener('open:countrydropdown', function () {
          document.documentElement.style.overflowY = 'hidden';
          document.body.style.overflowY = 'hidden';

          const dropdown = document.querySelector('.iti__country-list');
          if (dropdown) {
              dropdown.style.overflowY = 'auto';
              dropdown.addEventListener('wheel', stopPropagation, { passive: false });
          }
      });

      input.addEventListener('close:countrydropdown', function () {
          document.documentElement.style.overflowY = '';
          document.body.style.overflowY = '';

          const dropdown = document.querySelector('.iti__country-list');
          if (dropdown) {
              dropdown.removeEventListener('wheel', stopPropagation);
          }
      });

      form?.addEventListener("submit", function (e) {
          if (iti.isValidNumber()) {
              input.value = iti.getNumber();
          }
      });

      $.validator.addMethod("validPhone", function (value, element) {
          return this.optional(element) || iti.isValidNumber();
      }, "{{ __('validation.invalid', ['attribute' => 'Phone Number']) }}");

      function stopPropagation(e) {
          e.stopPropagation();
      }
    }

    function initAddressFormValidation() {
      $.validator.addMethod("noSpecialChars", function(value, element) {
          return this.optional(element) || /^[a-zA-Z0-9\- ]+$/.test(value);
      }, "Only letters, numbers, hyphens, and spaces are allowed.");

      $('#add_address_form').validate({
          rules: {
              name: {
                  required: true,
                  maxlength: 50
              },
              phone: {
                  required: true,
                  validPhone: true
              },
              pincode: {
                  required: true,
                  minlength: 3,
                  maxlength: 15,
                  noSpecialChars: true
              },
              state: {
                  required: true
              },
              address: {
                  required: true
              },
              landmark: {
                  required: true
              },
              city: {
                  required: true
              }
          },
          messages: {
              name: {
                  required: "{{ __('validation.required', ['attribute' => 'Name']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Name', 'max' => '50']) }}"
              },
              phone: {
                  required: "{{ __('validation.required', ['attribute' => 'Mobile']) }}",
              },
              pincode: {
                  required: "{{ __('validation.required', ['attribute' => 'Pincode']) }}",
                  minlength: "{{ __('validation.minlength', ['attribute' => 'Pincode', 'max' => '3']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Pincode', 'max' => '15']) }}",
                  noSpecialChars: "Only letters, numbers, hyphens and spaces are allowed."
              },
              state: {
                  required: "{{ __('validation.required', ['attribute' => 'State']) }}"
              },
              address: {
                  required: "{{ __('validation.required', ['attribute' => 'Address']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Address', 'max' => '100']) }}"
              },
              landmark: {
                  required: "{{ __('validation.required', ['attribute' => 'Landmark']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Landmark', 'max' => '100']) }}"
              },
              city: {
                  required: "{{ __('validation.required', ['attribute' => 'City']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'City', 'max' => '100']) }}"
              }
          },
          errorElement: "i",
          errorPlacement: function(error, element) {
              let errorContainer = $(`#${element.attr('id')}-error-container`);
              if (errorContainer.length) {
                  error.appendTo(errorContainer);
              } else {
                  error.insertAfter(element);
              }
          },
          submitHandler: function(form) {
              $.ajax({
                  url: '{{ route("user.update-address") }}',
                  type: "POST",
                  data: new FormData(form),
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      if (response.success) {
                          // Hide the add/edit address modal
                          $('#addressModal').modal('hide');

                          // Refresh the address list
                          window.refreshAddressList();

                          // Update the header pincode display
                          $('.default-pin').text(`${response.user_pincode.Name || '{{ config('defaults.default_location') }}'} ${response.user_pincode.Pincode || '{{ config('defaults.default_pincode') }}'}`);

                          // Show the address list modal
                          $('#listOfAddressModal').modal('show');

                          iziNotify("", response.message, "success");
                      } else {
                          iziNotify("Oops!", response.message, "error");
                      }
                  },
                  error: function(xhr) {
                      iziNotify("Oops!", "An error occurred while saving the address.", "error");
                  }
              });
          }
      });
    }

    function populateForm() {
        $('input[name="name"]').on('focusin', function() {
            $(this).parent('.form-element').addClass('has-value');
        });
        $('input[name="name"]').on('focusout', function() {
          if (!$(this).val()) {
            $(this).parent('.form-element').removeClass('has-value');
          }
        });

        $('input[name="pincode"]').on('focusin', function() {
            $(this).parent('.form-element').addClass('has-value');
        });
        $('input[name="pincode"]').on('focusout', function() {
          if (!$(this).val()) {
            $(this).parent('.form-element').removeClass('has-value');
          }
        });

        $('input[name="address"]').on('focusin', function() {
            $(this).parent('.form-element').addClass('has-value');
        });
        $('input[name="address"]').on('focusout', function() {
          if (!$(this).val()) {
            $(this).parent('.form-element').removeClass('has-value');
          }
        });

        $('input[name="city"]').on('focusin', function() {
            $(this).parent('.form-element').addClass('has-value');
        });
        $('input[name="city"]').on('focusout', function() {
          if (!$(this).val()) {
            $(this).parent('.form-element').removeClass('has-value');
          }
        });

        $('input[name="landmark"]').on('focusin', function() {
            $(this).parent('.form-element').addClass('has-value');
        });
        $('input[name="landmark"]').on('focusout', function() {
          if (!$(this).val()) {
            $(this).parent('.form-element').removeClass('has-value');
          }
        });

        $('select[name="state"]').parent('.form-element').addClass('has-value');
        $('input[name="phone"]').parent('.form-element').addClass('has-value');
        $('#defaultaddress').prop('checked', true);
        $('.address-modal-title').text('Add Address');
        $('#address_id').val('');
    }

  </script>
