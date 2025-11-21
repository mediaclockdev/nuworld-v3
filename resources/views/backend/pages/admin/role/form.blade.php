@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$role->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.roles')" :formId="'roleForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Role Name</label>
          <input class="form-control only-alphabet-numbers-symbols" type="text" name="rolename" id="rolename"
            value="{{ $role->name }}">
          <div id="rolename-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $role->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $role->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label for="password" class="form-label">Description </label>
          <textarea class="form-control" rows="3" name="description" id="description"
            placeholder="Role description (optional)">{{ $role->description }}</textarea>
          <div id="description-error-container"></div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title">Set Permissions for <span id="roleName">{{ $role->name ?? 'New Role' }}</span></h4>
          </div>
          <div class="card-body">
            <x-permission-list :modulesList="$modules" :checkedPermissions="$checkedPermissions" />
          </div>
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=' . time()) }}"></script>
  <script>
    $(document).ready(function() {
      $('#roleForm').validate({
        rules: {
          rolename: {
            required: true,
            minlength: 3,
            maxlength: 100
          },
          description: {
            maxlength: 1000
          }
        },
        messages: {
          rolename: {
            required: "{{ __('validation.required', ['attribute' => 'Role Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'Role Name', 'min' => 3]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Role Name', 'max' => 100]) }}"
          },
          description: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Description', 'max' => 1000]) }}"
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
          let formID = "{{ Hashids::encode($role->id ?? '') }}"
          let url = "{{ route('admin.roles.store') }}"
          if (formID)
            url = `{{ route('admin.roles.update', ':id') }}`.replace(':id', formID);
          $.ajax({
            type: "POST",
            url: url,
            data: $('#roleForm').serialize(),
            success: function(response) {
              if (response.success) {
                $('.is-valid').removeClass('is-valid');
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  $('#roleForm')[0].reset();
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
