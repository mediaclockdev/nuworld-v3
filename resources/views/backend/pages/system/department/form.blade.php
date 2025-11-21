@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$department->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.departments')" :formId="'departmentForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Department Name</label>
          <input class="form-control" type="text" name="departmentname" id="departmentname" value="{{ $department->name }}">
          <div id="departmentname-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $department->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $department->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <label for="password" class="form-label">Description </label>
        <textarea class="form-control" rows="3" name="description" id="description"> {{ $department->description }}</textarea>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>

  <script>
    $(document).ready(function() {
      $('#departmentForm').validate({
        rules: {
          departmentname: {
            required: true,
          },
          status: {
            required: true
          },
        },
        messages: {
          departmentname: {
            required: "{{ __('validation.required', ['attribute' => 'Department Name']) }}",
          },
          status: {
            required: "{{ __('validation.required', ['attribute' => 'Status']) }}"
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
          let formID = '{{ Hashids::encode($department->id ?? '') }}'
          let url = "{{ route('admin.departments.store') }}"
          if (formID)
            url = `{{ route('admin.departments.update', ':id') }}`.replace(':id', formID);

          $.ajax({
            type: "POST",
            url: url,
            data: $('#departmentForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#departmentForm')[0].reset();
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
