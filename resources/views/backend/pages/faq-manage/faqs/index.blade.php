@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
@php
    $prevUrl = url()->previous();
    $segments = explode('/', parse_url($prevUrl, PHP_URL_PATH));
    $lastElememnt = $segments[count($segments) - 1];
    // pd($lastElememnt);
@endphp
<x-breadcrumb :pageTitle="$pageTitle" :skipLevels=[] />
<div class="row min-VH">
   <div class="col-xl-12 col-lg-12">
      <div class="card card-h-100">
         <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title mb-0">{{ $cardHeader }} </h4>
            <div class="d-flex align-items-center">

              <a href="{{ route('admin.faq-categories') }}" id="addFaqCategoryBtn"
              class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2">
              New Faq Category <i class="mdi mdi-plus ms-1 font-16"></i></a>

              <a href="javascript:void(0);" id="addFaqFormBtn"
                 class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2">Add
              New Faq <i class="mdi mdi-plus ms-1 font-16"></i></a>

            </div>
         </div>
         <div class="card-body">
                <!-- FAQ Form (Initially Hidden) -->
              <div id="faqFormContainer" style="{{ !empty($lastElememnt) && !in_array($lastElememnt, ['edit', 'faq-categories']) ? 'display: none;' : 'display: block;' }}">
                <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.faqs')" :formId="'faqForm'">
                    <div class="row">
                      <div class="col-md-3">
                          <div class="mb-3 required">
                              <label for="faq_category_id" class="form-label">Category</label>
                              <select name="faq_category_id" id="faq_category_id" class="form-select select2">
                                  <option value="">Select a Category</option>
                                  @forelse($faqCategories as $faqCategory)
                                      <option value="{{ Hashids::encode($faqCategory->id ?? '') }}" {{ ($faq->faq_category_id ?? '') == $faqCategory->id ? 'selected' : '' }}>{{ $faqCategory->name }}</option>
                                  @empty
                                      <option value="">No category found!</option>
                                  @endforelse
                              </select>
                          </div>
                      </div>
                      <div class="col-md-9">
                          <div class="mb-3 required">
                              <label for="title" class="form-label">Question</label>
                              <input type="text" name="question" id="question" class="form-control"
                                    value="{{ $faq->question ?? '' }}">
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="mb-3 required">
                              <label for="answer" class="form-label">Answer</label>
                              <textarea name="answer" id="answer" class="form-control" rows="4">{{ $faq->answer ?? '' }}</textarea>
                          </div>
                      </div>
                    </div>
                  </x-form-card>
                </div>

              <!-- FAQ Listing Table -->
              <div id="faqTableContainer">
                  <livewire:faq-manage.faq-table />
              </div>
          </div>
      </div>
   </div>
</div>
@endsection
@push('component-scripts')
<script>
  $(document).ready(function() {
    $('#addFaqFormBtn').on('click', function (e) {
        e.preventDefault();
        $('#faqTableContainer').show();
        $('#faqFormContainer').show();
    });
  })
</script>
@endpush

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>

  <script>
    // Safely encode the product data for JavaScript
    // const faqCategory = @json($faqCategory ?? []);
    const faqData = @json($faq ?? []);
  </script>
  {{-- <script>

    let formID = `{{ Hashids::encode($faqCategory->id ?? '') }}`;
    $('#faqCategoryForm').validate({
      rules: {
          name: {
              required: true,
          },
      },
      messages: {
          name: {
              required: "{{ __('validation.required', ['attribute' => 'Category']) }}",
          },
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
  </script> --}}
   <script>
    $('#faq_category_id').select2({
      'placeholder': 'Select a Category',
    });

    let formID = `{{ Hashids::encode($faq->id ?? '') }}`;
    console.log('formID', formID);


    $('#faqForm').validate({
      rules: {
          question: {
              required: true,
          },
          faq_category_id: {
              required: true
          },
          answer: {
              required: true,
          },
      },
      messages: {
          question: {
              required: "{{ __('validation.required', ['attribute' => 'Question']) }}",
          },
          faq_category_id: {
              required: "{{ __('validation.required', ['attribute' => 'Category']) }}"
          },
          answer: {
              required: "{{ __('validation.required', ['attribute' => 'Answer']) }}",
          },
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
          let url = "{{ route('admin.faqs.store') }}";
          if (formID) {
              url = `{{ route('admin.faqs.update', ':id') }}`.replace(':id', formID);
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
                      $('#faqFormContainer').hide();
                      $('#addFaqBtn').show();
                      $(form).find(".is-valid, .is-invalid").removeClass("is-valid is-invalid");
                       $(form)[0].reset();
                       $('#faq_category_id').val('').trigger('change');
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

    $('#addFaqBtn').on('click', function (e) {
      // $('#faqTableContainer').hide();
      $('#faqFormContainer').show();
    });
  </script>
@endsection
