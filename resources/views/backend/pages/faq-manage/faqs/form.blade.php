@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
 {{--  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$faq->id ? [1] : []" />
  <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.faqs')" :formId="'faqForm'">
    <div class="row">
      <div class="col-md-6">
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
      <div class="col-md-6">
          <div class="mb-3 required">
              <label for="title" class="form-label">Question</label>
              <input type="text" name="question" id="question" class="form-control"
                    value="{{ $faq->question ?? '' }}" required>
          </div>
      </div>
      <div class="col-md-12">
          <div class="mb-3 required">
              <label for="answer" class="form-label">Answer</label>
              <textarea name="answer" id="answer" class="form-control" rows="4" required>{{ $faq->answer ?? '' }}</textarea>
          </div>
      </div>
    </div>
  </x-form-card>

  <livewire:faq-manage.faq-table /> --}}

<x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$faq->id ? [1] : []" />
<div class="row min-VH">
   <div class="col-xl-12 col-lg-12">
      <div class="card card-h-100">
         <div class="d-flex card-header justify-content-between align-items-center">
            <h4 class="header-title mb-0">{{ $cardHeader}} </h4>
            <div class="d-flex align-items-center">
              @if (empty($faq->id))
               <a href="{{ route('admin.faqs') }}" id="addFaqBtn"
                  class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2">Add
               New Faq <i class="mdi mdi-plus ms-1 font-16"></i></a>
               @else
               <a href="{{ route('admin.faqs') }}" id="addFaqBtn"
                  class="btn d-flex align-items-center btn-success btn-sm gap-1 me-2" style="display: none !important;">Add
               New Faqs <i class="mdi mdi-plus ms-1 font-16"></i></a>

               @endif

               {{-- <div class="dropdown ms-1">
                  <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                     aria-expanded="false">
                  <i class="mdi mdi-dots-vertical"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end"></div>
               </div> --}}
            </div>
         </div>
         <div class="card-body">
                <!-- FAQ Category Form (Initially Hidden) -->
              <div id="faqFormContainer">
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
                                <textarea name="answer" id="answer" class="form-control" rows="4" required>{{ $faq->answer ?? '' }}</textarea>
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

@endpush

@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script>
    // Safely encode the product data for JavaScript
    const faqData = @json($faq ?? []);
  </script>
  <script>
  /* $(document).ready(function() {
    $('#addFaqBtn').on('click', function (e) {
        e.preventDefault();
        $('#faqTableContainer').show();
        $('#faqFormContainer').show();
    });
  }) */
</script>
  <script>
    $('#faq_category_id').select2({
      'placeholder': 'Select a Category',
    });

    let formID = `{{ Hashids::encode($faq->id ?? '') }}`;

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

    /* $('#addFaqBtn').on('click', function (e) {
      // $('#faqTableContainer').hide();
      $('#faqFormContainer').show();
    }); */
  </script>
@endsection
