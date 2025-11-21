@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$module->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.modules')" :formId="'moduleForm'">
    <div class="row">
      <div class="col-md-3">
        <div class="mb-3 required">
          <label class="form-label">Name</label>
          <input class="form-control only-alphabet-numbers-symbols" type="text" name="modulename" id="modulename"
            value="{{ $module->name }}">
          <div id="modulename-error-container"></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label class="form-label">Icon </label>
          <x-remix-icon-select name="moduleicon" id="moduleicon" selected="{{ $module->icon }}" />
          <div id="moduleicon-error-container"></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label class="form-label">Sequence </label>
          <input class="form-control only-integers" type="text" name="modulesequence" id="modulesequence"
            value="{{ $module->sequence ?? 1 }}">
          <div id="modulesequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $module->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $module->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3">
          <label for="password" class="form-label">Description </label>
          <textarea class="form-control" rows="3" name="description" id="description"> {{ $module->description }}</textarea>
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
      $('#moduleForm').validate({
        rules: {
          modulename: {
            required: true,
            minlength: 3
          },
          modulesequence: {
            required: true,
            number: true,
            min: 1,
            max: 127
          },
          moduleicon: {
            required: true
          },
          status: {
            required: true
          }
        },
        messages: {
          modulename: {
            required: "{{ __('validation.required', ['attribute' => 'Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'Name', 'min' => 3]) }}"
          },
          modulesequence: {
            required: "{{ __('validation.required', ['attribute' => 'Sequence']) }}",
            number: "{{ __('validation.numeric', ['attribute' => 'Sequence']) }}",
            min: "{{ __('validation.minvalue', ['attribute' => 'Sequence', 'min' => 1]) }}",
            max: "{{ __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => 127]) }}"
          },
          moduleicon: {
            required: "{{ __('validation.required', ['attribute' => 'Icon']) }}"
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
          let formID = '{{ Hashids::encode($module->id ?? '') }}'
          let url = "{{ route('admin.modules.store') }}"
          if (formID)
            url = `{{ route('admin.modules.update', ':id') }}`.replace(':id', formID);

          $.ajax({
            type: "POST",
            url: url,
            data: $('#moduleForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                if (!formID) {
                  $('#moduleForm')[0].reset();
                  $('#moduleicon').val(null).trigger('change');
                }
                swalNotify("Success!", response.message, "success");
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
