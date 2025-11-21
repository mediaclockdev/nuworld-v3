@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$inventoryValue->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.inventory')" :formId="'inventoryValueForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="product_id" class="form-label">Product</label>
          <input type="text" class="form-control" readonly value="{{ $inventoryValue->product->name ?? '' }}">
          <div id="product_id-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="product_id" class="form-label">Variant Name</label>
          <input type="text" class="form-control" readonly value="{{ $inventoryValue->variant->name ?? '' }}">
          <div id="product_variant_id-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="stock" class="form-label">Stock</label>
          <input type="text" name="stock" id="stock" class="form-control only-integers"
            value="{{ $inventoryValue->quantity ?? 0 }}">
          <div class="error-container" id="stock-error"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="threshold" class="form-label">Threshold <i class="ri-information-line ms-1" data-bs-toggle="tooltip"
              data-bs-placement="bottom" data-bs-title="Will notify when stock reaches this value"></i></label>
          <input type="text" name="threshold" id="threshold" class="form-control only-integer"
            value="{{ $inventoryValue->threshold ?? 0 }}">
          <div class="error-container" id="threshold-error"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="max_selling_quantity" class="form-label">Max Selling Quantity <i class="ri-information-line ms-1"
              data-bs-toggle="tooltip" data-bs-placement="bottom"
              data-bs-title="Keep it 0 if no purchase limit"></i></label>
          <input type="text" name="max_selling_quantity" id="max_selling_quantity" class="form-control only-integers"
            value="{{ $inventoryValue->max_selling_quantity ?? 0 }}">
          <div class="error-container" id="max_selling_quantity-error"></div>
        </div>
      </div>
    </div>
  </x-form-card>
  <div class="card card-h-100">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title mb-0">Inventory Stock History</h4>
    </div>
    <div class="card-body">
      <livewire:inventory-manage.inventory-history-table :inventoryValue="$inventoryValue" />
    </div>
  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $.validator.addMethod('lessThanStock', function(value, element, param) {
      var stock = parseFloat($('#stock').val()) || 0;
      var val = parseFloat(value) || 0;
      return this.optional(element) || val <= stock;
    }, 'This value must be less than or equal to stock.');

    $('#inventoryValueForm').validate({
      rules: {
        'stock': {
          required: true,
          min: 0,
          max: 999999
        },
        'threshold': {
          required: true,
          min: 0,
          max: 999999,
          lessThanStock: true
        },
        'max_selling_quantity': {
          required: true,
          max: 999999,
          lessThanStock: true
        },
      },
      messages: {
        'stock': {
          required: "{{ __('validation.required', ['attribute' => 'Stock']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Stock', 'min' => 0]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Stock', 'max' => 999999]) }}"
        },
        'threshold': {
          required: "{{ __('validation.required', ['attribute' => 'Threshold']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Threshold', 'min' => 0]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Threshold', 'max' => 999999]) }}"
        },
        'max_selling_quantity': {
          required: "{{ __('validation.required', ['attribute' => 'Max Selling Quantity']) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Max Selling Quantity', 'max' => 999999]) }}",
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
        let formID = '{{ Hashids::encode($inventoryValue->id ?? '') }}'
        let url = "{{ route('admin.inventory.store') }}"
        if (formID)
          url = `{{ route('admin.inventory.update', ':id') }}`.replace(':id', formID);
        $.ajax({
          type: "POST",
          url: url,
          data: $('#inventoryValueForm').serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
              }
              console.log(response);

              Livewire.dispatch('refreshComponent');
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
  </script>
@endsection
