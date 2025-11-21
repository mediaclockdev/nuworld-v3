@extends('backend.layouts.app')
@section('page-styles')
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$cmsPage->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.cms-pages')" :formId="'cmsPagesForm'">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Title</label>
          <input class="form-control only-alphabet-symbols" type="text" name="cms_title" id="cms_title"
            placeholder="Enter Title" value="{{ $cmsPage->title ?? '' }}">
          <div id="cms_title-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 required">
          <label class="form-label">Slug</label>
          <input class="form-control lowercase-slug" type="text" name="cms_slug" id="cms_slug"
            placeholder="Enter Slug" value="{{ $cmsPage->slug ?? '' }}">
          <div id="cms_slug-error-container"></div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3 not-required">
          <label class="form-label">Meta Title</label>
          <input class="form-control" type="text" name="meta_title" id="meta_title" placeholder="Enter Title"
            value="{{ $cmsPage->meta_title ?? '' }}">
          <div id="meta_title-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 not-required">
          <label class="form-label">Meta Keywords</label>
          <input class="form-control" type="text" name="meta_keywords" id="meta_keywords" placeholder="Enter Keywords"
            value="{{ $cmsPage->meta_keywords ?? '' }}">
          <div id="meta_keywords-error-container"></div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="mb-3 required">
          <label class="form-label">Status</label>
          <select class="form-select" name="cms_status" id="cms_status">
            <option value="1" {{ $cmsPage->status === 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ $cmsPage->status === 0 ? 'selected' : '' }}>Inactive</option>
          </select>
          <div id="status-error-container"></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Meta Description</label>
          <textarea class="form-control" name="meta_description" id="meta_description" placeholder="Enter Meta Description">{{ $cmsPage->meta_description ?? '' }}</textarea>
          <div id="meta_description-error-container"></div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="mb-3 not-required">
          <label class="form-label">Description</label>
          <div id="cms_description" name="cms_description"></div>
          <div id="cms_description-error-container"></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3 not-required">
          <label class="form-label">Image</label>
          <input class="form-control" type="file" name="cms_image" id="cms_image" accept="image/*">
          <span class="text-muted">Preferred Size: 1360 x 500 px (Allowed Extensions: JPG, PNG, GIF & WebP)</span>
          <div id="cms_image-error-container" class="error"></div>
        </div>
      </div>

      <div class="col-md-6" id="image_preview_container" {{ $cmsPage->feature_image ?? 'style = display:none' }}>
        <div class="mb-3 not-required">
          <img
            src="{{ $cmsPage->feature_image ? asset('/public/storage/uploads/cms_pages/' . $cmsPage->feature_image) : '' }}"
            class="img-thumbnail mt-3" id="image_preview">
        </div>
      </div>
    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script> --}}
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>

  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  {{-- <script>
    let ckeditor5Instances = {}; // Define the ckeditor5Instances object

    const element = document.getElementById('cms_description'); // Define the element properly

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
        ckeditor5Instances['cms_description'] = editor; // Store the editor instance with a unique key
        editor.ui.view.editable.element.style.height = '300px';
      })
      .catch(error => {
        console.error('CKEditor initialization error:', error);
      });
  </script> --}}
  <script>
    let ckeditor5Instances = {}; // Define the ckeditor5Instances object

    const element = document.getElementById('cms_description'); // Define the element properly

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
        ckeditor5Instances['cms_description'] = editor; // Store the editor instance with a unique key
        editor.ui.view.editable.element.style.height = '300px';
        // Set existing content from the backend
        const existingContent = @json($cmsPage->body ?? '');
        editor.setData(existingContent);
      })
      .catch(error => {
        console.error('CKEditor initialization error:', error);
      });
  </script>
  <script>
    // const dbContent = {!! json_encode($cmsPage->body ?? '') !!};
    // ClassicEditor
    //   .create(document.querySelector('#cms_description'), {
    //     removePlugins: ['ImageUpload', 'ImageToolbar', 'ImageInsert', 'EasyImage',
    //       'ImageResize', 'ImageStyle', 'Image', 'CKFinder'
    //     ],
    //   })
    //   .then(editor => {
    //     window.editor = editor;
    //     editor.setData(dbContent);
    //   })
    //   .catch(error => console.error(error));

    let formID = '{{ Hashids::encode($cmsPage->id ?? '') }}'
    $('#cmsPagesForm').validate({
      rules: {
        cms_title: {
          required: true,
          maxlength: 100
        },
        cms_slug: {
          required: true,
          maxlength: 100
        },
        meta_title: {
          maxlength: 255
        },
        meta_keywords: {
          maxlength: 255
        },
        meta_description: {
          maxlength: 1000
        },
        cms_status: {
          required: true,
        },
      },
      messages: {
        cms_title: {
          required: "{{ __('validation.required', ['attribute' => 'Title']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Title', 'max' => '100']) }}"
        },
        cms_slug: {
          required: "{{ __('validation.required', ['attribute' => 'Slug']) }}",
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Slug', 'max' => '100']) }}"
        },
        meta_title: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Meta Title', 'max' => '255']) }}"
        },
        meta_keywords: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Meta Keywords', 'max' => '255']) }}"
        },
        meta_description: {
          maxlength: "{{ __('validation.maxlength', ['attribute' => 'Meta Description', 'max' => '1000']) }}"
        },
        cms_status: {
          required: "{{ __('validation.required', ['attribute' => 'Status']) }}",
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
        let url = "{{ route('admin.cms-pages.store') }}"
        if (formID)
          url = `{{ route('admin.cms-pages.update', ':id') }}`.replace(':id', formID);

        let formData = new FormData(form);
        // formData.append('cms_description', window.editor.getData());
        formData.append('cms_description', ckeditor5Instances['cms_description'].getData());
        //formData.append('banner_description', ckeditor5Instances['banner_description'].getData());
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              $('.is-valid').removeClass('is-valid');
              swalNotify("Success!", response.message, "success");
              if (!formID) {
                $('#cmsPagesForm')[0].reset();
                ckeditor5Instances['cms_description'].setData('');
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

    $('#cms_image').change(function() {
      const file = this.files[0];
      const {
        type,
        size
      } = file;

      if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(type)) {
        $(this).val('');
        $('#cms_image-error-container').text(
          'Invalid image type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        return;
      }

      if (size > 2e+6) {
        $(this).val('');
        $('#cms_image-error-container').text('Image size should not exceed 2MB.');
        return;
      }
      $('#cms_image-error-container').text('');
      const reader = new FileReader();
      $('#image_preview_container').show();
      reader.onload = () => $('#image_preview').attr('src', reader.result);
      reader.readAsDataURL(file);
    })

    $('#cms_title').on('input', function() {
      if (formID) return;
      var slug = $(this).val().toLowerCase().replace(/[^a-z0-9]+/g, '-');
      $('#cms_slug').val(slug);
    });
  </script>
@endsection
