@extends('backend.layouts.app')
@section('page-styles')
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$store->id ? [1] : []" />
    <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.stores')" :formId="'storeForm'">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 required">
                    <label for="name" class="form-label">Store Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $store->name ?? '' }}">
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3 required">
                    <label for="address" class="form-label">Address</label>
                    {{-- <textarea name="address" id="address" class="form-control" rows="6" >{{ $store->address ?? '' }}</textarea> --}}
                    <input type="text" name="address" id="address" class="form-control" value="{{ $store->address ?? '' }}" >
                </div>
            </div>


            <div class="col-md-4">
                <div class="mb-3">
                    <label for="country" class="form-label">Country</label>
                    <select name="country_id" id="country" class="form-control select2">
                        <option value="" selected>Select Country</option>
                      @forelse ($countries as $country)
                        <option value="{{ Hashids::encode($country->id) }}" {{ $country->id == $store->id ? 'selected' : '' }}>{{ $country->name }}</option>
                      @empty
                      @endforelse
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ $store->state ?? '' }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ $store->city ?? '' }}" >
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pincode" class="form-label">Pincode</label>
                    <input type="text" name="pincode" id="pincode" class="form-control" value="{{ $store->pincode ?? '' }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3 required">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ $store->phone ?? '' }}">
                </div>
            </div>

            {{-- <div class="col-md-3">
                <div class="mb-3 ">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" name="latitude" id="latitude" class="form-control" value="{{ $store->latitude ?? '' }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3 ">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" name="longitude" id="longitude" class="form-control" value="{{ $store->longitude ?? '' }}">
                </div>
            </div> --}}

            <div class="col-md-12">
                <div class="mb-3 required">
                    <label for="location" class="form-label">Location (Google Map Link)</label>
                    <input type="text" name="location" id="location" class="form-control" value="{{ $store->location ?? '' }}">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3 not-required">
                    <label class="form-label">Image</label>
                    <input class="form-control" type="file" name="image" id="store-image" accept="image/*">
                    <span class="text-muted">Preferred Size: 266 x 180 px (Allowed Extensions: JPG, PNG, GIF & WebP)</span>
                    <div id="store-image-error-container" class="error"></div>
                </div>
            </div>

            <div class="col-md-6" id="store-image-preview-container" {{ $store->image ?? 'style = display:none' }}>
                <div class="mb-3 not-required">
                  <img
                    src="{{ $store->image ? asset('/public/storage/uploads/stores/' . $store->image) : '' }}"
                    class="img-thumbnail mt-3" id="store-image-preview">
                </div>
            </div>
        </div>
    </x-form-card>
@endsection

@section('page-scripts')
    <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
    <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>

    <script>
        const storeData = @json($store ?? []);
    </script>
    <script>
        let formID = `{{ Hashids::encode($store->id ?? '') }}`;

        $.validator.addMethod("lowercase", function(value, element) {
            return this.optional(element) || /^[a-z0-9-]+$/.test(value);
        }, "{{ __('validation.lowercase', ['attribute' => 'Field']) }}");

        $('#storeForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                address: {
                    required: true,
                    maxlength: 65535
                },
                city: {
                    required: false,
                    maxlength: 255
                },
                state: {
                    required: false,
                    maxlength: 255
                },
                country_id: {
                    required: false,
                },
                pincode: {
                    required: false,
                    maxlength: 20
                },
                location: {
                  required: true,
                  url: true,
                  maxlength: 255
                },
                phone: {
                  required: true,
                },
                // latitude: {
                //   required: true,
                // },
                // longitude: {
                //   required: true,
                // },
            },
            messages: {
                name: {
                  required: "{{ __('validation.required', ['attribute' => 'Store Name']) }}",
                  minlength: "{{ __('validation.minlength', ['attribute' => 'Store Name', 'min' => '3']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Store Name', 'max' => '255']) }}"
                },
                address: {
                  required: "{{ __('validation.required', ['attribute' => 'Address']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Address', 'max' => '65535']) }}"
                },
                city: {
                  required: "{{ __('validation.required', ['attribute' => 'City']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'City', 'max' => '255']) }}"
                },
                state: {
                  required: "{{ __('validation.required', ['attribute' => 'State']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'State', 'max' => '255']) }}"
                },
                country_id: {
                  required: "{{ __('validation.required', ['attribute' => 'Country']) }}",
                },
                pincode: {
                    required: "{{ __('validation.required', ['attribute' => 'Pincode']) }}",
                    maxlength: "{{ __('validation.maxlength', ['attribute' => 'Pincode', 'max' => '20']) }}"
                },
                location: {
                  url: "{{ __('validation.url', ['attribute' => 'Google map link']) }}",
                  maxlength: "{{ __('validation.maxlength', ['attribute' => 'Google map link', 'max' => '255']) }}"
                },
                phone: {
                  required: "{{ __('validation.required', ['attribute' => 'Phone']) }}",
                },
                latitude: {
                  required: "{{ __('validation.required', ['attribute' => 'Latitude']) }}",
                },
                longitude: {
                  required: "{{ __('validation.required', ['attribute' => 'Longitude']) }}",
                },
            },
            errorElement: "i",
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
                let url = "{{ route('admin.stores.store') }}";
                if (formID) {
                    url = `{{ route('admin.stores.update', ':id') }}`.replace(':id', formID);
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

        $('.select2').select2({
          'placeholder': 'Select a Country'
        });

        $('#store-image').change(function() {
            const file = this.files[0];
            const {
              type,
              size
            } = file;

            if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(type)) {
              $(this).val('');
              $('#store-image-error-container').text(
                'Invalid image type. Only JPEG, PNG, GIF, and WebP images are allowed.');
              return;
            }

            if (size > 2e+6) {
              $(this).val('');
              $('#store-image-error-container').text('Image size should not exceed 2MB.');
              return;
            }
            $('#store-image-error-container').text('');
            const reader = new FileReader();
            $('#store-image-preview-container').show();
            reader.onload = () => $('#store-image-preview').attr('src', reader.result);
            reader.readAsDataURL(file);
        })
    </script>
@endsection
