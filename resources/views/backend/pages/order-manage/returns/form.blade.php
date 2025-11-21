@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$order_return->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.return-requests')" :formId="'order_returnForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 ">
          <label class="form-label">Order Number</label>
          <input class="form-control" type="text" value="{{ $order_return->order->order_number }}" readonly>
          <a href="{{ route('admin.orders.edit', ['id' => Hashids::encode($order_return->order->id)]) }}"
            target="blank">View Order
            Details</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 ">
          <label class="form-label">Customer Request</label>
          <input class="form-control" type="text" value="{{ ucfirst($order_return->type) }}" readonly>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 ">
          <label class="form-label">Requested On</label>
          <input class="form-control" type="text" value="{{ convertDateTimeHours($order_return->requested_at) ?? '' }}"
            readonly>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <label for="password" class="form-label">Reason Description</label>
        <textarea class="form-control" rows="3" name="description" id="description" readonly>{{ $order_return->reason ?? '' }}</textarea>
      </div>
      <div class="col-md-3">
        <div class="mb-3 not-required">
          <label class="form-label">Current Order Status </label>
          <input class="form-control" type="text"
            value="{{ getStatuses()[$order_return->order->order_status] ?? 'Unknown' }}" readonly>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label class="form-label">Process Request</label>
          <select name="status" id="div_status" class="form-select">
            <option value="">Select</option>
            <option value="{{ Hashids::encode(1) }}" {{ $order_return->status === 'approved' ? 'selected' : '' }}>Approved
            </option>
            <option value="{{ Hashids::encode(0) }}" {{ $order_return->status === 'declined' ? 'selected' : '' }}>Decline
            </option>
          </select>
          <div id="status"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 not-required">
          <label for="password" class="form-label">Reason Description</label>
          <textarea class="form-control" rows="3" name="admin_reason" id="admin_reason">{{ $order_return->admin_response ?? '' }}</textarea>
          <div id="admin_reason-error" class="error"></div>
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>

  <script>
    $(document).ready(function() {
      $('#order_returnForm').validate({
        rules: {
          status: {
            required: true
          },
        },
        messages: {
          status: {
            required: "{{ __('validation.required', ['attribute' => 'Process Request']) }}"
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
        submitHandler: function(form) {
          let formID = '{{ Hashids::encode($order_return->id ?? '') }}'
          $.ajax({
            type: "POST",
            url: `{{ route('admin.return-requests.update', ':id') }}`.replace(':id', formID),
            data: {
              _token: '{{ csrf_token() }}',
              choice: $('#div_status').val(),
              admin_reason: $('#admin_reason').val(),
            },
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#order_returnForm')[0].reset();
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

      $('#div_status').on('change', function() {
        if ($(this).val() == '{{ Hashids::encode(1) }}') {
          $('#admin_reason').removeClass('required');
        } else {
          $('#admin_reason').addClass('required');
        }
      });
    });
  </script>
@endsection
