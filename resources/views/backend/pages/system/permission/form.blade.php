@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$permission->id ? [0, 2] : [0]" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.permissions')" :formId="'permissionForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Permission Name</label>
          <input class="form-control only-alphabets" type="text" name="permissionname" id="permissionname"
            value="{{ $permission->name }}">
          <div id="permissionname-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Slug </label>
          <input class="form-control lowercase-slug" type="text" name="permissionslug" id="permissionslug"
            value="{{ $permission->slug }}">
          <div id="permissionslug-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $permission->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $permission->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <label for="password" class="form-label">Description </label>
        <textarea class="form-control" rows="3" name="description" id="description"> {{ $permission->description }}</textarea>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script>
    let formID = '{{ Hashids::encode($permission->id ?? '') }}'
    $(document).ready(function() {
      $('#permissionForm').validate({
        rules: {
          permissionname: {
            required: true,
            maxlength: 30
          },
          permissionslug: {
            required: true,
            maxlength: 30
          },
          status: {
            required: true,
          }
        },
        messages: {
          permissionname: {
            required: "{{ __('validation.required', ['attribute' => 'Permission Name']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Permission Name', 'max' => 30]) }}"
          },
          permissionslug: {
            required: "{{ __('validation.required', ['attribute' => 'Permission Slug']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Permission Slug', 'max' => 30]) }}"
          },
          status: {
            required: "{{ __('validation.required', ['attribute' => 'Status']) }}",
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
          let url = "{{ route('admin.permissions.store') }}"
          if (formID)
            url = `{{ route('admin.permissions.update', ':id') }}`.replace(':id', formID);

          $.ajax({
            type: "POST",
            url: url,
            data: $('#permissionForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#permissionForm')[0].reset();
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

    $('#permissionname').on('input', function() {
      if (formID) return;
      var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-');
      $('#permissionslug').val(slug);
    });
  </script>
@endsection
