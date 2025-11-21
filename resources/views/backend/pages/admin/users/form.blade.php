@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$adminUser['id'] ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.users')" :formId="'userForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">First Name</label>
          <input class="form-control only-alphabet-symbols" type="text" name="firstname" id="firstname"
            value="{{ $adminUser['first_name'] }}">
          <div id="firstname-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Middle Name</label>
          <input class="form-control only-alphabet-symbols" type="text" name="middlename" id="middlename"
            value="{{ $adminUser['middle_name'] }}">
          <div id="middlename-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Last Name</label>
          <input class="form-control only-alphabet-symbols" type="text" name="lastname" id="lastname"
            value="{{ $adminUser['last_name'] }}">
          <div id="lastname-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="adminemail" id="adminemail" value="{{ $adminUser['email'] }}">
          <div id="adminemail-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <x-phone-number :required="true" :previousValue="$adminUser['phone']" :name="'adminphone'" :id="'adminphone'" />
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status</label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $adminUser['status'] === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $adminUser['status'] === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Select Role</label>
          <select class="form-select select2" name="adminrole[]" id="adminrole" multiple
            style="line-height: 41.6px !important;">
            @foreach ($roles as $role)
              <option value="{{ Hashids::encode($role->id) }}"
                {{ in_array($role->id, $adminUser['role'] ?? []) ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          <div id="adminrole-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label">Select Department</label>
          <select class="form-select select2" name="department" id="department">
            <option value="">Select Department</option>
            @foreach ($departments as $department)
              <option value="{{ Hashids::encode($department->id ?? null) }}"
                {{ $department->id == ($adminUser['department'] ?? 0) ? 'selected' : '' }}>
                {{ $department->name }}
              </option>
            @endforeach
          </select>
          <div id="department-error-container"></div>
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
      $('.select2').select2();

      let isUpdating = false;

      $('#adminrole').on('change', function() {
        if (isUpdating) return; // Prevent recursion

        var selectData = $(this).select2('data'); // Get selected options
        var isSuperAdminSelected = selectData.some(option => option.text.trim() === 'Super Admin');

        if (isSuperAdminSelected) {
          // Find the "Super Admin" option
          var superAdminOption = selectData.find(option => option.text.trim() === 'Super Admin');
          swalNotify("Great!", "You have selected \"Super Admin\". No other options can be selected.", "success");
          // Prevent further selection modifications during this operation
          isUpdating = true;

          // Keep only "Super Admin" selected
          $(this).val([superAdminOption.id]).trigger('change');

          isUpdating = false; // Reset the flag
        }
      });

      $('#userForm').validate({
        ignore: [],
        rules: {
          firstname: {
            required: true,
            minlength: 3,
            maxlength: 100
          },
          middlename: {
            required: false,
            maxlength: 100
          },
          lastname: {
            required: false,
            maxlength: 100
          },
          adminemail: {
            required: true,
            email: true,
            maxlength: 100
          },
          adminphone: {
            required: true,
            validPhone: true // Custom validation method from PhoneNumber Component
          },
          "adminrole[]": {
            required: true
          }
        },
        messages: {
          firstname: {
            required: "{{ __('validation.required', ['attribute' => 'First Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'First Name', 'min' => 3]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'First Name', 'max' => 100]) }}"
          },
          middlename: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Middle Name', 'max' => 100]) }}"
          },
          lastname: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Last Name', 'max' => 100]) }}"
          },
          adminemail: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email Format']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Email', 'max' => 100]) }}"
          },
          adminphone: {
            required: "{{ __('validation.required', ['attribute' => 'Phone']) }}",
            minlength: "{{ __('validation.mindigits', ['attribute' => 'Phone', 'min' => 10]) }}"
          },
          "adminrole[]": {
            required: "{{ __('validation.required', ['attribute' => 'At Least One Role']) }}"
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
          let formID = "{{ Hashids::encode($adminUser['id'] ?? '') }}"
          let url = "{{ route('admin.users.store') }}"
          if (formID)
            url = `{{ route('admin.users.update', ':id') }}`.replace(':id', formID);
          $.ajax({
            type: "POST",
            url: url,
            data: $('#userForm').serialize(),
            success: function(response) {
              if (response.success) {
                $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  form.reset();
                  $(form).find(".select2").val(null).trigger("change.select2");
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

    $(".select2").on("change", function() {
      $(this).valid();
    });
  </script>
@endsection
