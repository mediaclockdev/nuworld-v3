@extends('backend.layouts.app')
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$pinCode->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.pincode')" :formId="'pinCodeForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="title" class="form-label">Pincode  </label>
          <input type="text" name="code" id="code" class="form-control "
            value="{{ $pinCode->code ?? '' }}" >
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sequence" class="form-label">Estimate Days</label>
          <input type="text" name="estimate_days" id="estimate_days" class="form-control only-alphabet-numbers-symbols"
            value="{{ $pinCode->estimate_days ?? '' }}">
        </div>
      </div>

    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script>


    let formID = `{{ Hashids::encode($pinCode->id ?? '') }}`;

    /* $("#code").keypress(function(e) {
      var keyCode = e.keyCode || e.which;
      var character = String.fromCharCode(keyCode);
      if ($(this).val().length >= 6 && keyCode !== 8 && keyCode !== 46) {
        return false;
      }
    }); */

    $.validator.addMethod("noSpecialChars", function(value, element) {
      return this.optional(element) || /^[a-zA-Z0-9\- ]+$/.test(value);
    }, "Only letters, numbers, hyphens, and spaces are allowed.");

    $('#pinCodeForm').validate({
        rules: {
            code: {
                required: true,
                minlength: 3,
                maxlength: 15,
                noSpecialChars: true

            },
            estimate_days: {
                required: true,
                maxlength: 15
            },
        },
        messages: {
            code: {
                required: "{{ __('validation.required', ['attribute' => 'PinCode']) }}",
                minlength: "{{ __('validation.minlength', ['attribute' => 'Pincode', 'min' => '3']) }}",
                maxlength: "{{ __('validation.maxlength', ['attribute' => 'Pincode', 'max' => '15']) }}",
                noSpecialChars: "Only letters, numbers, hyphens and spaces are allowed."
            },
            estimate_days: {
                required: "{{ __('validation.required', ['attribute' => 'Estimate Days']) }}",
                maxlength: "{{ __('validation.maxlength', ['attribute' => 'Estimate Days', 'max' => 15]) }}"
            }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
            let errorContainer = $(`#${element.attr('id')}-error-container`);
            if (errorContainer.length) {
                error.appendTo(errorContainer);
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function(form) {
            let url = "{{ route('admin.pincode.store') }}";
            if (formID) {
                url = `{{ route('admin.pincode.update', ':id') }}`.replace(':id', formID);
            }
            let formData = new FormData(form);
            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                  if (response.success) {
                      swalNotify("Success!", response.message, "success");
                      $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
                  } else {
                      swalNotify("Oops!", response.message, "error");
                  }
                },
                error: function(error) {
                    console.log(error);
                    swalNotify("Error!", error.responseJSON.message, "error");
                }
            });
        }
    });
  </script>
@endsection
