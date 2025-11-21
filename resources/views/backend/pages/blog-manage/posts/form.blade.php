@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$post->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.posts')" :formId="'postForm'">
    <div class="row">

          <div class="col-md-4">
              <div class="mb-3 required">
                  <label for="title" class="form-label">Post Title</label>
                  <input type="text" name="title" id="title" class="form-control"
                        value="{{ $post->title ?? '' }}" required>
              </div>
          </div>

          <div class="col-md-4">
              <div class="mb-3 required">
                  <label for="slug" class="form-label">Slug</label>
                  <input type="text" name="slug" id="slug" class="form-control lowercase-slug"
                        value="{{ $post->slug ?? '' }}" required>
              </div>
          </div>

          <div class="col-md-12">
              <div class="mb-3">
                  <label for="content" class="form-label">Content</label>
                  <textarea name="content" id="content" class="form-control" rows="6" required>{{ $post->content ?? '' }}</textarea>
              </div>
          </div>


    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>
  <script>
    // Safely encode the product data for JavaScript
    const postData = @json($post ?? []);
  </script>
  <script>
    let ckeditor5Instances = {};
    const editorFields = ['content'];


    editorFields.forEach(field => {
      const el = document.getElementById(field);
      if (el) {
        ClassicEditor.create(el, {
            toolbar: [
              'heading', '|',
              'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
              'blockQuote', 'insertTable', 'undo', 'redo', '|',
              'sourceEditing'
            ],
            sourceEditing: {
              allowedTags: true
            },
            htmlSupport: {
              allow: [{
                name: /.*/,
                attributes: true,
                classes: true,
                styles: true
              }]
            }
          })
          .then(editor => {
            ckeditor5Instances[field] = editor;
            editor.ui.view.editable.element.style.height = '300px';

            // ✅ Safely access existing field value
            const existingContent = postData[field] ?? '';
            editor.setData(existingContent);
          })
          .catch(error => {
            console.error(`CKEditor error on ${field}:`, error);
          });
      }
    });
    // Function to reload data into CKEditors from postData
    function reloadCKEditorDataFromProduct() {
      Object.keys(ckeditor5Instances).forEach(field => {
        const content = postData[field] ?? '';
        ckeditor5Instances[field].setData(content);
      });
    }

  </script>
  <script>
    let formID = `{{ Hashids::encode($post->id ?? '') }}`;

    $.validator.addMethod("lowercase", function(value, element) {
    return this.optional(element) || /^[a-z0-9-]+$/.test(value);
    }, "{{ __('validation.lowercase', ['attribute' => 'Slug']) }}");

    $('#postForm').validate({
      rules: {
          title: {
              required: true,
              minlength: 3,
              maxlength: 255
          },
          slug: {
              required: true,
              maxlength: 255,
              lowercase: true
          },
          content: {
              // required: true,
              maxlength: 1000000
          }
      },
      messages: {
          title: {
              required: "{{ __('validation.required', ['attribute' => 'Post Title']) }}",
              minlength: "{{ __('validation.minlength', ['attribute' => 'Post Title', 'min' => '3']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Post Title', 'max' => '255']) }}"
          },
          slug: {
              required: "{{ __('validation.required', ['attribute' => 'Slug']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Slug', 'max' => '255']) }}",
              lowercase: "{{ __('validation.lowercase', ['attribute' => 'Slug']) }}"
          },
          content: {
              // required: "{{ __('validation.required', ['attribute' => 'Long Description']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Long Description', 'max' => '1000000']) }}"
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
          let url = "{{ route('admin.posts.store') }}";
          if (formID) {
              url = `{{ route('admin.posts.update', ':id') }}`.replace(':id', formID);
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
