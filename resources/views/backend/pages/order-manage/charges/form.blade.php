@extends('backend.layouts.app')

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$charge->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.charges')" :formId="'chargeForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Charge Name</label>
          <input class="form-control" type="text" name="name" id="name" value="{{ $charge->name }}">
          <div id="name-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Calculation method</label>
          <select name="calculation_method" id="calculation_method" class="form-select">
            <option value="fixed" {{ $charge->calculation_method === 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="percentage" {{ $charge->calculation_method === 'percentage' ? 'selected' : '' }}>Percentage
            </option>
            {{-- <option value="weight_based" {{ $charge->type === 'weight_based' ? 'selected' : '' }}>Weight Based</option>
            <option value="distance_based" {{ $charge->type === 'distance_based' ? 'selected' : '' }}>Distance Based
            </option> --}}
          </select>
          <div id="type-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Value</label>
          <input class="form-control only-numbers" type="text" name="charge_amount" id="charge_amount"
            value="{{ $charge->value }}">
          <div id="charge_amount-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Is Mandatory?</label>
          <select name="is_mandatory" id="is_mandatory" class="form-select">
            <option value="1" {{ $charge->is_mandatory ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$charge->is_mandatory ? 'selected' : '' }}>No (Optional)</option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Applicability</label>
          <input class="form-control" type="text" name="applicability" id="applicability"
            value="{{ $charge->applicability ?? '' }}" placeholder='e.g., "all_orders", "above_100"'>
          <div id="applicability-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status</label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $charge->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $charge->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Conditions (JSON)</label>
          <textarea class="form-control" name="conditions" id="conditions" rows="2" placeholder='e.g., {"min_order": 5000}'>{{ $charge->conditions ?? '' }}</textarea>
          <div id="conditions-error-container"></div>
        </div>
      </div>

    </div>
  </x-form-card>
@endsection

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script>
    $.validator.addMethod("validJson", function(value, element) {
      if (value.trim() === "") return true;
      try {
        JSON.parse(value);
        return true;
      } catch (e) {
        return false;
      }
    }, "Please enter a valid JSON string.");

    $('#chargeForm').validate({
      rules: {
        name: {
          required: true,
          maxlength: 100
        },
        calculation_method: {
          required: true
        },
        charge_amount: {
          required: true,
          number: true,
          min: 0
        },
        status: {
          required: true
        },
        is_mandatory: {
          required: true
        },
        applicability: {
          maxlength: 100
        },
        conditions: {
          maxlength: 1000,
          validJson: true
        }
      },
      messages: {
        name: {
          required: "{{ __('validation.required', ['attribute' => 'Charge Name']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Charge Name', 'max' => 100]) }}"
        },
        calculation_method: {
          required: "{{ __('validation.required', ['attribute' => 'Charge Type']) }}"
        },
        charge_amount: {
          required: "{{ __('validation.required', ['attribute' => 'Charge Amount']) }}",
          min: "Charge Amount must be zero or more."
        },
        is_mandatory: {
          required: "{{ __('validation.required', ['attribute' => 'Mandatory']) }}"
        },
        status: {
          required: "{{ __('validation.required', ['attribute' => 'Status']) }}"
        },
        applicability: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Applicability', 'max' => 100]) }}"
        },
        conditions: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Conditions', 'max' => 1000]) }}",
          validJson: "{{ __('validation.json', ['attribute' => 'Conditions']) }}"
        }
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        const container = $('#' + element.attr('id') + '-error-container');
        container.length ? error.appendTo(container) : error.insertAfter(element);
      },
      highlight: function(element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
      },
      unhighlight: function(element) {
        $(element).removeClass("is-invalid").addClass("is-valid");
      },
      submitHandler: function(form) {
        const formID = "{{ Hashids::encode($charge->id ?? '') }}";
        let url = "{{ route('admin.charges.store') }}";
        if (formID) url = "{{ route('admin.charges.update', ':id') }}".replace(':id', formID);

        $.ajax({
          type: "POST",
          url: url,
          data: $(form).serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) form.reset();
            } else {
              swalNotify("Oops!", response.message, "error");
            }
          },
          error: function(error) {
            swalNotify("Error!", error.responseJSON.message, "error");
          }
        });
      }
    });
  </script>
@endsection
