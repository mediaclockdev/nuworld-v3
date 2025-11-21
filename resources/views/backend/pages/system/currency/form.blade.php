@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$currency->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.currencies')" :formId="'currencyForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Name</label>
          <select class="form-select select2" name="currencyname" id="currencyname">
            <option value="">Select Currency</option>
            @foreach ($currencies as $cur)
              <option value="{{ $cur['name'] }}" data-code="{{ $cur['code'] }}" data-symbol="{{ $cur['symbolNative'] }}"
                {{ $cur['code'] == ($currency->code ?? 0) ? 'selected' : '' }}>
                {{ $cur['name'] }} ({{ $cur['code'] }})
              </option>
            @endforeach
          </select>

          <div id="currencyname-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Icon </label>
          <input class="form-control" type="text" name="currencysymbol" id="currencysymbol" readonly
            value="{{ $currency->symbol ?? '' }}">
          <div id="currencysymbol-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Code </label>
          <input class="form-control" type="text" name="currencycode" id="currencycode" readonly
            value="{{ $currency->code ?? '' }}">
          <div id="currencycode-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Position </label>
          <select class="form-select" name="currencyposition" id="currencyposition">
            <option value="left" {{ $currency->position == 'Left' ? 'selected' : '' }}>Left</option>
            <option value="right" {{ $currency->position == 'Right' ? 'selected' : '' }}>Right</option>
          </select>
          <div id="currencyposition-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Decimal Places </label>
          <input class="form-control only-numbers" type="text" name="decimal_places" id="decimal_places"
            value="{{ $currency->decimal_places ?? 2 }}">
          <div id="decimal_places-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status. </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $currency->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $currency->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
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
    $(document).ready(function() {

      $('.select2').select2({
        placeholder: "Select Currency",
      });

      $('#currencyname').change(function() {
        var selectedOption = $(this).find('option:selected');

        $('#currencysymbol').val(selectedOption.data('symbol'));
        $('#currencycode').val(selectedOption.data('code'));
      })

      $('#currencyForm').validate({
        rules: {
          currencyposition: {
            required: true
          },
          decimal_places: {
            required: true,
            number: true,
            min: 0,
            max: 6
          },
        },
        messages: {
          currencyposition: {
            required: "{{ __('validation.required', ['attribute' => 'Position']) }}"
          },
          decimal_places: {
            required: "{{ __('validation.required', ['attribute' => 'Decimal Places']) }}",
            number: "{{ __('validation.numeric', ['attribute' => 'Decimal Places']) }}",
            min: "{{ __('validation.minvalue', ['attribute' => 'Decimal Places', 'min' => 0]) }}",
            max: "{{ __('validation.maxvalue', ['attribute' => 'Decimal Places', 'max' => 6]) }}"
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
          let formID = '{{ Hashids::encode($currency->id ?? '') }}'
          let url = "{{ route('admin.currencies.store') }}"
          if (formID)
            url = `{{ route('admin.currencies.update', ':id') }}`.replace(':id', formID);
          $.ajax({
            type: "POST",
            url: url,
            data: $('#currencyForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#currencyForm')[0].reset();
                  $('#currencyname').val('').trigger('change');
                }
              } else {
                swalNotify("Oops!", response.message, "error");
              }
            },
            error: function(error) {
              // console.log(error);
              swalNotify("Error!", error.responseJSON.message, "error");
            }
          })
        }
      });
    });
  </script>
@endsection
