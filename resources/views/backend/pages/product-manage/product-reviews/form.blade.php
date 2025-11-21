@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
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
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$product->id ? [1] : []" />
  <div class="row min-VH">
    <div class="col-xl-12 col-lg-12">
      <div class="card card-h-100">
        <div class="d-flex card-header justify-content-between align-items-center">
          <h4 class="header-title">{{ $cardHeader }}</h4>
        </div>
        <input type="hidden" name="p_id" id="p_id" value="{{ Hashids::encode($product->id) ?? '' }}">
        <div class="card-body pt-3">
          <ul class="nav nav-tabs nav-bordered mb-4">
            <li class="nav-item">
              <a href="#page1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 px-2 active"
                id="page1-tab">
                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Basic Details</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#page2" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0 px-2 disabled"
                id="page2-tab">
                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                <span class="d-none d-md-block">Product Variants</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#page3" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 px-2 disabled"
                id="page3-tab">
                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                <span class="d-none d-md-block">Pricing & Media</span>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane show active" id="page1">
              @include('backend.pages.product-manage.products.partials.product-page')
            </div>
            <div class="tab-pane " id="page2">
              @include('backend.pages.product-manage.products.partials.product-variants')
            </div>
            <div class="tab-pane " id="page3">
              @include('backend.pages.product-manage.products.partials.variant-price-image')
              <div class="d-flex mt-4 justify-content-between align-items-center">
                {{-- <div class="back-btn"><a href="javascript:void(0)" onclick="gotoPreviousTab(2)" title="Back"
                    class="d-flex justify-content-start align-items-center font-16"><i
                      class="uil-angle-left font-18 me-1"></i>Back</a>
                </div> --}}
                <a href="{{ route('admin.products') }}" class="btn btn-primary gap-2"> Submit </a>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>
  <script>
    // Safely encode the product data for JavaScript
    const productData = @json($product ?? []);
  </script>
  <script>
    let ckeditor5Instances = {};
    const editorFields = ['product_details', 'specifications', 'care_maintenance', 'warranty'];


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
            const existingContent = productData[field] ?? '';
            editor.setData(existingContent);
          })
          .catch(error => {
            console.error(`CKEditor error on ${field}:`, error);
          });
      }
    });
  </script>
  <script>
    let productId = $('#p_id').val();

    $(document).ready(function() {
      // chkDisabled();
      // $(document).on('click', '.back-btn a', function() {
      //   gotoPreviousTab(1); // or dynamically determine the tab number
      // });
    });

    $('#page1').on('click', function() {
      // alert('hi')
      //   gotoPreviousTab(1); // or dynamically determine the tab number
    });

    function chkDisabled() {
      if (!productId) {
        $('#page2-tab').addClass('disabled');
        $('#page3-tab').addClass('disabled');
      } else {
        $('#page2-tab').removeClass('disabled');
        $('#page3-tab').removeClass('disabled');
      }
    }

    function gotoNextTab(tabNumber) {
      console.log(tabNumber)
      $('#page' + tabNumber + '-tab').removeClass('disabled');
      $('.nav-tabs a').removeClass('active');
      $('.tab-pane').removeClass('show active');
      $('#page' + tabNumber + '-tab').addClass('active');
      $('#page' + tabNumber).addClass('show active');
      // const editorFields = ['product_details', 'specifications', 'care_maintenance', 'warranty'];

      // editorFields.forEach(field => {
      //   const editor = ckeditor5Instances[field];
      //   const el = document.getElementById(field);

      //   if (editor && el && $('#page' + tabNumber).has(el).length) {
      //     const currentData = editor.getData().trim();

      //     if (!currentData) {
      //       const content = existingData[field] ?? '';
      //       editor.setData(content);
      //     }

      //     editor.ui.update();
      //   }
      // });


      existingVariants(true);
      window.scrollTo(0, 0);
    }

    // function gotoPreviousTab(tabNumber) {
    //   alert('hi');
    //   console.log(tabNumber);
    //   console.log('click')
    //   $('#page' + tabNumber + '-tab').removeClass('disabled');
    //   $('.nav-tabs a').removeClass('active');
    //   $('.tab-pane').removeClass('show active');
    //   $('#page' + tabNumber + '-tab').addClass('active');
    //   $('#page' + tabNumber).addClass('show active');
    //   // const editorFields = ['product_details', 'specifications', 'care_maintenance', 'warranty'];


    //   // editorFields.forEach(field => {
    //   //   const editor = ckeditor5Instances[field];
    //   //   const el = document.getElementById(field);

    //   //   if (editor && el && $('#page' + tabNumber).has(el).length) {
    //   //     const currentData = editor.getData().trim();

    //   //     if (!currentData) {
    //   //       const content = existingData[field] ?? '';
    //   //       console.log(content)
    //   //       editor.setData(content);
    //   //     }

    //   //     editor.ui.update();
    //   //   }
    //   // });


    //   existingVariants(true);
    //   window.scrollTo(0, 0);
    // }
  </script>
@endsection
