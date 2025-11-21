@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$blog->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.blogs')" :formId="'blogForm'">
    <div class="row">

          <div class="col-md-4">
              <div class="mb-3 required">
                  <label for="title" class="form-label">Blog Title</label>
                  <input type="text" name="title" id="title" class="form-control"
                        value="{{ $blog->title ?? '' }}" required>
              </div>
          </div>

          <div class="col-md-4">
              <div class="mb-3 required">
                  <label for="slug" class="form-label">Slug</label>
                  <input type="text" name="slug" id="slug" class="form-control lowercase-slug"
                        value="{{ $blog->slug ?? '' }}" required>
              </div>
          </div>

          <div class="col-md-4">
              <div class="mb-3 required">
                  <label for="post_id" class="form-label">Post</label>
                  <select name="post_id" id="post_id" class="form-select select2">
                      <option value="">Select a Post</option>
                      @foreach($posts as $post)
                          <option value="{{ $post->id }}" {{ ($blog->post_id ?? '') == $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                      @endforeach
                  </select>
              </div>
          </div>

          <div class="col-md-12">
              <div class="mb-3 required">
                  <label for="short_description" class="form-label">Short Description</label>
                  <textarea name="short_description" id="short_description" class="form-control" rows="4" required>{{ $blog->short_description ?? '' }}</textarea>
              </div>
          </div>

          <div class="col-md-12">
              <div class="mb-3 required">
                  <label for="long_description" class="form-label">Long Description</label>
                  <textarea name="long_description" id="long_description" class="form-control" rows="6" required>{{ $blog->long_description ?? '' }}</textarea>
              </div>
          </div>

          <div class="col-md-4">
              <div class="mb-3">
                  <label for="published_at" class="form-label">Published At</label>
                  <input type="datetime-local" name="published_at" id="published_at" class="form-control"
                        value="{{ $blog->published_at ? date('Y-m-d\TH:i', strtotime($blog->published_at)) : '' }}">
              </div>
          </div>

          <div class="col-md-6">
            <div class="mb-3 not-required">
                <label class="form-label">Image</label>
                <input class="form-control" type="file" name="image" id="image" accept="image/*">
                <span class="text-muted">Preferred Size: 1360 x 500 px (Allowed Extensions: JPG, PNG, GIF & WebP)</span>
                <div id="image-error-container" class="error"></div>
            </div>
          </div>

          <div class="col-md-6" id="blog_image_preview_container" {{ $blog->image ?? 'style = display:none' }}>
            <div class="mb-3 not-required">
              <img
                src="{{ $blog->image ? asset('/public/storage/uploads/blogs/' . $blog->image) : '' }}"
                class="img-thumbnail mt-3" id="image_preview">
            </div>
          </div>

          {{-- <div class="col-md-12">
            <div class="required form-textarea mb-0">
                <label class="form-label">Description</label>
                <div id="description" name="description"></div>
              </div>
          </div> --}}

    </div>
  </x-form-card>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    // Safely encode the product data for JavaScript
    const blogData = @json($blog ?? []);
  </script>
  <script>
    let ckeditor5Instances = {};
    const editorFields = ['short_description', 'long_description'];


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
            const existingContent = blogData[field] ?? '';
            editor.setData(existingContent);
          })
          .catch(error => {
            console.error(`CKEditor error on ${field}:`, error);
          });
      }
    });
    // Function to reload data into CKEditors from blogData
    function reloadCKEditorDataFromProduct() {
      Object.keys(ckeditor5Instances).forEach(field => {
        const content = blogData[field] ?? '';
        ckeditor5Instances[field].setData(content);
      });
    }

  </script>
  <script>
    $('#post_id').select2({
      'placeholder': 'Select a Post',
    });

    let formID = `{{ Hashids::encode($blog->id ?? '') }}`;

    /* $("#code").keypress(function(e) {
      var keyCode = e.keyCode || e.which;
      var character = String.fromCharCode(keyCode);
      if ($(this).val().length >= 6 && keyCode !== 8 && keyCode !== 46) {
        return false;
      }
    }); */


    $.validator.addMethod("lowercase", function(value, element) {
    return this.optional(element) || /^[a-z0-9-]+$/.test(value);
    }, "{{ __('validation.lowercase', ['attribute' => 'Slug']) }}");

    $('#blogForm').validate({
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
          post_id: {
              required: true
          },
          short_description: {
              // required: true,
              maxlength: 65535
          },
          long_description: {
              // required: true,
              maxlength: 1000000
          },
          published_at: {
              date: true
          }
      },
      messages: {
          title: {
              required: "{{ __('validation.required', ['attribute' => 'Blog Title']) }}",
              minlength: "{{ __('validation.minlength', ['attribute' => 'Blog Title', 'min' => '3']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Blog Title', 'max' => '255']) }}"
          },
          slug: {
              required: "{{ __('validation.required', ['attribute' => 'Slug']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Slug', 'max' => '255']) }}",
              lowercase: "{{ __('validation.lowercase', ['attribute' => 'Slug']) }}"
          },
          post_id: {
              required: "{{ __('validation.required', ['attribute' => 'Post']) }}"
          },
          short_description: {
              // required: "{{ __('validation.required', ['attribute' => 'Short Description']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Short Description', 'max' => '65535']) }}"
          },
          long_description: {
              // required: "{{ __('validation.required', ['attribute' => 'Long Description']) }}",
              maxlength: "{{ __('validation.maxlength', ['attribute' => 'Long Description', 'max' => '1000000']) }}"
          },
          published_at: {
              date: "{{ __('validation.date', ['attribute' => 'Published At']) }}"
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
          let url = "{{ route('admin.blogs.store') }}";
          if (formID) {
              url = `{{ route('admin.blogs.update', ':id') }}`.replace(':id', formID);
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

    $('#image').change(function() {
      const file = this.files[0];
      const {
        type,
        size
      } = file;

      if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(type)) {
        $(this).val('');
        $('#image-error-container').text(
          'Invalid image type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        return;
      }

      if (size > 2e+6) {
        $(this).val('');
        $('#image-error-container').text('Image size should not exceed 2MB.');
        return;
      }
      $('#image-error-container').text('');
      const reader = new FileReader();
      $('#blog_image_preview_container').show();
      reader.onload = () => $('#image_preview').attr('src', reader.result);
      reader.readAsDataURL(file);
    })
  </script>
@endsection
