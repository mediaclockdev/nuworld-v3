@extends('backend.layouts.app')

@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
  {{-- <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css" rel="stylesheet" /> --}}
@endsection
@php
  $selectedVariantIds = $promotion->promotionDetail->pluck('product_variant_id')->toArray();
  // dd($selectedVariantIds);
@endphp

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$promotion->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.promotion')" :formId="'promotionForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Name</label>
          <input class="form-control" type="text" name="name" id="name" pattern="[A-Za-z0-9]+"
            value="{{ $promotion->name ?? '' }}">
          <div id="name-error-container" class="invalid-feedback"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Promotion Start From</label>
          <input type="text" id="promotion_start_from" name="promotion_start_from" class="form-control"
            value="{{ $promotion->promotion_start_from ?? '' }}" min="{{ now()->format('Y-m-d\TH:i') }}"
            autocomplete="off" readonly>
          {{-- <p></p> --}}
          <div id="promotion_start_from-error-container" class="invalid-feedback"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Promotion End To</label>
          <input type="text" id="promotion_end_to" name="promotion_end_to" class="form-control"
            value="{{ $promotion->promotion_end_to ?? '' }}" min="{{ now()->format('Y-m-d\TH:i') }}" autocomplete="off"
            readonly>
          <div id="promotion_end_to-error-container" class="invalid-feedback"></div>
        </div>
      </div>

      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" id="description" placeholder="Enter Description"></textarea>
          <div id="description-error-container">{{ $promotion->description ?? '' }}</div>
        </div>
      </div>


      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label d-block">Promotion Mode</label>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="promotion_mode" id="promotion_mode_1" value="1"
              {{ $promotion->promotion_mode == 1 || $promotion->promotion_mode == '' ? 'checked' : '' }}>
            <label class="form-check-label" for="promotion_mode_1">Product Wise</label>
          </div>

          {{-- <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="promotion_mode" id="promotion_mode_2" value="2"
              {{ $promotion->promotion_mode == 2 ? 'checked' : '' }}>
            <label class="form-check-label" for="promotion_mode_2">Category Wise</label>
          </div> --}}

          <div id="promotion_mode-error-container"></div>
        </div>
      </div>


      <div class="col-md-4">

      </div>


      <div class="col-md-4">

      </div>

      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="title" class="form-label">Product</label>
          <select name="product_id" id="product_id" class="form-select select2">
            <option value=""></option>
            @foreach ($products as $product)
              <option value="{{ Hashids::encode($product->id) }}"
                {{ $promotion->promotionDetail()?->first()?->product_id == $product->id ? 'selected' : '' }}>
                {{ $product->name }}</option>
            @endforeach
          </select>
          <div id="product_id-error-container"></div>
        </div>
      </div>

      <!-- Product Variant Dropdown (initially empty) -->
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="product_variant_id" class="form-label">Product Variant</label>
          <select name="product_variant_id[]" id="product_variant_id" class="form-select select2" multiple
            style="min-height: 45px; max-height: 100px; overflow-y: auto;">
            <option value="">Select a variant</option>
          </select>
          <div id="variant_id-error-container"></div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Type</label>
          <select name="type" id="type" class="form-select select2">
            <option value="Flat"{{ $promotion->promotionDetail()?->first()?->type == 'Flat' ? 'selected' : '' }}>
              Flat</option>
            <option
              value="Percentage"{{ $promotion->promotionDetail()?->first()?->type == 'Percentage' ? 'selected' : '' }}>
              Percentage
            </option>
          </select>
          <div id="type-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Amount</label>
          <input type="text" class="form-control only-numbers" name="discount_amount" id="discount_amount"
            value="{{ $promotion->promotionDetail()?->first()?->discount_amount }}">
          <div id="discount_amount-error-container" class="invalid-feedback"></div>
          <small class="form-text text-muted">Use a percentage for 'Percentage' type, or a fixed value for 'Flat'
            type.</small>
        </div>
      </div>
      {{-- <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Valid From</label>
          <input type="text" id="valid_from" name="valid_from" class="form-control"
            value="{{ $promotion->promotionDetail()?->first()?->valid_from }}"
            min="{{ now()->format('Y-m-d\TH:i') }}">
          <div id="valid_from-error-container" class="invalid-feedback"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Valid To</label>
          <input type="text" id="valid_to" name="valid_to" class="form-control"
            value="{{ $promotion->promotionDetail()?->first()?->valid_to }}" min="{{ now()->format('Y-m-d\TH:i') }}">
          <div id="valid_to-error-container" class="invalid-feedback"></div>
        </div>
      </div> --}}
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
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


  <script>
    $(document).ready(function() {
      let selectedVariantIds = @json($selectedVariantIds ?? []);
      // Initialize flatpickr with confirmDate plugin for all fields
      ['#promotion_start_from', '#promotion_end_to'].forEach(selector => {
        flatpickr(selector, {
          enableTime: true,
          dateFormat: "Y-m-d h:i K", // 12-hour format with AM/PM
          minDate: "today",
          time_24hr: false, // default, but explicitly stated
          allowInput: true,
          // plugins: [new confirmDatePlugin({})],
        });
      });
      // Initialize Select2
      // $('.select2').select2({
      //   minimumResultsForSearch: Infinity,
      //   width: '100%'
      // });

      $('#product_id').select2({
        'placeholder': 'Select Product',
      });

      $('#product_variant_id').select2({
        'placeholder': 'Select Product Variants',
      });
      $('#type').select2({
        'placeholder': 'Select Product Variants',
      });

      $('#product_id').on('change', function() {
        var productId = $(this).val();
        $('#product_variant_id').html('<option value="">Loading...</option>');

        if (productId) {
          $.ajax({
            url: "{{ route('admin.get-product-variants') }}",
            type: 'GET',
            data: {
              product_id: productId
            },
            success: function(res) {
              console.log("Variants:", res.variants);

              let options = '';

              if (res.success === true && Array.isArray(res.variants) && res.variants.length > 0) {
                res.variants.forEach(function(variant) {
                  const isSelected = selectedVariantIds.includes(variant.id);
                  options +=
                    `<option value="${variant.id}" ${isSelected ? 'selected' : ''}>${variant.name}</option>`;
                });
              } else {
                options = '<option value="">No variants found</option>';
              }

              $('#product_variant_id').html(options).trigger('change');
            },
            error: function() {
              $('#product_variant_id').html('<option value="">Error loading variants</option>');
            }
          });
        } else {
          $('#product_variant_id').html('<option value="">Select a product first</option>');
        }
      });
      const preSelectedProduct = $('#product_id').val();
      if (preSelectedProduct) {
        $('#product_id').trigger('change');
      }
      // Handle type change logic for the discount amount
      function toggleDiscountPlaceholder() {
        const isPercent = $('#type').val() === 'Percentage';
        $('#discount_amount').attr('placeholder', isPercent ? 'e.g. 20.00%' : 'e.g. 100.00');
      }

      $('#type').on('change', toggleDiscountPlaceholder).trigger('change');


      function formatDate(date) {
        const d = ('0' + date.getDate()).slice(-2);
        const m = ('0' + (date.getMonth() + 1)).slice(-2);
        const y = date.getFullYear();
        const h = ('0' + date.getHours()).slice(-2);
        const min = ('0' + date.getMinutes()).slice(-2);
        return `${d}/${m}/${y} ${h}:${min}`;
      }

      // Parse date function
      function parseDate(str) {
        if (!str) return null;
        const [datePart, timePart] = str.trim().split(' ');
        if (!datePart || !timePart) return null;

        const [year, month, day] = datePart.split('-');
        const [hours, minutes] = timePart.split(':');

        return new Date(year, month - 1, day, hours, minutes);
      }


      // Validate if a date is within a specific range
      function validateDateInRange(fromSelector, toSelector, rangeFromSelector, rangeToSelector) {
        const fromStr = $(fromSelector).val();
        const toStr = $(toSelector).val();
        const rangeFromStr = $(rangeFromSelector).val();
        const rangeToStr = $(rangeToSelector).val();
        let valid = true;

        $(fromSelector).removeClass('is-invalid');
        $(toSelector).removeClass('is-invalid');

        if (fromStr && toStr && rangeFromStr && rangeToStr) {
          const fromDate = parseDate(fromStr);
          const toDate = parseDate(toStr);
          const rangeFromDate = parseDate(rangeFromStr);
          const rangeToDate = parseDate(rangeToStr);

          // Ensure that the date is within the promotion range
          if (fromDate < rangeFromDate || fromDate > rangeToDate) {
            $(fromSelector).addClass('is-invalid');
            valid = false;
          }

          if (toDate < rangeFromDate || toDate > rangeToDate) {
            $(toSelector).addClass('is-invalid');
            valid = false;
          }

          if (toDate < fromDate) {
            $(toSelector).addClass('is-invalid');
            valid = false;
          }
        }

        return valid;
      }

      // Apply inline validation on change
      $('#valid_from, #valid_to').on('change', function() {
        validateDateInRange('#valid_from', '#valid_to', '#promotion_start_from', '#promotion_end_to');
      });

      $('#promotion_start_from, #promotion_end_to').on('change', function() {
        validateDateInRange('#valid_from', '#valid_to', '#promotion_start_from', '#promotion_end_to');
      });

      // Add custom validator method to jQuery Validator
      $.validator.addMethod('greaterThanOrEqual', function(value, element, param) {
        const from = $(param).val();
        if (!value || !from) return true;
        return parseDate(value) >= parseDate(from);
      }, 'End date must be same or after start date');

      // Add custom validation rule for valid_from and valid_to
      $.validator.addMethod('validDateRange', function(value, element) {
        const isValid = validateDateInRange('#valid_from', '#valid_to', '#promotion_start_from',
          '#promotion_end_to');
        return isValid;
      }, 'Valid dates must be within the promotion range');


      $.validator.addMethod('alphanumericUppercase', function(value, element) {
        return this.optional(element) || /^[A-Z0-9]+$/.test(value);
      }, 'Only uppercase letters and numbers allowed');

      $.validator.addMethod('validAmount', function(value, element) {
        return this.optional(element) || /^\d+(\.\d{1,2})?$/.test(value);
      }, 'Please enter a valid amount with up to 2 decimal places');

      // Set validation rules
      $('#promotionForm').validate({
        rules: {
          name: {
            required: true,
            maxlength: 255
          },
          promotion_start_from: {
            required: true,
            date: true
          },
          promotion_end_to: {
            required: true,
            date: true,
            greaterThanOrEqual: '#promotion_start_from'
          },
          description: {
            maxlength: 500
          },
          promotion_mode: {
            required: true
          },
          product_id: {
            required: true
          },
          product_variant_id: {
            required: true,
            minlength: 1
          },
          type: {
            required: true
          },
          discount_amount: {
            required: true,
            validAmount: true,
            min: 0.01,
            max: function() {
              return $('#type').val() === 'Percentage' ? 100 : 999999999;
            }
          },
          // valid_from: {
          //   required: true,
          //   date: true
          // },
          // valid_to: {
          //   required: true,
          //   date: true,
          //   greaterThanOrEqual: '#valid_from'
          // }
        },
        messages: {
          name: {
            required: 'Please enter a name',
            maxlength: 'Name cannot be more than 255 characters'
          },
          promotion_start_from: {
            required: 'Please enter a promotion start date',
            date: 'Please enter a valid date'
          },
          promotion_end_to: {
            required: 'Please enter a promotion end date',
            date: 'Please enter a valid date',
            greaterThanOrEqual: 'End date must be after the start date'
          },
          description: {
            maxlength: 'Description cannot exceed 500 characters'
          },
          promotion_mode: {
            required: 'Please select a promotion mode'
          },
          product_id: {
            required: 'Please select a product'
          },
          product_variant_id: {
            required: 'Please select at least one product variant',
            minlength: 'Please select at least one product variant'
          },
          type: {
            required: 'Please select a promotion type'
          },
          discount_amount: {
            required: 'Please enter a discount amount',
            validAmount: 'Please enter a valid amount',
            min: 'The minimum discount is {0}',
            max: function() {
              return $('#type').val() === 'Percentage' ? 'The max discount percentage is 100%' :
                'The max flat discount is 999,999,999';
            }
          },
          // valid_from: {
          //   required: 'Please enter a valid from date',
          //   date: 'Please enter a valid date'
          // },
          // valid_to: {
          //   required: 'Please enter a valid to date',
          //   date: 'Please enter a valid date',
          //   greaterThanOrEqual: 'Valid To must be the same or after Valid From'
          // }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback
          }
        },
        highlight: function(element) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid").addClass("is-valid");
        },

        // AJAX submit
        submitHandler: function(form) {
          const formID = '{{ Hashids::encode($promotion->id ?? '') }}';
          let url = "{{ route('admin.promotion.store') }}";

          if (formID) {
            url = `{{ route('admin.promotion.update', ':id') }}`.replace(':id', formID);
          }

          $.ajax({
            type: "POST",
            url: url,
            data: $(form).serialize(),
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
