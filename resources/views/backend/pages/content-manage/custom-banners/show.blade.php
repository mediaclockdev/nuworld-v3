@extends('backend.layouts.app')

@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/common/css/nestable.min.css') }}">
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
  <style>
    .hotspot-editor {
      position: relative;
      display: inline-block;
    }

    .hotspot-editor .hotspot {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      border: 2px solid #fff;
      background: rgba(17, 17, 17, 0.5);
      position: absolute;
      transform: translate(-50%, -50%);
      cursor: pointer;
    }

    .hotspot-editor .hotspot::before {
      content: "";
      position: absolute;
      left: 50%;
      top: 50%;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: white;
      transform: translate(-50%, -50%);
    }
  </style>
@endsection
@php
  $firstSettings = json_decode($customBannerSpeed->settings ?? '{}', true);
  $globalSpeed = $firstSettings['speed'] ?? '';
  $fourHoverCardSettings = json_decode($customBannerFourHoverCard->settings ?? '{}', true);
  $globalFourHoverCartTitle = $fourHoverCardSettings['title'] ?? '';
  $shouldHideButton =
      ($key === 'hover_card' && $customBanners->count() > 2) ||
      ($key === 'block_wrap' && $customBanners->count() > 0) ||
      ($key === 'subscribe_banner' && $customBanners->count() > 0) ||
      ($key === 'sale_block_fullwidth' && $customBanners->count() > 0) ||
      ($key === 'flow_banner' && $customBanners->count() > 0) ||
      ($key === 'shop_details_page_banner' && $customBanners->count() > 6) ||
      ($key === 'category_page_banner' && $customBanners->count() > 0) ||
      ($key === 'category_sale_block' && $customBanners->count() > 0) ||
      ($key === 'hot_deals_category_banner' && $customBanners->count() > 3) ||
      ($key === 'login_page_banner' && $customBanners->count() > 0) ||
      ($key === 'category_page_headline_banner' && $customBanners->count() > 0) ||
      ($key === 'app_splash_logo' && $customBanners->count() > 0) ||
      ($key === 'app_journey_screen' && $customBanners->count() > 0) ||
      ($key === 'app_home_landing_inner_banner' && $customBanners->count() > 2) ||
      ($key === 'home_page_top_category_banner' && $customBanners->count() > 0) ||
      ($key === 'blog_page_banner' && $customBanners->count() > 0) ||
      ($key === 'mega_menu_banner' && $customBanners->count() > 6) ||
      ($key === 'book_collection_banner' && $customBanners->count() > 0);
@endphp
@section('content')
  <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="[1]" />

  <div class="card card-h-100">
    <div class="d-flex card-header justify-content-between align-items-center">
      <h4 class="header-title mb-0">{{ $cardHeader }} </h4>
      <div class="d-flex align-items-center">
        <a id="toggleFormBtn" class="btn btn-sm btn-success {{ $shouldHideButton ? 'd-none' : '' }}">
          Add Banner<i class="mdi mdi-plus ms-1 font-16"></i></a>
      </div>
    </div>
    <div class="card-body">
      @if ($key === 'ticker')
        <form id="globalSpeedForm" class="mb-4 border p-3">
          @csrf
          <input type="hidden" name="position" value="ticker_speed">
          <div class="row mb-3 d-flex align-items-center">
            <div class="col-md-4 required">
              <label>Global Speed (In Seconds.)</label>
              <input type="text" class="form-control only-integers" name="speed" id="globalSpeedInput" min="1"
                max="10" step="1"
                value="{{ !empty($globalSpeed) ? $globalSpeed / 1000 : $banner_config_speed }}">

            </div>
            <div class="col-md-4 mt-3">
              <button type="submit" class="btn btn-outline-primary">Save</button>
            </div>
          </div>
        </form>
      @elseif($key === 'four_hover_cards')
        <form id="globalFourHoverTitleForm" class="mb-4 border p-3">
          @csrf
          <input type="hidden" name="position" value="four_hover_card_title">
          <div class="row mb-3 d-flex align-items-center">
            <div class="col-md-4 required">
              <label>Main Title</label>
              <input type="text" class="form-control" name="title" id="globalTitleInput"
                value="{{ !empty($globalFourHoverCartTitle) ? $globalFourHoverCartTitle : '' }}">

            </div>
            <div class="col-md-4 mt-3">
              <button type="submit" class="btn btn-outline-primary">Save</button>
            </div>
          </div>
        </form>
      @endif

      {{-- Add / Edit Form --}}
      <form id="addBannerForm" class="mb-4 border p-3 d-none" enctype="multipart/form-data">
        <input type="hidden" name="position" value="{{ $key }}">
        <input type="hidden" name="id" id="bannerId">
        <input type="hidden" name="custom_order" id="customOrderInput">
        @if ($key === 'book_collection_banner')
          <div class="col-md-12 mt-4">
            <label><strong>Upload Image & Add Hotspots (Product SKUs)</strong></label>

            {{-- Image input --}}
            <input type="file" name="coordinates_image" id="bannerImageInput_book_collection_banner" accept="image/*"
              class="form-control mb-3">

            {{-- Image + hotspot container --}}
            <div class="hotspot-editor border rounded position-relative d-inline-block" style="display:none;">
              <img id="bannerImagePreview" src="{{ !empty($banner->image) ? asset('storage/' . $banner->image) : '' }}"
                alt="Banner Preview" class="img-fluid" style="cursor: crosshair; max-width: 100%; height: auto;">
              <div id="hotspotContainer"></div>
            </div>

            {{-- <input type="hidden" name="options[hotspots]" id="hotspotData"> --}}
            <input type="hidden" name="coordinates[hotspots]" id="hotspotData" value='@json($banner->settings['coordinates']['hotspots'] ?? [])'>

            <p class="text-muted mt-2">
              Click anywhere on the image to add a Product SKU hotspot.<br>
              Right-click on a point to remove it.
            </p>
          </div>
        @endif




        <div class="row mb-3">
          @php
            $fields = [
                'title' => ['type' => 'text', 'label' => 'Title', 'id' => 'titleInput', 'col' => 'col-md-4 required'],
                'sub_title' => [
                    'type' => 'text',
                    'label' => 'Sub Title',
                    'id' => 'subTitleInput',
                    'col' => 'col-md-4 required',
                ],
                'hyper_link' => [
                    'type' => 'url',
                    'label' => 'Hyperlink',
                    'id' => 'hyperlinkInput',
                    'col' => 'col-md-4',
                ],
                'image' => [
                    'type' => 'file',
                    'label' => 'Image',
                    'id' => 'imageInput',
                    'col' => 'col-md-4',
                    'extra' => !empty($bannerConfig['default_image_size'])
                        ? "data-size=\"{$bannerConfig['default_image_size']}\""
                        : '',
                ],
                'hover_image' => [
                    'type' => 'file',
                    'label' => 'Hover Image',
                    'id' => 'hoverImageInput',
                    'col' => 'col-md-4',
                    'extra' => !empty($bannerConfig['default_hover_image_size'])
                        ? "data-size=\"{$bannerConfig['default_hover_image_size']}\""
                        : '',
                ],
                'alt_text' => ['type' => 'text', 'label' => 'Alt Text', 'id' => 'altTextInput', 'col' => 'col-md-4'],
            ];

            $specialFields = [
                'hover_card' => ['product_sku', 'btn_text', 'btn_color', 'content'],
                'four_hover_cards' => ['product_sku', 'btn_text', 'btn_color', 'content'],
                'block_wrap' => ['btn_text', 'btn_color', 'content'],
                'sale_block_fullwidth' => ['btn_text', 'btn_color', 'content'],
                'category_sale_block' => ['btn_text', 'btn_color', 'content'],
                'subscribe_banner' => ['content'],
                'flow_banner' => ['content'],
                'hero' => ['btn_text'],
                'category_page_banner' => ['content'],
                'login_page_banner' => ['content'],
                'app_journey_screen' => ['btn_text', 'btn_color', 'skip_btn_text', 'skip_btn_color'],
                'app_splash_logo' => ['bg_color'],
                'home_page_top_category_banner' => ['option'],
                'book_collection_banner' => ['sub_title'],
                'shop_details_page_banner' => ['single_option'],
                'mega_menu_banner' => ['single_option'],
                // 'category_page_headline_banner' => ['content'],
            ];

            $specialInputs = [
                'product_sku' => [
                    'type' => 'text',
                    'label' => 'Product SKU',
                    'id' => 'productSkuInput',
                    'col' => 'col-md-4',
                ],
                'btn_text' => ['type' => 'text', 'label' => 'Button Text', 'id' => 'btnTextInput', 'col' => 'col-md-4'],
                'btn_color' => [
                    'type' => 'color',
                    'label' => 'Button Color',
                    'id' => 'btnColorInput',
                    'value' => '#000000',
                    'class' => 'form-control-color',
                    'col' => 'col-md-4',
                ],
                'skip_btn_text' => [
                    'type' => 'text',
                    'label' => 'Skip Button Text',
                    'id' => 'skipbtnTextInput',
                    'col' => 'col-md-4',
                ],
                'skip_btn_color' => [
                    'type' => 'color',
                    'label' => 'Skip Button Color',
                    'id' => 'skipbtnColorInput',
                    'value' => '#000000',
                    'class' => 'form-control-color',
                    'col' => 'col-md-4',
                ],
                'content' => [
                    'type' => 'textarea',
                    'label' => 'Content',
                    'id' => 'content',
                    'rows' => 3,
                    'col' => 'col-md-12',
                ],
                'bg_color' => [
                    'type' => 'color',
                    'label' => 'Background Color',
                    'id' => 'backgroundColorInput',
                    'value' => '#000000',
                    'class' => 'form-control-color',
                    'col' => 'col-md-4',
                ],
                'option' => [
                    'type' => 'select',
                    'label' => 'Top Categories',
                    'id' => 'optionInput',
                    'name' => 'option[]',
                    'col' => 'col-md-8',
                    'multiple' => true,
                    'class' => 'form-select select2',
                    'options' => $categories,
                ],
                'sub_title' => [
                    'type' => 'text',
                    'label' => 'Sub Title',
                    'id' => 'subTitleInput',
                    'col' => 'col-md-4',
                ],
                'single_option' => [
                    'type' => 'select',
                    'label' => 'Select Category',
                    'id' => 'singleOptionInput',
                    'name' => 'single_option',
                    'col' => 'col-md-4',
                    'multiple' => false,
                    'class' => 'form-select select2',
                    'options' => $key === 'mega_menu_banner' ? $menu_items : $parent_categories,
                ],
            ];
          @endphp

          @foreach ($fields as $name => $config)
            @if (!empty($bannerConfig[$name]))
              <div class="{{ $config['col'] ?? 'col-md-6' }}">
                <label>
                  {{ $config['label'] ?? ucfirst($name) }}

                  {{-- Size hints --}}
                  @if ($name === 'image' && !empty($bannerConfig['default_image_size']))
                    <span class="text-muted">({{ $bannerConfig['default_image_size'] }})</span>
                  @elseif ($name === 'hover_image' && !empty($bannerConfig['default_hover_image_size']))
                    <span class="text-muted">({{ $bannerConfig['default_hover_image_size'] }})</span>
                  @endif
                </label>

                {{-- Main Image --}}
                @if ($name === 'image')
                  <input type="file" class="form-control {{ $config['class'] ?? '' }}" name="image"
                    id="{{ $config['id'] ?? 'imageInput' }}" {{ $config['extra'] ?? '' }}>

                  <input type="hidden" name="image_existing" id="imageExistingInput">

                  <div id="imagePreviewWrapper" class="mt-2" style="display:none;">
                    <img id="imagePreview" src="" alt="Preview" style="max-width: 60%; height: auto;">
                  </div>

                  {{-- Hover Image --}}
                @elseif ($name === 'hover_image')
                  <input type="file" class="form-control {{ $config['class'] ?? '' }}" name="hover_image"
                    id="{{ $config['id'] ?? 'hoverImageInput' }}" {{ $config['extra'] ?? '' }}>

                  <input type="hidden" name="hover_image_existing" id="hoverImageExistingInput">

                  <div id="hoverImagePreviewWrapper" class="mt-2" style="display:none;">
                    <img id="hoverImagePreview" src="" alt="Hover Preview"
                      style="max-width: 60%; height: auto;">
                  </div>

                  {{-- Default input --}}
                @else
                  <input type="{{ $config['type'] ?? 'text' }}" class="form-control {{ $config['class'] ?? '' }}"
                    name="{{ $name }}" id="{{ $config['id'] ?? $name }}"
                    value="{{ $config['value'] ?? '' }}">
                @endif
              </div>
            @endif
          @endforeach



          @if (!empty($specialFields[$key]))
            @foreach ($specialFields[$key] as $name)
              <div class="{{ $specialInputs[$name]['col'] }}">
                <label>{{ $specialInputs[$name]['label'] }}</label>
                @if ($specialInputs[$name]['type'] === 'textarea')
                  <textarea class="form-control" name="{{ $name }}" id="{{ $specialInputs[$name]['id'] }}"
                    rows="{{ $specialInputs[$name]['rows'] }}"></textarea>
                @elseif ($specialInputs[$name]['type'] === 'select')
                  <select name="{{ $specialInputs[$name]['name'] ?? $name }}" id="{{ $specialInputs[$name]['id'] }}"
                    class="{{ $specialInputs[$name]['class'] ?? 'form-control' }}"
                    @if (!empty($specialInputs[$name]['multiple'])) multiple="multiple" @endif>
                    @foreach ($specialInputs[$name]['options'] as $value => $label)
                      <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                  </select>
                @else
                  <input type="{{ $specialInputs[$name]['type'] }}"
                    class="form-control {{ $specialInputs[$name]['class'] ?? '' }}" name="{{ $name }}"
                    id="{{ $specialInputs[$name]['id'] }}"
                    @if (!empty($specialInputs[$name]['value'])) value="{{ $specialInputs[$name]['value'] }}" @endif>
                @endif
              </div>
            @endforeach
          @endif
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" id="cancelFormBtn" class="btn btn-secondary">Cancel</button>
      </form>

      <div class="dd" id="bannerListContainer">
        <ol class="dd-list list-group" id="bannerList">
          @foreach ($customBanners as $banner)
            @php $settings = json_decode($banner->settings, true) ?: []; @endphp
            <li class="dd-item list-group-item d-flex align-items-center py-3 px-1"
              data-id="{{ Hashids::encode($banner->id) }}" data-settings="{{ htmlentities(json_encode($settings)) }}">
              <div class="dd-handle w-100">
                <i class="ri-drag-move-2-line text-muted"></i>
                <div class="flex-grow-1 d-flex align-items-center ms-2">
                  @if (!empty($settings['image']))
                    <img src="{{ asset("public/storage/uploads/banners/{$settings['image']}") }}" class="rounded me-3"
                      style="width: 70px; height: 70px; object-fit: contain;"
                      alt="{{ $settings['alt_text'] ?? 'Banner' }}">
                  @elseif (!empty($settings['coordinates_image']))
                    <img src="{{ asset("public/storage/uploads/banners/{$settings['coordinates_image']}") }}"
                      class="rounded me-3" style="width: 70px; height: 70px; object-fit: contain;"
                      alt="{{ $settings['alt_text'] ?? 'Banner' }}">
                  @else
                    <div class="me-3" style="width: 70px; height: 70px;"></div>
                  @endif

                  @if ($key != 'hero' && $key != 'four_hover_cards')
                    <strong class="banner-title">{{ $banner->title ?: '' }}</strong>
                  @endif
                  @if ($key == 'home_page_top_category_banner')
                    <strong class="banner-title">Top Categories</strong>
                  @endif

                </div>
              </div>
              <span class="btn btn-success btn-sm button-edit pt-4" data-id="{{ Hashids::encode($banner->id) }}"
                data-title="{{ $banner->title ?? '' }}" data-sub_title="{{ $banner->sub_title ?? '' }}"
                data-hyper="{{ $settings['hyper_link'] ?? '' }}" data-alt="{{ $settings['alt_text'] ?? '' }}"
                data-image="{{ $settings['image'] ?? '' }}" data-hoverimage="{{ $settings['hover_image'] ?? '' }}"
                data-product_sku="{{ $settings['product_sku'] ?? '' }}"
                data-content="{{ $settings['content'] ?? '' }}" data-btn_text="{{ $settings['btn_text'] ?? '' }}"
                data-btn_color="{{ $settings['btn_color'] ?? '' }}"
                data-skip_btn_text="{{ $settings['skip_btn_text'] ?? '' }}"
                data-skip_btn_color="{{ $settings['skip_btn_color'] ?? '' }}"
                data-bg_color="{{ $settings['bg_color'] ?? '' }}" data-custom_order="{{ $banner->custom_order }}"
                data-option="{{ json_encode($settings['options'] ?? []) }}"
                data-single_option="{{ $settings['single_option'] ?? '' }}">

                <i class="ri-pencil-line"></i>
              </span>
              <span class="btn btn-danger btn-sm button-delete pt-4 pe-2" data-id="{{ Hashids::encode($banner->id) }}"
                style="cursor: pointer;">
                <i class="ri-delete-bin-line"></i>
              </span>
            </li>
          @endforeach
        </ol>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="d-flex justify-content-between align-items-center">
            <div class="back-btn">
              <a href="{{ route('admin.banners') }}" title="Back"
                class="d-flex justify-content-start align-items-center font-16">
                <i class="uil-angle-left font-18 me-1"></i>Back
              </a>
            </div>
            @if ($customBanners->count() > 2)
              <button id="saveOrderBtn" class="btn btn-primary mt-3">Save Order</button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-scripts')
  <script src="{{ asset('/public/common/js/custom_input.js?v=' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
  <script src="{{ asset('/public/common/js/nestable.min.js?v=' . time()) }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/ckeditor5.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('bannerImageInput_book_collection_banner');
      const preview = document.getElementById('bannerImagePreview');
      const editor = document.querySelector('.hotspot-editor');
      const container = document.getElementById('hotspotContainer');
      const hotspotData = document.getElementById('hotspotData');
      let hotspots = [];

      // Preview the image on change
      input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(event) {
          preview.src = event.target.result;
          editor.style.display = 'inline-block';
          hotspots = []; // reset hotspots when new image is chosen
          container.innerHTML = '';
          hotspotData.value = '';
        };
        reader.readAsDataURL(file);
      });

      // Add hotspot on image click
      preview.addEventListener('click', function(e) {
        const rect = preview.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const leftPercent = (x / rect.width) * 100;
        const topPercent = (y / rect.height) * 100;

        const sku = prompt('Enter Product SKU:');
        if (!sku) return;

        const spot = {
          product_sku: sku,
          top: topPercent.toFixed(2) + '%',
          left: leftPercent.toFixed(2) + '%'
        };

        hotspots.push(spot);
        updateHotspots();
      });

      // Update hotspot display and hidden input
      function updateHotspots() {
        container.innerHTML = '';
        hotspots.forEach((spot, index) => {
          const div = document.createElement('div');
          div.className = 'hotspot';
          div.style.top = spot.top;
          div.style.left = spot.left;
          div.title = `SKU: ${spot.product_sku}`;
          div.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            hotspots.splice(index, 1);
            updateHotspots();
          });
          container.appendChild(div);
        });
        hotspotData.value = JSON.stringify(hotspots);
      }

      // Restore saved hotspots when editing an existing banner
      const existing = hotspotData.value ? JSON.parse(hotspotData.value) : null;
      if (existing && Array.isArray(existing) && existing.length > 0) {
        editor.style.display = 'inline-block';
        hotspots = existing;
        updateHotspots();
      }
    });



    $('#optionInput').select2({
      placeholder: 'Select Categories',
      // maximumSelectionLength: 4 // Restrict to 4 selections
    });

    $('#singleOptionInput').select2({
      placeholder: 'Select Category',
      // maximumSelectionLength: 4 // Restrict to 4 selections
    });
    $.validator.addMethod("imageSize", (value, element, param) => {
      if (!element.files.length || !$(element).data('size')) return true;
      const [width, height] = $(element).data('size').split('x').map(s => parseInt(s.trim()));
      const file = element.files[0];
      const img = new Image();
      const deferred = $.Deferred();
      img.onload = () => deferred.resolve(img.width === width && img.height === height);
      img.onerror = () => deferred.resolve(false);
      img.src = URL.createObjectURL(file);
      return deferred.promise();
    }, (params, element) => `Image size must be exactly ${$(element).data('size')}`);

    const ckeditor5Instances = {};
    const contentElement = document.querySelector('#content');
    if (contentElement) {
      ClassicEditor.create(contentElement, {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote',
          'insertTable', 'undo', 'redo', '|', 'sourceEditing'
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
      }).then(editor => {
        ckeditor5Instances.content = editor;
        editor.ui.view.editable.element.style.height = '300px';
      }).catch(error => console.error('CKEditor error:', error));
    }

    const imageInput = document.getElementById('imageInput');
    if (imageInput) {
      imageInput.addEventListener('change', ({
        target: {
          files
        }
      }) => {
        const wrapper = document.getElementById('imagePreviewWrapper');
        const preview = document.getElementById('imagePreview');
        if (files[0]) {
          const reader = new FileReader();
          reader.onload = ({
            target: {
              result
            }
          }) => {
            preview.src = result;
            wrapper.style.display = 'block';
          };
          reader.readAsDataURL(files[0]);
        } else {
          wrapper.style.display = 'none';
        }
      });
    }

    const hoverImageInput = document.getElementById('hoverImageInput');
    if (hoverImageInput) {
      hoverImageInput.addEventListener('change', ({
        target: {
          files
        }
      }) => {
        const wrapper = document.getElementById('hoverImagePreviewWrapper');
        const preview = document.getElementById('hoverImagePreview');
        if (files[0]) {
          const reader = new FileReader();
          reader.onload = ({
            target: {
              result
            }
          }) => {
            preview.src = result;
            wrapper.style.display = 'block';
          };
          reader.readAsDataURL(files[0]);
        } else {
          wrapper.style.display = 'none';
        }
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('addBannerForm');
      const toggleBtn = document.getElementById('toggleFormBtn');
      const cancelBtn = document.getElementById('cancelFormBtn');

      toggleBtn.addEventListener('click', () => {
        form.classList.toggle('d-none');
        form.reset();
        $('#bannerId, #customOrderInput, #imagePreviewWrapper, #hoverImagePreviewWrapper, #imageExistingInput, #hoverImageExistingInput')
          .val('').hide();
        @if (!empty($bannerConfig['speed']))
          document.getElementById('globalSpeedInput').value = '{{ $globalSpeed }}';
        @endif
        if (ckeditor5Instances.content) ckeditor5Instances.content.setData('');
        else $('#content').val('');
      });

      cancelBtn.addEventListener('click', () => {
        form.classList.add('d-none');
        form.reset();
        $('#imagePreviewWrapper').hide();
        $('#hoverImagePreviewWrapper').hide();
        $('#imagePreview, #imageExistingInput, #hoverImagePreview, #hoverImageExistingInput').val('');
        if (ckeditor5Instances.content) ckeditor5Instances.content.setData('');
        else $('#content').val('');
      });

      $("#addBannerForm").validate({
        rules: {
          @if (!empty($bannerConfig['title']))
            title: {
              required: true,
              maxlength: 255
            },
          @endif
          @if (!empty($bannerConfig['sub_title']))
            sub_title: {
              required: true,
              maxlength: 255
            },
          @endif
          @if (!empty($bannerConfig['hyper_link']))
            hyper_link: {
              url: true
            },
          @endif
          @if (!empty($bannerConfig['image']))
            image: {
              @if (empty($customBanners->first()))
                required: true,
              @endif
              extension: "jpg|jpeg|png|webp|gif",
              imageSize: false
            },
          @endif
          @if (!empty($bannerConfig['hover_image']))
            hover_image: {
              @if (empty($customBanners->first()))
                required: true,
              @endif
              extension: "jpg|jpeg|png|webp|gif",
              imageSize: false
            },
          @endif
          @if (!empty($bannerConfig['alt_text']))
            alt_text: {
              maxlength: 255
            },
          @endif
          @if (in_array($key, ['hover_card', 'four_hover_cards']))
            product_sku: {
              maxlength: 100
            },
            btn_text: {
              maxlength: 100
            },
            content: {
              minlength: 10
            },
          @elseif (in_array($key, ['block_wrap', 'sale_block_fullwidth']))
            btn_text: {
                maxlength: 100
              },
              content: {
                minlength: 10
              },
          @elseif (in_array($key, ['subscribe_banner', 'flow_banner', 'hero']))
            content: {
                minlength: 10
              },
          @elseif (in_array($key, ['app_journey_screen']))
            skip_btn_text: {
                maxlength: 100
              },
          @endif
          'option[]': {
            required: true,
            maxlength: 4
          },
          single_option: {
            required: true
          }
        },
        messages: {
          'option[]': {
            required: "Please select at least one category",
            maxlength: "You can select up to 4 categories only"
          },
          'single_option': {
            required: "Please select a category",

          },
          title: {
            required: "Title is required",
            maxlength: "Title cannot exceed 255 characters"
          },
          sub_title: {
            required: "Sub Title is required",
            maxlength: "Sub Title cannot exceed 255 characters"
          },
          hyper_link: {
            url: "Please enter a valid URL"
          },
          image: {
            required: "Please upload an image.",
            extension: "Only jpg, jpeg, png, webp, gif files allowed."
          },
          alt_text: {
            maxlength: "Alt Text cannot exceed 255 characters"
          },
          product_sku: {
            maxlength: "Product SKU cannot exceed 100 characters"
          },
          btn_text: {
            maxlength: "Button Text cannot exceed 100 characters"
          },
          skip_btn_text: {
            maxlength: "Skip Button Text cannot exceed 100 characters"
          },
          content: {
            minlength: "Content must be at least 10 characters long"
          }
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid'),
        errorPlacement: function(error, element) {
          if (element.attr("name") === "option[]") {
            error.insertAfter(element.next('.select2-container'));
          } else {
            error.insertAfter(element.type === "file" ? element.parent() : element);
          }
        },

        submitHandler: form => {
          fetch(`{{ route('admin.banners.store') }}`, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
              },
              body: new FormData(form)
            })
            .then(async res => {
              let data;

              try {
                data = await res.clone().json();
              } catch (err) {
                const html = await res.text();
                console.error('Non-JSON response:', html);
                swalNotify('Error', 'Unexpected server response. Please reload the page.', 'error');
                return;
              }

              if (!res.ok) {
                if (res.status === 422) {
                  const errors = data.errors;

                  $(form).find('.is-invalid').removeClass('is-invalid');
                  $(form).find('.invalid-feedback').remove();

                  // Display field errors
                  for (const field in errors) {
                    const input = $(`[name="${field}"]`);
                    input.addClass('is-invalid');

                    if (input.next('.invalid-feedback').length === 0) {
                      input.after(`<span class="invalid-feedback d-block">${errors[field][0]}</span>`);
                    }
                  }

                  swalNotify('Validation Error', Object.values(errors).flat()[0], 'error');
                } else {
                  swalNotify('Error', data.message || 'Something went wrong', 'error');
                }

                throw new Error('Request failed');
              }

              swalNotify('Success', data.message, 'success');
              location.reload();
            })
            .catch(err => {
              console.error(err);
            });
        }
      });



      $('.button-edit').on('click', function() {
        const btn = $(this);
        const settings = btn.data('settings') ? JSON.parse(btn.attr('data-settings')) : {};
        const key = '{{ $key }}';
        // Handle coordinates image preview

        $('#bannerId').val(btn.data('id'));
        $('#titleInput').val(btn.data('title'));
        $('#subTitleInput').val(btn.data('sub_title'));
        $('#hyperlinkInput').val(btn.data('hyper'));
        // $('#globalSpeedInput').val(btn.data('speed'));
        $('#altTextInput').val(btn.data('alt'));
        $('#customOrderInput').val(btn.data('custom_order'));
        $('#productSkuInput').val(btn.data('product_sku') ?? '');
        $('#btnTextInput').val(btn.data('btn_text') ?? '');
        $('#btnColorInput').val(btn.data('btn_color') ?? '#000000');
        $('#skipbtnTextInput').val(btn.data('skip_btn_text') ?? '');
        $('#skipbtnColorInput').val(btn.data('skip_btn_color') ?? '#000000');
        $('#backgroundColorInput').val(btn.data('bg_color') ?? '#000000');
        if (ckeditor5Instances.content) ckeditor5Instances.content.setData(btn.data('content') ?? '');
        else $('#content').val(btn.data('content') ?? '');

        // Handle Select2 for categories
        const options = btn.data('option') || [];
        $('#optionInput').val(options).trigger('change');
        $('#singleOptionInput').val(btn.data('single_option')).trigger('change');

        const imageName = btn.data('image');
        const hoverImageName = btn.data('hoverimage');
        if (imageName) {
          $('#imagePreview').attr('src', `{{ asset('public/storage/uploads/banners') }}/${imageName}`);
          $('#imagePreviewWrapper, #imageExistingInput').val(imageName).show();
        } else {
          $('#imagePreviewWrapper, #imageExistingInput').val('').hide();
        }
        if (hoverImageName) {
          $('#hoverImagePreview').attr('src', `{{ asset('public/storage/uploads/banners') }}/${hoverImageName}`);
          $('#hoverImagePreviewWrapper, #hoverImageExistingInput').val(hoverImageName).show();
        } else {
          $('#hoverImagePreviewWrapper, #hoverImageExistingInput').val('').hide();
        }
        $('#addBannerForm').removeClass('d-none');
      });

      $('.button-delete').on('click', function() {
        swalConfirm("Are you sure?", "You won't be able to revert this!").then(result => {
          if (result.isConfirmed) {
            $.ajax({
              url: '{{ route('admin.banners.delete', ':id') }}'.replace(':id', $(this).data(
                'id')),
              method: 'POST',
              data: {
                id: $(this).data('id'),
                _token: '{{ csrf_token() }}'
              },
              success: ({
                message
              }) => {
                swalNotify('Success', message || 'Banner deleted successfully.', 'success');
                location.reload();
              },
              error: () => swalNotify('Error', 'Failed to delete banner.', 'error')
            });
          }
        });
      });

      const globalSpeedForm = document.getElementById('globalSpeedForm');



      $("#globalSpeedForm").validate({
        rules: {
          @if (!empty($banner_config_speed))
            speed: {
              required: true,
              digits: true,
              min: 1,
              max: 10,
              pattern: /^[1-9]$|^10$/
            },
          @endif
        },

        messages: {
          speed: {
            required: "Speed is required",
            digits: "Speed must be a number",
            min: "Minimum value is 1.",
            max: "Maximum value is 10.",
            pattern: "Leading zeros are not allowed."
          },
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid'),
        errorPlacement: (error, element) => error.insertAfter(element.type === "file" ? element.parent() :
          element),
        submitHandler: form => {
          const formData = new FormData(form);

          fetch("{{ route('admin.banners.save-speed') }}", {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              swalNotify('Success', 'Banner speed updated.', 'success');
            })
            .catch(error => {
              swalNotify('Error', 'Banner speed update failed.', 'error');
            });
        }
      });



      const globalFourHoverTitleForm = document.getElementById('globalFourHoverTitleForm');



      $("#globalFourHoverTitleForm").validate({
        rules: {
          @if (!empty($banner_four_hover_card_title))
            title: {
              required: true,

              maxlength: 255
              // min: 1,
              // max: 10,
              // pattern: /^[1-9]$|^10$/
            },
          @endif
        },

        messages: {
          title: {
            required: "Title is required",
            maxlength: "Title cannot exceed 255 characters"
          },
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid'),
        errorPlacement: (error, element) => error.insertAfter(element.type === "file" ? element.parent() :
          element),
        submitHandler: form => {
          const formData = new FormData(form);

          fetch("{{ route('admin.banners.save-global-title') }}", {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              swalNotify('Success', 'Main Title updated.', 'success');
            })
            .catch(error => {
              swalNotify('Error', 'Main Title update failed.', 'error');
            });
        }
      });



      $('#bannerListContainer').nestable({
        maxDepth: 1
      });

      $('#saveOrderBtn').on('click', () => {
        const order = $('#bannerListContainer').nestable('serialize').map(item => item.id);
        $.ajax({
          url: '{{ route('admin.banners.update-order') }}',
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          data: {
            custom_order: order
          },
          success: () => swalNotify('Success', 'Banner order updated.', 'success'),
          error: () => swalNotify('Error', 'Order update failed.', 'error')
        });
      });
    });
  </script>
@endsection
