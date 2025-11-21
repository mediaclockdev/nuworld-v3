@props(['states'])
<div class="modal genericmodal fade AddNewAddressModal" id="AddNewAddressModal" tabindex="-1" aria-labelledby="AddNewAddressModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font24 fw-normal">Add Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body flow-rootX2">
        <div class="border-top"></div>
        <div class="modal_scroll data-simplebar">
          <form class="allForm" id="checkout_add_edit_address">
            @csrf
            <div class="form-element name">
              <label class="form-label">Name <em>*</em></label>
              <input name="name" id="name" type="text" class="form-field only-alphabet-symbols">
            </div>

            <div class="form-element has-value mobile">
              <x-phone-number-frontend :required="true" :previousValue="''" :name="'phone'" :id="'phone'" />
              <label class="form-label">Mobile No <em>*</em></label>
            </div>

            <div class="form-element address">
              <label class="form-label">Address <em>*</em></label>
              <input name="address" type="text" class="form-field">
            </div>
            <div class="form-element locality">
              <label class="form-label">Landmark <em>*</em></label>
              <input name="landmark" type="text" class="form-field">
            </div>
            <div class="form-element city">
              <label class="form-label">City / District <em>*</em></label>
              <input name="city" type="text" class="form-field">
            </div>

            <div class="form-element state">
              <label class="form-label">State <em>*</em></label>
              <select class="form-field form-select" name="state" id="state">
                <option value="" selected>Select State</option>
                @forelse ($states as $state)
                  <option value="{{ Hashids::encode($state->id) }}">{{ $state->name }}</option>
                @empty
                  <option value="">No states available</option>
                @endforelse
              </select>
            </div>
            <div class="form-element pincode">
              <label class="form-label">Pincode <em>*</em></label>
              <input name="pincode" type="text" class="form-field">
            </div>
            <div class="form-check m-0 defaultaddress mb-4">
              <input class="form-check-input" type="checkbox" name="is_default" id="is_default" checked>
              <label class="form-check-label font14" for="is_default">Make this as my default address</label>
            </div>
            <div class="action d-flex align-items-center gap-4 pt-2">
              <button type="button" class="btn btn-outline-dark w-50 btn-lg py-2"
                data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-dark w-50 btn-lg py-2">Save Details</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('component-scripts')
  <script src="{{ asset('/public/common/js/custom_input.js?v=3' . time()) }}"></script>

  <script>
    // Modal toggle
    $('.add-new-address-btn').click(() => {
      $('#AddressModal').modal('hide');
      $('select[name="state"]').parent('.form-element').addClass('has-value');
      $('#AddNewAddressModal').modal('show');
    });

    $.validator.addMethod("noSpecialChars", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\- ]+$/.test(value);
    }, "Only letters, numbers, hyphens, and spaces are allowed.");

    // Form validation and submission
    $('#checkout_add_edit_address').validate({
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
          required: true,
          maxlength: 100
        },
        landmark: {
          required: true,
          maxlength: 100
        },
        city: {
          required: true,
          maxlength: 100
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
          minlength: "{{ __('validation.minlength', ['attribute' => 'Pincode', 'min' => '3']) }}",
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
      errorElement: 'div',
      errorPlacement: (error, element) => error.insertAfter($(`#${element.attr('id')}-error-container`).length ?
        `#${element.attr('id')}-error-container` : element),
      submitHandler: function(form) {
        $.ajax({
          url: "{{ route('user.create-or-update-address') }}",
          type: 'POST',
          data: $(form).serialize(),
          success: function(response) {
            if (response.success) {
              let addressType = $('input[name="default_address"]:checked').attr('data-attr-type2') || 'shipping';
              let html = $(response.html);
              html.find('input[name="default_address"]').attr('data-attr-type2', addressType);
              updateAddress(html.prop('outerHTML'), addressType, response.id);
              // Prepend the updated HTML
              $('.address_block').prepend(html);
              $('#no-address').remove();
                form.reset();
              iziNotify('', response.message, 'success');
            } else
              iziNotify('', response.message, 'error');
          },
          error: function(xhr) {
            iziNotify('Error!', xhr.responseJSON?.message || 'Failed to save address', 'error')
          }
        })
      }
    });
    function updateAddress(addressHtml, addressType, id) {
      if (addressType == 'shipping') {
          $('#shipping_address').val(id);
          selectedShippingAddress = id;
      } else {
          $('#billing_address').val(id);
          selectedBillingAddress = id;
      }
      /* let addressPartHtml = $('#address_' + id).html();
      let label = addressType.includes('shipping') ? 'Shipping' : 'Billing';

      addressPartHtml += `<a href="javascript:void(0);" class="font16 change-address-btn" data-address-type="${addressType}">
        Change ${label} Address
      </a>`;
       $('.' + addressType).html(addressPartHtml); */
      let addressCount = $('#address_count').val();
      $('#address_count').val(parseInt($('#address_count').val()) + 1);
      $('#AddNewAddressModal').modal('hide');
      // if (addressCount > 1) {
        $('#AddressModal').modal('show');
      // }
    }
  </script>
@endpush
