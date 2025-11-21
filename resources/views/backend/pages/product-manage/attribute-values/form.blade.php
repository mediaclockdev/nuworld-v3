@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$attributeValue->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.product-attribute-values')" :formId="'attributeValueForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="title" class="form-label">Attribute</label>
          <select name="parent_attribute" id="parent_attribute" class="form-select select2">
            <option value=""></option>
            @foreach ($attributes as $attribute)
              <option value="{{ Hashids::encode($attribute->id) }}"
                {{ $attributeValue->attribute_id == $attribute->id ? 'selected' : '' }}>{{ $attribute->name }}</option>
            @endforeach
          </select>
          <div id="parent_attribute-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="title" class="form-label">Value</label>
          <input type="text" name="value_name" id="value_name" class="form-control"
            value="{{ $attributeValue->value ?? '' }}">
          <div id="value_name-error-container"></div>
        </div>
      </div>
      <div class="col-md-4" id="colorPickerContainer" style="display: none;">
        <div class="mb-3 not-required">
          <label for="colorPicker" class="form-label">Select Color</label>
          <input type="color" id="colorPicker" name="colorPicker" class="form-control form-control-color"
            value="{{ $attributeValue->value_details ?? '#ffffff' }}">
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label for="sequence" class="form-label">Extra Details</label>
          <input type="text" name="extra_details" id="extra_details" class="form-control"
            value="{{ $attributeValue->value_details ?? '' }}">
          <div id="extra_details-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label for="sequence" class="form-label">Sequence</label>
          <input type="text" name="sequence" id="sequence" class="form-control only-integers"
            value="{{ $attributeValue->sequence ?? 1 }}">
          <div id="sequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="status" id="div_status" class="form-select">
            <option value="1" {{ $attributeValue->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $attributeValue->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
    </div>
  </x-form-card>
  {{-- <!-- end row --> --}}
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    $(function() {
      $('#parent_attribute').change(function() {
        const selectedText = $(this).find('option:selected').text().trim().toLowerCase();
        const chkColorValue = selectedText.includes('color') || selectedText.includes('colour');
        $('#colorPickerContainer').toggle(chkColorValue);
      }).trigger('change');

      $('#colorPicker').on('input', function() {
        $('#extra_details').val($(this).val());
      });
    });

    $('.select2').select2({
      'placeholder': 'Select an Attribute'
    });

    $('#attributeValueForm').validate({
      rules: {
        parent_attribute: {
          required: true,
        },
        value_name: {
          required: true,
          minlength: 1,
          maxlength: 100
        },
        extra_details: {
          maxlength: 100
        },
        sequence: {
          required: true
        },
      },
      messages: {
        parent_attribute: {
          required: "{{ __('validation.required', ['attribute' => 'Attribute']) }}"
        },
        value_name: {
          required: "{{ __('validation.required', ['attribute' => 'Value']) }}",
          minlength: "{{ __('validation.minlength', ['attribute' => 'Value', 'min' => 1]) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Value', 'max' => 100]) }}"
        },
        extra_details: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Extra Details', 'max' => 100]) }}"
        },
        sequence: {
          required: "{{ __('validation.required', ['attribute' => 'Sequence']) }}"
        },
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
        let formID = '{{ Hashids::encode($attributeValue->id ?? '') }}'
        let url = "{{ route('admin.product-attribute-values.store') }}"
        if (formID)
          url = `{{ route('admin.product-attribute-values.update', ':id') }}`.replace(':id', formID);

        $.ajax({
          type: "POST",
          url: url,
          data: $('#attributeValueForm').serialize(),
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
              if (!formID) {
                form.reset();
                $('#parent_attribute').val(null).trigger("change");
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
