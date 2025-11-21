@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$order->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.shipment-management')" :formId="'shipmentForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="product_id" class="form-label">Order No</label>
          <input type="text" class="form-control" readonly value="{{ $order->order_number ?? '' }}">
          <div id="order_number-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        {{-- @dd($shippingStatus); --}}
        <div class="mb-3 required">
          <label for="order_status" class="form-label">Order Status</label>
          <select name="order_status" id="order_status" class="form-select">
            @foreach ($shippingStatus as $key => $status)
              @if ($key > 0)
                <option value="{{ Hashids::encode($key) }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                  {{ $status }}
                </option>
              @endif
            @endforeach
          </select>
          <div id="order_status"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Scheduled Date</label>
          <input type="text" class="form-control datepicker" name="scheduled_date" id="scheduled_date"
            value="{{ $scheduledDate ? $scheduledDate : now()->format('d/m/Y') }}" autocomplete="new-date">
          <div id="scheduled_date-error-container" class="invalid-feedback"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Scheduled Time</label>
          <input type="time" class="form-control" name="scheduled_time" id="scheduled_time"
            value="{{ $scheduledTime ? $scheduledTime : now()->format('H:i') }}">

          <div id="scheduled_time-error-container" class="invalid-feedback"></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Description</label>
          <textarea rows="3" class="form-control" name="description" id="description" placeholder="Enter Description"></textarea>
          <div id="description-error-container"></div>
        </div>
      </div>
    </div>
  </x-form-card>

  <div class="card card-h-100">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title mb-0">Order Status History</h4>
    </div>
    <div class="card-body">
      <livewire:order-manage.order-history-table :order="$order" />
    </div>
  </div>

  {{-- <!-- end row --> --}}
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
    $('#order_status').select2({
      'placeholder': 'Select Status',
    });
    $('.datepicker').datepicker({
      dateFormat: "dd/mm/yy",
      changeMonth: true,
      changeYear: true,
      onClose: function() {
        $(this).valid();
      }
    });
    $('.datepicker').on('keydown paste', function(e) {
      e.preventDefault();
    });

    $.validator.addMethod("dateFormat", function(value, element) {
      return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
    }, "Please enter a valid date in the format dd/mm/yyyy.");
    $('#shipmentForm').validate({
      rules: {
        scheduled_date: {
          required: true,
          // date: true,
          dateFormat: true
        },
        'scheduled_time': {
          required: true,
        },
      },
      messages: {
        scheduled_date: {
          required: "__('validation.required', ['attribute' => 'Schedule Date'])",
        },
        scheduled_time: {
          required: "__('validation.required', ['attribute' => 'Schedule Time'])",
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
        let formID = '{{ Hashids::encode($order->id ?? '') }}'
        if (formID)
          url = `{{ route('admin.shipment-management.update', ':id') }}`.replace(':id', formID);
        $.ajax({
          type: "POST",
          url: url,
          data: $('#shipmentForm').serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
                $(form).find(".select2").val(null).trigger("change.select2");
              }
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
