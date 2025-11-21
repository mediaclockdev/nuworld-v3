<div class="modal genericmodal fade AddNewAddressModal" id="createOrUpdateAddressModal" tabindex="-1" aria-labelledby="createOrUpdateAddressModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font24 fw-normal address-modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body flow-rootX2">
        <div class="border-top"></div>
        <form class="allForm" id="address_add_edit_form">
          @csrf
          <input type="hidden" name="id" id="address_id">

          <div class="form-element name">
            <label class="form-label">Name <em>*</em></label>
            <input name="name" id="name" type="text" class="form-field only-alphabet-symbols">
          </div>
          <div class="form-element has-value mobile">
            <x-phone-number-frontend :required="true" :previousValue="''" :name="'phone'" :id="'phone'" />
            <label class="form-label">Mobile No<em>*</em></label>
          </div>
          <div class="form-element address">
            <label class="form-label">Address <em>*</em></label>
            <input name="address" type="text" class="form-field" value="">
          </div>
          <div class="form-element locality">
            <label class="form-label">Landmark <em>*</em></label>
            <input name="landmark" type="text" class="form-field" value="">
          </div>
          <div class="form-element city">
            <label class="form-label">City / District <em>*</em></label>
            <input name="city" type="text" class="form-field" value="">
          </div>
          <div class="form-element state">
            <label class="form-label">State <em>*</em></label>
            <select class="form-field form-select" name="state" id="state">
              <option value="">Select State</option>
              @forelse ($states as $state)
                <option value="{{ Hashids::encode($state->id) }}"> {{ $state->name }}</option>
              @empty
              @endforelse
            </select>
          </div>
          <div class="form-element pincode">
            <label class="form-label">Pincode <em>*</em></label>
            <input name="pincode" type="text" class="form-field">
          </div>
          <div class="form-check m-0 defaultaddress mb-4">
            <input class="form-check-input" type="checkbox" name="is_default" id="defaultaddress">
            <label class="form-check-label font14" for="defaultaddress">Make this as my default address</label>
          </div>
          <div class="action d-flex align-items-center gap-4 pt-2">
            <button type="button" class="btn btn-outline-dark w-50 btn-lg py-2 custom-btn-close" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-dark w-50 btn-lg py-2">Save Details</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@push('component-scripts')
<script src="{{ asset('/public/common/js/custom_input.js?v=3' . time()) }}"></script>
<script>
    // Open the add/edit address modal when clicking the "Add New Address" or "Edit" button
    $(document).on('click', '.create-or-edit-address', function(e) {
    e.preventDefault();
    let address = $(this).data('address'); // Parses JSON automatically
    let validator = $('#address_add_edit_form').validate(); // Get the validator instance
    let addressId = $(this).data('address-id');

    if (!address) {
        // Reset the form for adding a new address
        $('#address_add_edit_form')[0].reset();
        validator.resetForm(); // Reset validator to clear errors
        $('input[name="name"]').parent('.form-element').removeClass('has-value');
        $('input[name="pincode"]').parent('.form-element').removeClass('has-value');
        $('select[name="state"]').parent('.form-element').addClass('has-value');
        $('input[name="address"]').parent('.form-element').removeClass('has-value');
        $('input[name="city"]').parent('.form-element').removeClass('has-value');
        $('input[name="landmark"]').parent('.form-element').removeClass('has-value');
        $('input[name="phone"]').parent('.form-element').removeClass('has-value');
        $('#defaultaddress').prop('checked', true);
        $('.address-modal-title').text('Add Address');
        $('#address_id').val('');
    } else {
        // Populate the form for editing an existing address
        $('#address_id').val(addressId);
        $('input[name="name"]').val(address.name).parent('.form-element').addClass('has-value');
        $('input[name="pincode"]').val(address.pincode).parent('.form-element').addClass('has-value');
        $('select[name="state"]').val(address.state_id).parent('.form-element').addClass('has-value');
        $('input[name="address"]').val(address.address_line_1).parent('.form-element').addClass('has-value');
        $('input[name="city"]').val(address.city_name).parent('.form-element').addClass('has-value');
        $('input[name="landmark"]').val(address.landmark).parent('.form-element').addClass('has-value');
        $('input[name="phone"]').val(address.phone).parent('.form-element').addClass('has-value');
        $('#defaultaddress').prop('checked', address.primary == true);
        $('.address-modal-title').text('Edit Address');

        // Reset validator and revalidate the form
        validator.resetForm(); // Clear any existing error messages
        $('#address_add_edit_form').valid(); // Trigger validation to check populated fields
    }
    $('#createOrUpdateAddressModal').modal('show');
});

    // Form validation and submission
    $.validator.addMethod("noSpecialChars", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\- ]+$/.test(value);
    }, "Only letters, numbers, hyphens, and spaces are allowed.");

    $(document).ready(function() {
      $('#address_add_edit_form').validate({
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
                  maxlength: 255

              },
              landmark: {
                  required: true,
                  maxlength: 200
              },
              city: {
                  required: true,
                  maxlength: 40
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
                  minlength: "{{ __('validation.maxlength', ['attribute' => 'Pincode', 'max' => '3']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Pincode', 'max' => '15']) }}",
                  noSpecialChars: "Only letters, numbers, hyphens and spaces are allowed."
              },
              state: {
                  required: "{{ __('validation.required', ['attribute' => 'State']) }}"
              },
              address: {
                  required: "{{ __('validation.required', ['attribute' => 'Address']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Address', 'max' => '255']) }}"
              },
              landmark: {
                  required: "{{ __('validation.required', ['attribute' => 'Landmark']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Landmark', 'max' => '200']) }}"
              },
              city: {
                  required: "{{ __('validation.required', ['attribute' => 'City']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'City', 'max' => '40']) }}"
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
                          $('#createOrUpdateAddressModal').modal('hide');
                          // Update the header pincode display
                          $('.default-pin').text(`${response.user_pincode.Name || '{{ config('defaults.default_location') }}'} ${response.user_pincode.Pincode || '{{ config('defaults.default_pincode') }}'}`);
                          window.location.reload();
                          // Show the address list modal
                          iziNotify("", response.message, "success");
                      } else {
                          iziNotify("Oops!", response.message, "error");
                      }
                  },
                  error: function(xhr) {
                      iziNotify("Oops!", xhr.responseJSON.message, "error");
                  }
              });
          }
      });

      $('.custom-btn-close').on('click', function(e) {
        $('#addressModal').modal('hide');
      })

      $('.delete-default-address').on('click', function(e) {
        let addressId = $(this).data('address-id');
        $.ajax({
          url: `{{ route('user.delete-address') }}`,
          type: "POST",
          data: {
            id: addressId,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              iziNotify("", response.message, "success");
              $('#addressModal').modal('hide');
              // setTimeout(function() {
              window.location.reload();
              // }, 2000);
            } else {
              iziNotify("Oops!", response.message, "error");
            }
          }
        });
      })
    });
</script>
@endpush
