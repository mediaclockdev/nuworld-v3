@extends('backend.layouts.app')
@section('content')
<x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
<div class="row min-VH">
   <div class="col-xl-12 col-lg-12">
      <div class="card card-h-100">
         <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title mb-0">{{ $cardHeader }} </h4>
            <div class="d-flex align-items-center">

              <a href="{{ route('admin.faqs') }}"
                  class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2">
               Add New Faq <i class="mdi mdi-plus ms-1 font-16"></i></a>

               <a href="javascript:void(0);" id="addFaqCategoryBtn"
                  class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2">Add
               New Faq Caetgory <i class="mdi mdi-plus ms-1 font-16"></i></a>

            </div>
         </div>
         <div class="card-body">
                <!-- FAQ Category Form (Initially Hidden) -->
            <div id="faqCategoryFormContainer">
                <x-form-card :formTitle="'Create Faq Category'" :backRoute="route('admin.faq-categories')" :formId="'faqCategoryForm'">
                  <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3 required">
                            <label for="title" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $faqCategory->name ?? ''}}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="title" class="form-label">Button Text</label>
                            <input type="text" name="btn_text" id="btn_text" class="form-control" value="{{ $faqCategory->btn_text ?? ''}}">
                        </div>
                    </div>

                    <div class="col-md-4">
                      <div class="mb-3 not-required">
                        <label class="form-label">Button Url</label>
                        <input class="form-control " type="text" name="btn_url" id="btn_url"
                          placeholder="Enter Banner Hyperlink" value="{{ $faqCategory->btn_url ?? '' }}">
                        <div id="btn_url-error-container"></div>
                      </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="answer" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" >{{ $faq->description ?? '' }}</textarea>
                        </div>
                    </div>
                  </div>
                </x-form-card>
            </div>

            <!-- FAQ Listing Table -->
            <div id="faqCategoryTableContainer">
                <livewire:faq-manage.faq-category-table />
            </div>
          </div>
      </div>
   </div>
</div>
@endsection
@push('component-scripts')

@endpush

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script>
    // Safely encode the product data for JavaScript
    const faqCategory = @json($faqCategory ?? []);
  </script>
  <script>
  $(document).ready(function() {
    $('#addFaqCategoryBtn').on('click', function (e) {
        e.preventDefault();
        $('#faqCategoryTableContainer').show();
        $('#faqCategoryFormContainer').show();
    });


  })
</script>
  <script>

    let formID = `{{ Hashids::encode($faqCategory->id ?? '') }}`;
    $('#faqCategoryForm').validate({
      rules: {
          name: {
              required: true,
          },
          btn_url: {
            url: true,
            maxlength: 255
          }
      },
      messages: {
          name: {
              required: "{{ __('validation.required', ['attribute' => 'Name']) }}",
          },
          btn_url: {
            url: "{{ __('validation.url', ['attribute' => 'URL']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'URL', 'max' => 255]) }}"
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
          let url = "{{ route('admin.faq-categories.store') }}";
          if (formID) {
              url = `{{ route('admin.faq-categories.update', ':id') }}`.replace(':id', formID);
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
                      $('#faqCategoryFormContainer').hide();
                      $(form)[0].reset();
                      // Trigger Livewire to refresh the faq-table component
                      if (window.Livewire) {
                        if (typeof window.Livewire.dispatch === 'function') {
                            window.Livewire.dispatch('refreshComponent', { component: 'faq-manage.faq-table' });
                        } else if (typeof window.livewire.emit === 'function') {
                            window.livewire.emit('refreshComponent', 'faq-manage.faq-table');
                        }
                      }
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
