@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$state->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.states')" :formId="'locationForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Country</label>
          <select class="form-select select2" name="country" id="country">
            <option value="">Select Country</option>
            @foreach ($countries as $country)
              <option value="{{ Hashids::encode($country['id']) }}"
                {{ $state->country_id == $country['id'] ? 'selected' : '' }}>
                {{ $country['name'] }}</option>
            @endforeach
          </select>
          <div id="country-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">State Name</label>
          <input class="form-control only-alphabet-unicode" type="text" name="statename" id="statename"
            placeholder="Enter State Name" value="{{ $state->name }}">
          <div id="statename-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $state->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $state->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
    </div>
  </x-form-card>
  <!-- end row -->
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2();
      $('#locationForm').validate({
        rules: {
          statename: {
            required: true,
            minlength: 3,
            maxlength: 100,
          },
          country: {
            required: true,
          },
          status: {
            required: true,
          }
        },
        messages: {
          statename: {
            required: "{{ __('validation.required', ['attribute' => 'State Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'State Name', 'min' => 3]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'State Name', 'max' => 100]) }}",
          },
          country: {
            required: "{{ __('validation.required', ['attribute' => 'Country']) }}",
          },
          status: {
            required: "{{ __('validation.required', ['attribute' => 'Status']) }}",
          }
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
          let formID = '{{ Hashids::encode($state->id ?? '') }}'
          let url = "{{ route('admin.states.store') }}"
          if (formID)
            url = `{{ route('admin.states.update', ':id') }}`.replace(':id', formID);
          $.ajax({
            type: "POST",
            url: url,
            data: $('#locationForm').serialize(),
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
    });

    $(".select2").on("change", function() {
      $(this).valid();
    });
  </script>
@endsection
