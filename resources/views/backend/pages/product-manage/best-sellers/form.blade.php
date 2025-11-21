@extends('backend.layouts.app')

@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
  {{-- <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css" rel="stylesheet" /> --}}
@endsection
{{-- @php
  $selectedVariantIds = $base_seller;
  pd($selectedVariantIds);
@endphp --}}

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$base_seller->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.best-sellers')" :formId="'bestSellerForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="title" class="form-label">Product</label>
          <select name="product_id" id="product_id" class="form-select select2">
            <option value=""></option>
            @foreach ($products as $product)
              <option value="{{ Hashids::encode($product->id) }}"
                {{ $base_seller->product_id == $product->id ? 'selected' : '' }}>
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
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" id="description" placeholder="Enter Description"></textarea>
          <div id="description-error-container">{{ $base_seller->description ?? '' }}</div>
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
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    const selectedVariantIds = @json([$base_seller->product_variant_id]);
    // alert(selectedVariantId);
  </script>
  <script>
    $(document).ready(function() {
      // let selectedVariantIds = @json($selectedVariantIds ?? []);
      $('#product_id').select2({
        'placeholder': 'Select Product',
      });

      $('#product_variant_id').select2({
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


      // Set validation rules
      $('#bestSellerForm').validate({
        rules: {
          description: {
            maxlength: 500
          },
          product_id: {
            required: true
          },
          product_variant_id: {
            required: true,
            minlength: 1
          },

        },
        messages: {
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
          const formID = '{{ Hashids::encode($base_seller->id ?? '') }}';
          let url = "{{ route('admin.best-sellers.store') }}";

          if (formID) {
            url = `{{ route('admin.best-sellers.update', ':id') }}`.replace(':id', formID);
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
                  // $('#valid_from').val('');
                  // $('#valid_to').val('');
                  // $('#type').val('Percentage').trigger('change');
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
