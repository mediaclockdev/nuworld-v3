@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$productAttribute->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.product-attributes')" :formId="'attributeForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="title" class="form-label">Name</label>
          <input type="text" name="attribute_title" id="attribute_title" class="form-control only-alphabet-symbols"
            value="{{ $productAttribute->name ?? '' }}">
          <div id="attribute_title-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sequence" class="form-label">Sequence</label>
          <input type="text" name="sequence" id="sequence" class="form-control only-integers"
            value="{{ $productAttribute->sequence ?? 1 }}">
          <div id="sequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $productAttribute->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $productAttribute->status === 0 ? 'selected' : '' }}>Inactive</option>
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
  <script>
    $('#attributeForm').validate({
      rules: {
        attribute_title: {
          required: true,
          minlength: 3,
          maxlength: 100
        },
        sequence: {
          required: true,
          min: 1,
          max: 50000,
        },
      },
      messages: {
        attribute_title: {
          required: "{{ __('validation.required', ['attribute' => 'Attribute Name']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Attribute Name', 'min' => 3]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Attribute Name', 'max' => 100]) }}"
        },
        sequence: {
          required: "{{ __('validation.required', ['attribute' => 'Sequence']) }}",
          min: "{{ __('validation.minvalue', ['attribute' => 'Sequence', 'min' => 1]) }}",
          max: "{{ __('validation.maxvalue', ['attribute' => 'Sequence', 'max' => 50000]) }}"
        },
      },
      errorElement: "div",
      errorPlacement: function(error, element) {
        let errorContainer = $(`#${element.attr('id')}-error-container`);
        if (errorContainer.length) {
          error.appendTo(errorContainer);
        } else {
          error.insertAfter(element); // Fallback for other elements
        }
      },
      highlight: function(element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
      },
      unhighlight: function(element) {
        $(element).removeClass("is-invalid").addClass("is-valid");
      },
      submitHandler: function(form) {
        let formID = '{{ Hashids::encode($productAttribute->id ?? '') }}'
        let url = "{{ route('admin.product-attributes.store') }}"
        if (formID)
          url = `{{ route('admin.product-attributes.update', ':id') }}`.replace(':id', formID);

        $.ajax({
          type: "POST",
          url: url,
          data: $('#attributeForm').serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
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
  </script>
@endsection
