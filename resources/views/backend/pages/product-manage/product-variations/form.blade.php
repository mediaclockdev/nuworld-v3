@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  @php $i = 0; @endphp
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$productVariation->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.product-variations')" :formId="'variationForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="product_id" class="form-label">Product</label>
          <select name="product_id" id="product_id" class="form-select select2">
            <option value="">None</option>
            @foreach ($products as $product)
              <option value="{{ Hashids::encode($product->id) }}" data-sku="{{ $product->sku }}"
                {{ $productVariation->product_id == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
              </option>
            @endforeach
          </select>
          <div id="product_id-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="variant_name" class="form-label">Variant Name</label>
          <input type="text" name="variant_name" id="variant_name" class="form-control uppercase-slug"
            value="{{ $productVariation->name ?? '' }}">
          <div id="variant_name-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sku" class="form-label">SKU</label>
          <input type="text" name="sku" id="sku" class="form-control uppercase-slug"
            value="{{ $productVariation->sku ?? '' }}">
          <div id="sku-error-container"></div>
        </div>
      </div>
      @foreach ($attributes as $attribute)
        <div class="col-md-3">
          <div class="mb-3">
            <label for="attribute_id_{{ $attribute->id }}" class="form-label">{{ $attribute->name }}</label>
            <select name="attribute_id[{{ Hashids::encode($attribute->id) }}]" id="attribute_id_{{ $attribute->id }}"
              class="form-select select2 attribute-select">
              <option value="">None</option>
              @foreach ($attributeValues->where('attribute_id', $attribute->id) as $value)
                <option value="{{ Hashids::encode($value->id) }}" data-value="{{ substr($value->value, 0, 3) }}"
                  data-name="{{ $value->value }}" {{ $productVariation->attribute_id == $value->id ? 'selected' : '' }}>
                  {{ $value->value }}
                </option>
              @endforeach
            </select>
            <div id="attribute_id-error-container"></div>
          </div>
        </div>
      @endforeach
      <div class="col-md-3">
        <div class="mb-3 required">
          <label for="regular_price" class="form-label">Regular Price</label>
          <input type="text" name="regular_price" id="regular_price" class="form-control only-numbers"
            value="{{ $productVariation->regular_price ?? 0 }}">
          <div id="regular_price-error-container"></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label for="sale_price" class="form-label">Sale Price</label>
          <input type="text" name="sale_price" id="sale_price" class="form-control only-numbers"
            value="{{ $productVariation->sale_price ?? 0 }}">
          <div id="sale_price-error-container"></div>
          <span class="text-muted" id="discount"></span>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label for="stock" class="form-label">Stock</label>
          <input type="text" name="stock" id="stock" class="form-control only-numbers"
            value="{{ $productVariation->stock ?? 0 }}">
          <div id="stock-error-container"></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label for="threshold" class="form-label">Threshold</label>
          <input type="text" name="threshold" id="threshold" class="form-control only-numbers"
            value="{{ $productVariation->threshold ?? 0 }}">
          <div id="discount-error-container"></div>
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $('#product_id').select2({
      'placeholder': 'Select Product',
    });

    updateSKU();

    $('#product_id').on('change', function() {
      var product_id = $(this).val();
      if (!product_id) {
        $('.attribute-select').val('').trigger('change');
      }
      updateSKU();
    });

    $('.attribute-select').on('change', function() {
      updateSKU();
    });

    function updateSKU() {
      let productSKU = $('#product_id').find('option:selected').data('sku') || '';
      let sku = productSKU;
      let variantName = $('#product_id').find('option:selected').text().trim() || '';

      $('.attribute-select').each(function() {
        let selectedValue = $(this).find('option:selected').data('value');
        let selectedName = $(this).find('option:selected').data('name');
        if (selectedValue && selectedValue !== 'None') {
          sku += '-' + selectedValue;
          if (selectedName) {
            variantName += ' ' + selectedName;
          }
        }
      });

      $('#sku').val(sku.toUpperCase());
      $('#variant_name').val(variantName.trim());
    }

    function updateDiscount() {
      var regularPrice = parseFloat($('#regular_price').val()) || 0;
      var salePrice = parseFloat($('#sale_price').val()) || 0;
      if (regularPrice > 0 && salePrice >= 0) {
        var discount = ((regularPrice - salePrice) / regularPrice) * 100;
        if (discount >= 0) {
          $('#discount').html('Discount: ' + discount.toFixed(2) + '%');
        } else {
          $('#discount').html('Discount: 0.00%');
        }
      } else {
        $('#discount').html('');
      }
    }

    $('#regular_price, #sale_price').on('input', function() {
      updateDiscount();
    });

    $.validator.addMethod('lessThanStock', function(value, element) {
      var stock = parseFloat($('#stock').val()) || 0;
      var threshold = parseFloat(value) || 0;
      return this.optional(element) || threshold < stock;
    }, 'Threshold must be less than stock.');

    $('#variationForm').validate({
      rules: {
        'product_id': {
          required: true
        },
        'variant_name': {
          required: true,
          maxlength: 255
        },
        'sku': {
          required: true,
          maxlength: 100
        },
        'regular_price': {
          required: true,
          min: 1,
          max: 999999
        },
        'sale_price': {
          required: true,
          min: 1,
          max: 999999
        },
        'stock': {
          required: true,
          min: 1,
          max: 999999
        },
        'threshold': {
          required: true,
          min: 1,
          max: 999999,
          lessThanStock: true
        }
      },
      messages: {
        'product_id': {
          required: "{{ __('validation.required', ['attribute' => 'Product']) }}"
        },
        'variant_name': {
          required: "{{ __('validation.required', ['attribute' => 'Variant Name']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Variant Name', 'max' => 255]) }}"
        },
        'sku': {
          required: "{{ __('validation.required', ['attribute' => 'SKU']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'SKU', 'max' => 100]) }}"
        },
        'regular_price': {
          required: "{{ __('validation.required', ['attribute' => 'Regular Price']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Regular Price', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Regular Price', 'max' => 999999]) }}"
        },
        'sale_price': {
          required: "{{ __('validation.required', ['attribute' => 'Sale Price']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Sale Price', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Sale Price', 'max' => 999999]) }}"
        },
        'stock': {
          required: "{{ __('validation.required', ['attribute' => 'Stock']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Stock', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Stock', 'max' => 999999]) }}"
        },
        'threshold': {
          required: "{{ __('validation.required', ['attribute' => 'Threshold']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Threshold', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Threshold', 'max' => 999999]) }}"
        },
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        let errorContainer = $(`#${element.attr('id')}-error-container`);
        // Check if element is a Select2
        if (element.hasClass("select2-hidden-accessible")) {
          let select2Container = element.next(".select2-container");

          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(select2Container); // Place after Select2 container
          }
        } else {
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback for other elements
          }
        }
      },
      highlight: function(element) {
        // Add is-invalid for Select2
        if ($(element).hasClass("select2-hidden-accessible")) {
          $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
        } else {
          $(element).addClass("is-invalid").removeClass("is-valid");
        }
      },
      unhighlight: function(element) {
        // Remove is-invalid for Select2
        if ($(element).hasClass("select2-hidden-accessible")) {
          $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
        } else {
          $(element).removeClass("is-invalid").addClass("is-valid");
        }
      },
      submitHandler: function(form) {
        let formID = '{{ Hashids::encode($productVariation->id ?? '') }}'
        let url = "{{ route('admin.product-variations.store') }}"
        if (formID)
          url = `{{ route('admin.product-variations.update', ':id') }}`.replace(':id', formID);

        $.ajax({
          type: "POST",
          url: url,
          data: $('#variationForm').serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
                $(form).find(".select2").val(null).trigger("change.select2");
              }
            } else {
              swalNotify("Oops!", response.message, "error");
            }
          },
          error: function(error) {
            console.log(error);
            swalNotify("Error!", error.responseJSON.message, "error");
          }
        })
      }
    });

    $(".select2").on("change", function() {
      $(this).valid();
    });
  </script>
@endsection
