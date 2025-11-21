@extends('backend.layouts.app')

@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$customer->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.customers')" :formId="'customerForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">First Name</label>
          <input class="form-control only-alphabet-symbols" type="text" name="firstname" id="firstname"
            value="{{ $customer->first_name }}">
          <div id="firstname-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Last Name</label>
          <input class="form-control only-alphabet-symbols" type="text" name="lastname" id="lastname"
            value="{{ $customer->last_name }}">
          <div id="lastname-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Email</label>
          <input class="form-control" type="email" name="customeremail" id="customeremail"
            value="{{ $customer->email }}" readonly>
          <div id="customeremail-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <x-phone-number :required="false" :previousValue="$customer->phone" :name="'customerphone'" :id="'customerphone'" />
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status</label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $customer->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="2" {{ $customer->status === 2 ? 'selected' : '' }}>Revoked</option>
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

      $('#customerForm').validate({
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
          customeremail: {
            required: true,
            email: true,
            maxlength: 100
          },
          customerphone: {
            validPhone: true // Custom validation method from PhoneNumber Component
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
          customeremail: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email Format']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Email', 'max' => 100]) }}"
          },
          customerphone: {
            minlength: "{{ __('validation.mindigits', ['attribute' => 'Phone', 'min' => 10]) }}"
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
          let formID = "{{ Hashids::encode($customer->id ?? '') }}"
          let url = "{{ route('admin.customers.store') }}"
          if (formID)
            url = `{{ route('admin.customers.update', ':id') }}`.replace(':id', formID);
          $.ajax({
            type: "POST",
            url: url,
            data: $('#customerForm').serialize(),
            success: function(response) {
              if (response.success) {
                $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
                swalNotify("Success!", response.message, "success");
                if (!formID) {
                  form.reset();
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
