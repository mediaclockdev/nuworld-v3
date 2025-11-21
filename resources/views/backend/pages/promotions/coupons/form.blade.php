@extends('backend.layouts.app')

@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$coupon['id'] ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.coupons')" :formId="'couponForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Coupon Code</label>
          <input class="form-control" type="text" name="code" id="code" pattern="[A-Za-z0-9]+"
            value="{{ $coupon['code'] }}" placeholder="e.g. SUMMER20" {{ isset($coupon['code']) ? 'readonly' : '' }}
            style="{{ isset($coupon['code']) ? 'background-color: var(--ct-tertiary-bg);' : '' }}">
          <div id="code-error-container" class="invalid-feedback"></div>
          <small class="form-text text-muted">Alphanumeric only, max 20 characters.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Type</label>
          <select name="type" id="type" class="form-select select2">
            <option value="Flat" {{ $coupon['type'] === 'Flat' ? 'selected' : '' }}>Flat</option>
            <option value="Percentage" {{ $coupon['type'] === 'Percentage' || !$coupon['type'] ? 'selected' : '' }}>
              Percentage
            </option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Discount Amount</label>
          <input type="text" class="form-control only-numbers" name="discount_amount" id="discount_amount"
            value="{{ $coupon['discount_amount'] }}"
            placeholder="{{ $coupon['type'] === 'Flat' ? 'e.g. 100.00' : 'e.g. 20.00%' }}">
          <div id="discount_amount-error-container" class="invalid-feedback"></div>
          <small class="form-text text-muted">Use a percentage for 'Percentage' type, or a fixed value for 'Flat'
            type.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Max Discount</label>
          <input type="text" class="form-control only-numbers" name="max_discount" id="max_discount"
            value="{{ $coupon['max_discount'] }}" placeholder="e.g. 50.00" disabled>
          <small class="form-text text-muted">Only applicable for percentage discounts. Leave blank for no limit.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Min Order Value</label>
          <input type="text" min="0" class="form-control only-numbers" name="min_order_value"
            id="min_order_value" value="{{ $coupon['min_order_value'] }}" placeholder="e.g. 100.00">
          <small class="form-text text-muted">Minimum order amount to apply coupon. Leave blank for no minimum.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Max Uses</label>
          <input type="text" min="1" class="form-control only-numbers" name="max_uses" id="max_uses"
            value="{{ $coupon['max_uses'] }}" placeholder="e.g. 100">
          <small class="form-text text-muted">Maximum total uses for this coupon. Leave blank for unlimited uses.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Per User Limit</label>
          <input type="text" min="1" class="form-control only-numbers" name="per_user_limit" id="per_user_limit"
            value="{{ $coupon['per_user_limit'] ?? 1 }}" placeholder="e.g. 1">
          <small class="form-text text-muted">How many times a single user can use this coupon.</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Valid From</label>
          <input type="text" class="form-control datepicker" name="valid_from" id="valid_from"
            value="{{ $coupon['valid_from'] ? $coupon['valid_from'] : '' }}" autocomplete="new-date">
          <div id="valid_from-error-container" class="invalid-feedback"></div>
          <small class="form-text text-muted">Coupon start date</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Valid To</label>
          <input type="text" class="form-control datepicker" name="valid_to" id="valid_to"
            value="{{ $coupon['valid_to'] ? $coupon['valid_to'] : '' }}" autocomplete="new-date">
          <div id="valid_to-error-container" class="invalid-feedback"></div>
          <small class="form-text text-muted">Coupon end date</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status</label>
          <select name="is_active" id="is_active" class="form-select select2">
            <option value="1" {{ $coupon['is_active'] == 1 || !isset($coupon['is_active']) ? 'selected' : '' }}>
              Active</option>
            <option value="0" {{ $coupon['is_active'] === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
    </div>
  </x-form-card>
@endsection

@push('component-styles')
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style>
    .invalid-feedback {
      all: unset;
    }
  </style>
@endpush

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=' . time()) }}"></script>

  <script>
    $(document).ready(function() {
      const $form = $('#couponForm');
      const $type = $('#type');
      const $maxDiscount = $('#max_discount');
      const $discountAmount = $('#discount_amount');
      const $validFrom = $('#valid_from');
      const $validTo = $('#valid_to');

      // Initialize Select2
      $('.select2').select2({
        minimumResultsForSearch: Infinity,
        width: '100%',
      });

      if (window.location.href.includes('/create')) {
        $('#valid_from').val('');
        $('#valid_to').val('');
      }

      $('.datepicker').datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        onClose: function() {
          validateDateRange();
          $(this).valid();
        }
      });

      $('.datepicker').on('keydown paste', function(e) {
        e.preventDefault();
      });

      // Convert coupon code to uppercase
      $('#code').on('input', function() {
        this.value = this.value.toUpperCase();
      });

      // Handle type change logic
      function toggleMaxDiscount() {
        const isPercent = $type.val() === 'Percentage';
        $maxDiscount.prop('disabled', !isPercent).val(isPercent ? $maxDiscount.val() : '');
        $discountAmount.attr('placeholder', isPercent ? 'e.g. 20.00%' : 'e.g. 100.00');
      }

      $type.on('change', toggleMaxDiscount).trigger('change');

      function parseDate(str) {
        const parts = str.split('/');
        return new Date(parts[2], parts[1] - 1, parts[0]);
      }

      function formatDate(date) {
        const d = ('0' + date.getDate()).slice(-2);
        const m = ('0' + (date.getMonth() + 1)).slice(-2);
        const y = date.getFullYear();
        return `${d}/${m}/${y}`;
      }

      // Date validation function
      function validateDateRange() {
        const fromStr = $validFrom.val();
        const toStr = $validTo.val();

        let valid = true;
        $validFrom.removeClass('is-invalid');
        $validTo.removeClass('is-invalid');

        if (fromStr && toStr) {
          const fromDate = parseDate(fromStr);
          const toDate = parseDate(toStr);

          if (toDate < fromDate) {
            $validTo.addClass('is-invalid');
            valid = false;
          }
        }

        return valid;
      }

      $validFrom.add($validTo).on('change', validateDateRange);

      // jQuery Validator custom methods
      $.validator.addMethod("date", function(value, element) {
        if (this.optional(element)) return true;
        const bits = value.split('/');
        const d = new Date(bits[2], bits[1] - 1, bits[0]);
        return d && (d.getMonth() + 1) == bits[1] && d.getDate() == Number(bits[0]);
      }, "Please enter a valid date");

      $.validator.addMethod("greaterThanOrEqual", function(value, element, param) {
        const from = $(param).val();
        if (!value || !from) return true;
        return parseDate(value) >= parseDate(from);
      }, "Valid To must be same or after Valid From");

      $.validator.addMethod("alphanumericUppercase", function(value, element) {
        return this.optional(element) || /^[A-Z0-9]+$/.test(value);
      }, "Only uppercase letters and numbers allowed");

      $.validator.addMethod("validAmount", function(value, element) {
        return this.optional(element) || /^\d+(\.\d{1,2})?$/.test(value);
      }, "Please enter a valid amount with up to 2 decimal places");

      $(document).on('change', '#type', function() {
        $discountAmount.valid();
      });

      // Validation rules
      $form.validate({
        rules: {
          code: {
            required: true,
            maxlength: 20,
            alphanumericUppercase: true
          },
          discount_amount: {
            required: true,
            number: true,
            validAmount: true,
            min: 0.01,
            max: function() {
              return $type.val() === 'Percentage' ? 100 : 999999999;
            },
          },
          max_discount: {
            number: true,
            min: 0.01,
            required: function() {
              return $type.val() === 'Percentage' && $maxDiscount.val() !== '';
            }
          },
          per_user_limit: {
            required: true,
            digits: true,
            min: 1
          },
          valid_from: {
            required: true,
            date: true
          },
          valid_to: {
            required: true,
            date: true,
            greaterThanOrEqual: "#valid_from"
          }
        },
        messages: {
          code: {
            required: "Please enter a coupon code",
            maxlength: "Coupon Code must be at most 20 characters"
          },
          discount_amount: {
            required: "Enter discount amount",
            min: "Minimum discount is {0}",
            max: function() {
              return $type.val() === 'Percentage' ?
                "Max Percentage allowed is 100%" :
                "Max Flat discount allowed is 999,999,999";
            }
          },
          max_discount: {
            min: "Minimum max discount is {0}"
          },
          per_user_limit: {
            required: "Enter usage limit per user",
            digits: "Only numbers allowed",
            min: "Minimum value is 1"
          },
          valid_from: {
            required: "Start date required"
          },
          valid_to: {
            required: "End date required",
            greaterThanOrEqual: "Valid To must be same or after Valid From"
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          const container = $(`#${element.attr('id')}-error-container`);
          if (container.length) {
            error.appendTo(container);
          } else {
            error.insertAfter(element);
          }
        },
        highlight: function(element) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function(form) {
          if (!validateDateRange()) {
            swalNotify("Error!", "Please correct the date fields.", "error");
            return false;
          }

          const formID = "{{ Hashids::encode($coupon['id'] ?? '') }}";
          let url = "{{ route('admin.coupons.store') }}";

          if (formID) {
            url = `{{ route('admin.coupons.update', ':id') }}`.replace(':id', formID);
          }

          $.ajax({
            type: "POST",
            url: url,
            data: $form.serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');

                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  form.reset();
                  $('#valid_from').val('');
                  $('#valid_to').val('');
                  $('#type').val('Percentage').trigger('change');
                  $('input').removeClass("is-invalid");
                  $('.error').text('');
                }
              } else {
                swalNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              swalNotify("Error!", error.responseJSON?.message || "Something went wrong!", "error");
            }
          });
        }
      });
    });
  </script>
@endsection
