@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$payment_gateway->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.payments')" :formId="'payment_gatewayForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Gateway Name</label>
          <input class="form-control" type="text" name="gatewayname" id="gatewayname"
            value="{{ $payment_gateway->gateway_name }}" readonly>
          <div id="gatewayname-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Gateway Mode</label>
          <input class="form-control" type="text" name="gatewaymode" id="gatewaymode"
            value="{{ $payment_gateway->gateway_mode }}" readonly>
          <div id="gatewaymode-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Gateway Key </label>
          <input class="form-control" type="text" name="gatewaykey" id="gatewaykey"
            value="{{ $payment_gateway->gateway_key }}">
          <div id="gatewaykey-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Gateway Secret </label>
          <input class="form-control" type="text" name="gatewaysecret" id="gatewaysecret"
            value="{{ $payment_gateway->gateway_secret }}">
          <div id="gatewaysecret-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Gateway Other Info </label>
          <input class="form-control" type="text" name="other_info" id="other_info"
            value="{{ $payment_gateway->gateway_other }}">
          <div id="other_info-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $payment_gateway->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $payment_gateway->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#payment_gatewayForm').validate({
        rules: {
          gatewayname: {
            required: true,
            maxlength: 30
          },
          gatewaymode: {
            required: true,
            maxlength: 30
          },
          gatewaykey: {
            // required: true,
            maxlength: 255
          },
          gatewaysecret: {
            // required: true,
            maxlength: 255
          },
          status: {
            required: true
          }
        },
        messages: {
          gatewayname: {
            required: "{{ __('validation.required', ['attribute' => 'Gateway Name']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Gateway Name', 'max' => '30']) }}"
          },
          gatewaymode: {
            required: "{{ __('validation.required', ['attribute' => 'Gateway Mode']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Gateway Mode', 'max' => '30']) }}"
          },
          gatewaykey: {
            required: "{{ __('validation.required', ['attribute' => 'Gateway Key']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Gateway Key', 'max' => '255']) }}"
          },
          gatewaysecret: {
            required: "{{ __('validation.required', ['attribute' => 'Gateway Secret']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Gateway Secret', 'max' => '255']) }}"
          },
          status: {
            required: "{{ __('validation.required', ['attribute' => 'Status']) }}"
          }
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
        submitHandler: function(form) {
          let formID = '{{ Hashids::encode($payment_gateway->id ?? '') }}'
          let url = "{{ route('admin.payments.store') }}"
          if (formID)
            url = `{{ route('admin.payments.update', ':id') }}`.replace(':id', formID);

          $.ajax({
            type: "POST",
            url: url,
            data: $('#payment_gatewayForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid')
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#payment_gatewayForm')[0].reset();
                }
              } else {
                swalNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              swalNotify("Error!", error.responseJSON.message, "error");
            }
          })
        }
      });
    });
  </script>
@endsection
