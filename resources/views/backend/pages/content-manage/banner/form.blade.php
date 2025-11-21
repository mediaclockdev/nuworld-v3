@extends('backend.layouts.app')
@section('page-styles')
  <style>
    /* #modal_form_view .image img {
                                    width: 100%;
                                } */
    .ck.ck-balloon-panel {
      z-index: 99999 !important;
    }

    .ck-editor__editable_inline:not(.ck-comment__input *) {
      height: 300px;
      overflow-y: auto;
    }
  </style>
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$banner->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.banners')" :formId="'bannerForm'">
    <div class="row">
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Position</label>
          <select class="form-select" name="banner_position" id="banner_position">
            <option value="">Select Position</option>
            @foreach ($positions as $position)
              <option value="{{ Hashids::encode($position->id) }}"
                {{ $banner->position == $position->id ? 'selected' : '' }} data-value1="{{ $position->value_1 }}">
                {{ $position->name }}</option>
            @endforeach
          </select>
          <div id="banner_position-error-container"></div>
        </div>
        {{-- @php
            pd($banner->positionValue->value_1);
        @endphp --}}
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Title</label>
          <input class="form-control only-alphabet-numbers-symbols" type="text" name="banner_title" id="banner_title"
            placeholder="Enter Banner Title" value="{{ $banner->title ?? '' }}">
          <div id="banner_title-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Sequence</label>
          <input class="form-control only-integers" type="text" name="banner_sequence" id="banner_sequence"
            placeholder="Enter Sequence" value="{{ $banner->sequence ?? '1' }}">
          <div id="banner_sequence-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Alt Text</label>
          <input class="form-control" type="text" name="banner_alt_text" id="banner_alt_text"
            placeholder="Enter Alt Text" value="{{ $banner->alt_text ?? '' }}">
          <div id="banner_alt_text-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Product SKU</label>
          <input class="form-control" type="text" name="banner_extra_value" id="banner_extra_value"
            placeholder="Enter Product SKU" value="{{ $banner->extra_value ?? '' }}">
          <div id="banner_extra_value-error-container"></div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="mb-3 required">
          <label class="form-label">Status </label>
          <select name="banner_status" id="banner_status" class="form-select">
            <option value="1" {{ $banner->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $banner->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Hyperlink</label>
          <input class="form-control " type="text" name="banner_hyperlink" id="banner_hyperlink"
            placeholder="Enter Banner Hyperlink" value="{{ $banner->hyper_link ?? '' }}">
          <div id="banner_hyperlink-error-container"></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Content</label>
          <div id="banner_description" name="banner_description"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Image</label>
          <input class="form-control" type="file" name="banner_image" id="banner_image" accept="image/*"
            value="{{ $banner->image ?? '' }}">
            <span class="text-muted" id="banner_size">Preferred Size: {{ $banner->positionValue->value_1 ?? '1360 x 500 px' }} (Allowed Extensions: JPG, PNG & WebP)</span>
          <div id="banner_image-error-container"></div>
        </div>
      </div>
      <div class="col-md-6" id="image_preview_container" {{ $banner->image ?? 'style = display:none' }}>
        <div class="mb-3 not-required">
          <img src="{{ $banner->image ? asset('/public/storage/uploads/banners/' . $banner->image) : '' }}"
            class="img-thumbnail mt-3" id="image_preview">
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> --}}
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/balloon/ckeditor.js"></script> --}}
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>
  {{-- <script>
    BalloonEditor
      .create(document.querySelector('#banner_description'), {
        toolbar: ['bold', 'italic', 'codeBlock', 'htmlEmbed', '|', 'undo', 'redo']
      })
      .then(editor => {
        window.editor = editor;
      })
      .catch(error => console.error(error));
  </script> --}}
  <script>
    let ckeditor5Instances = {}; // Define the ckeditor5Instances object

    const element = document.getElementById('banner_description'); // Define the element properly

    ClassicEditor.create(element, {
        toolbar: [
          'heading', '|',
          'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
          'blockQuote', 'insertTable', 'undo', 'redo', '|',
          'sourceEditing' // Add this to show Source button
        ],
        sourceEditing: {
          // Optional: allow editing all HTML
          allowedTags: true
        },
        htmlSupport: {
          allow: [{
            name: /.*/, // allow all tags
            attributes: true,
            classes: true,
            styles: true
          }]
        }
      })
      .then(editor => {
        ckeditor5Instances['banner_description'] = editor; // Store the editor instance with a unique key
        editor.ui.view.editable.element.style.height = '300px';
        // Set existing content from the backend
        const existingContent = @json($banner->content ?? '');
        editor.setData(existingContent);
      })
      .catch(error => {
        console.error('CKEditor initialization error:', error);
      });
  </script>


  <script>
    // const dbContent = {!! json_encode($banner->content ?? '') !!};
    // ClassicEditor
    //   .create(document.querySelector('#banner_description'), {
    //     removePlugins: ['ImageUpload', 'ImageToolbar', 'ImageInsert', 'EasyImage',
    //       'ImageResize', 'ImageStyle', 'Image', 'CKFinder'
    //     ],
    //   })
    //   .then(editor => {
    //     window.editor = editor;
    //     editor.setData(dbContent); // Load existing content
    //   })
    //   .catch(error => console.error(error));


    $(document).ready(function() {
      $('#bannerForm').validate({
        rules: {
          banner_title: {
            required: true,
            maxlength: 100
          },
          banner_position: {
            required: true,
          },
          banner_sequence: {
            required: true,
            min: 1,
            max: 127
          },
          banner_hyperlink: {
            url: true,
            maxlength: 255
          },
          banner_image: {
            required: function() {
              return $('#banner_image_preview').attr('src') === '';
            },
          },
          banner_alt_text: {
            maxlength: 100
          },
          banner_extra_value: {
            maxlength: 100
          }
        },
        messages: {
          banner_title: {
            required: "{{ __('validation.required', ['attribute' => 'Banner Title']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Banner Title', 'max' => '100']) }}"
          },
          banner_position: {
            required: "{{ __('validation.required', ['attribute' => 'Banner Position']) }}",
          },
          banner_sequence: {
            required: "{{ __('validation.required', ['attribute' => 'Banner Sequence']) }}",
            max: "{{ __('validation.maxvalue', ['attribute' => 'Banner Sequence', 'max' => '127']) }}"
          },
          banner_hyperlink: {
            url: "{{ __('validation.url', ['attribute' => 'Banner Hyperlink']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Banner Hyperlink', 'max' => '255']) }}"
          },
          banner_image: {
            required: "{{ __('validation.required', ['attribute' => 'Banner Image']) }}",
          },
          banner_alt_text: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Banner Alt Text', 'max' => '100']) }}"
          },
          banner_extra_value: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Product SKU', 'max' => '100']) }}"
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
          let formID = '{{ Hashids::encode($banner->id ?? '') }}'
          let url = "{{ route('admin.banners.store') }}"
          if (formID)
            url = `{{ route('admin.banners.update', ':id') }}`.replace(':id', formID);

          let formData = new FormData(form);
          formData.append('banner_description', ckeditor5Instances['banner_description'].getData());

          if (banner_image === '') {
            return false;
          } else {
            formData.append('banner_image', '{{ $banner->image }}');
          }

          $.ajax({
            url: url,
            method: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
              console.log(response);
              if (response.success) {
                swalNotify("Success!", response.message, "success");
                $('.is-valid').removeClass('is-valid');
                if (!formID) {
                  $('#bannerForm')[0].reset();
                  ckeditor5Instances['banner_description'].setData('');
                  $('#image_preview_container').hide();
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

    $('#banner_image').change(function() {
      const file = this.files[0];
      const {
        type,
        size
      } = file;

      if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(type)) {
        $(this).val('');
        $('#banner_image-error-container').text(
          'Invalid image type. Only JPEG, PNG and WebP images are allowed.');
        return;
      }

      if (size > 2e+6) {
        $(this).val('');
        $('#banner_image-error-container').text('Image size should not exceed 2MB.').addClass('error');
        return;
      }
      $('#banner_image-error-container').text('').removeClass('error');
      const reader = new FileReader();
      $('#image_preview_container').show();
      reader.onload = () => $('#image_preview').attr('src', reader.result);
      reader.readAsDataURL(file);
    })

    $('#banner_position').on('change', function() {
      let value = $(this).val();
      let attrVal = $(this).find('option:selected').attr('data-value1');
      $('#banner_size').text(`Preferred Size: ${attrVal} (Allowed Extensions: JPG, PNG & WebP)`);
    })
  </script>
@endsection
