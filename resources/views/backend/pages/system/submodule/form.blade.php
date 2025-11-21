@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$submodule->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.submodules')" :formId="'submoduleForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Module </label>
          <select class="form-select select2" name="parentmodule" id="parentmodule">
            <option value="">Select Module</option>
            @foreach ($modules as $module)
              <option value="{{ Hashids::encode($module->id) }}"
                {{ $module->id == $submodule->module_id ? 'selected' : '' }}>
                {{ $module->name }}
              </option>
            @endforeach
          </select>
          <div id="parentmodule-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Submodule</label>
          <input class="form-control only-alphabet-numbers-symbols" type="text" name="submodulename" id="submodulename"
            value="{{ $submodule->name }}">
          <div id="submodulename-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Route Name </label>
          <input class="form-control lowercase-slug" type="text" name="submoduleslug" id="submoduleslug"
            value="{{ $submodule->route_name }}">
          <div id="submoduleslug-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Sequence </label>
          <input class="form-control only-integers" type="text" name="submodulesequence" id="submodulesequence"
            value="{{ $submodule->sequence ?? 1 }}">
          <div id="submodulesequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label class="form-label">Submodule Icon </label>
          <x-remix-icon-select name="submoduleicon" id="submoduleicon" selected="{{ $submodule->icon }}" />
          <div id="submoduleicon-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $submodule->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $submodule->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <h5>Permissions </h5>
        <div class="pl-2">
          @foreach ($permissions as $permission)
            <div class="form-check form-check-inline form-checkbox-success">
              <input class="form-check-input" type="checkbox"
                name="permissions['{{ Hashids::encode($permission->id) }}']" id="permissions-{{ $permission->name }}"
                value="{{ $permission->id }}"
                {{ $submodule->id ? (in_array($permission->id, $submodulePermissions) ? 'checked' : '') : '' }}>
              <label class="form-check-label" for="permissions-{{ $permission->name }}">{{ $permission->name }}</label>
            </div>
          @endforeach
          <div id="permissions-error-container"></div>
        </div>
      </div>

      <div class="col-md-12 mb-3">
        <label for="password" class="form-label">Description </label>
        <textarea class="form-control" rows="3" name="description" id="description">{{ $submodule->description }}</textarea>
      </div>
    </div>
  </x-form-card>
  <!-- end row -->
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/select2-option-icon.js') }}"></script>

  <script>
    $('#parentmodule').select2({
      placeholder: 'Select Module',
    });
    $('#submoduleForm').validate({
      rules: {
        parentmodule: {
          required: true
        },
        submodulename: {
          required: true,
          minlength: 3
        },
        submodulesequence: {
          required: true,
          number: true,
          min: 1,
          max: 127
        },
        status: {
          required: true
        }
      },
      messages: {
        parentmodule: {
          required: "{{ __('validation.required', ['attribute' => 'Module']) }}"
        },
        submodulename: {
          required: "{{ __('validation.required', ['attribute' => 'Submodule']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Submodule', 'min' => 3]) }}"
        },
        submodulesequence: {
          required: "{{ __('validation.required', ['attribute' => 'Submodule Sequence']) }}",
          number: "{{ __('validation.numeric', ['attribute' => 'Sequence']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Sequence', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => 127]) }}"
        },
        status: {
          required: "{{ __('validation.required', ['attribute' => 'Status']) }}"
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
        let formID = '{{ Hashids::encode($submodule->id ?? '') }}'
        let url = "{{ route('admin.submodules.store') }}"
        if (formID)
          url = `{{ route('admin.submodules.update', ':id') }}`.replace(':id', formID);

        $.ajax({
          type: "POST",
          url: url,
          data: $('#submoduleForm').serialize(),
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
