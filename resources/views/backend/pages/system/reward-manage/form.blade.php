@extends('backend.layouts.app')
@section('page-styles')
    <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <x-breadcrumb :pageTitle="$pageTitle" :skipLevels="$reward->id ? [1] : []" />
    <x-form-card :formTitle="$cardHeader" :backRoute="route('admin.scratch-card-rewards')" :formId="'rewardForm'">
        <div class="row">
            {{-- <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="type" class="form-label">Reward Type</label>
                    <select name="type" id="type" class="form-select select2" required>
                        <option value="">Select a Type</option>
                        @foreach(['fixed' => 'Fixed Discount', 'percentage' => 'Percentage Discount', 'coupon' => 'Coupon'] as $value => $label)
                            <option value="{{ $value }}" {{ ($reward->type ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
             <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="type" class="form-label">Reward Type</label>
                    <select name="type" id="type" class="form-select select2" required>
                        <option value="">Select a Type</option>
                        @foreach(['coupon' => 'Coupon'] as $value => $label)
                            <option value="{{ $value }}" {{ ($reward->type ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="value" class="form-label">Amount</label>
                    <input type="text" name="value" id="value" class="form-control only-pricing"
                           value="{{ $reward->value ?? '' }}"  placeholder="e.g. 100.00">
                    {{-- <span class="text-muted">E.g., 5.00 for $5 off, 10.00 for 10% off, or 0.00 for coupons</span> --}}
                </div>
            </div>

            {{-- <div class="col-md-4" id="coupon_code_container" style="display: {{ ($reward->type ?? '') === 'coupon' ? 'block' : 'none' }};">
                <div class="mb-3 required">
                    <label for="coupon_code" class="form-label">Coupon Code</label>
                    <div class="input-group">
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control"
                               value="{{ isset($reward->conditions['coupon_code']) ? $reward->conditions['coupon_code'] : '' }}"
                               {{ ($reward->type ?? '') === 'coupon' ? 'required' : '' }}>
                        <button type="button" id="generate_coupon" class="btn btn-success">Generate</button>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-4" id="coupon_code_container" style="display: {{ ($reward->type ?? '') === 'coupon' ? 'block' : 'none' }};">
                <div class="mb-3 required">
                    <label for="coupon_code" class="form-label">Coupon Code</label>
                    <div class="input-group">
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control"
                               value="{{ $reward->coupon_code ?? '' }}"
                               {{ ($reward->type ?? '') === 'coupon' ? 'required' : '' }}>
                        <button type="button" id="generate_coupon" class="btn btn-success">Generate</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="reward_valid_from" class="form-label">Valid From</label>
                    <input type="text" class="form-control datepicker" name="valid_from" id="reward_valid_from"
                           value="{{ $reward->valid_from ? formatDate($reward->valid_from,'d/m/Y') : '' }}" autocomplete="new-date">
                    <div id="valid_from-error-container" class="invalid-feedback"></div>
                    <small class="form-text text-muted">Start date</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="reward_valid_to" class="form-label">Valid To</label>
                    <input type="text" class="form-control datepicker" name="valid_to" id="reward_valid_to"
                           value="{{ $reward->valid_to ? formatDate($reward->valid_to,'d/m/Y') : '' }}" autocomplete="new-date">
                    <div id="valid_to-error-container" class="invalid-feedback"></div>
                    <small class="form-text text-muted">End date</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3 required">
                    <label for="validity_period" class="form-label">Validity Period (Days)</label>
                    <input type="text" name="validity_period" id="validity_period" class="form-control only-integers"
                           value="{{ $reward->validity_period ?? 15 }}" >
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3 required">
                    <label for="product_type" class="form-label">Product Eligibility</label>
                    <select name="product_type" id="product_type" class="form-select select2">
                        <option value="any" {{ isset($reward->conditions['product']) && $reward->conditions['product'] === 'any' ? 'selected' : '' }}>Any Product</option>
                        <option value="specific" {{ isset($reward->conditions['product_ids']) ? 'selected' : '' }}>Specific Products</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6" id="product_ids_container" style="display: {{ isset($reward->conditions['product_ids']) ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="product_ids" class="form-label">Product Selection</label>
                    <select name="product_ids[]" id="product_ids" class="form-select select2" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                    {{ isset($reward->conditions['product_ids']) && in_array($product->id, $reward->conditions['product_ids']) ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-muted">Select multiple products if specific</span>
                </div>
            </div>
        </div>
    </x-form-card>
@endsection
@push('component-styles')
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <style>
    .invalid-feedback {
      all: unset;
    }
  </style>
@endpush
@section('page-scripts')
    <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
    <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
    <script>
        const rewardData = @json($reward ?? []);
    </script>
    <script>
        $('#type').select2({
            placeholder: 'Select a Reward Type',
        });

        $('#product_type').select2({
            placeholder: 'Select Product Eligibility',
        });

        $('#product_ids').select2({
            placeholder: 'Select Products',
        });

        $('#type').on('change', function () {
            const isCoupon = this.value === 'coupon';
            $('#coupon_code_container').css('display', isCoupon ? 'block' : 'none');
            $('#coupon_code').prop('required', isCoupon);
            if (!isCoupon) {
                $('#coupon_code').val('');
            }
        });

        $('#product_type').on('change', function () {
            $('#product_ids_container').css('display', this.value === 'specific' ? 'block' : 'none');
            if (this.value !== 'specific') {
                $('#product_ids').val(null).trigger('change');
            }
        });

        $('#generate_coupon').on('click', function () {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let coupon = '';
            for (let i = 0; i < 12; i++) {
                coupon += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            $('#coupon_code').val(coupon);
        });

        if (window.location.href.includes('/create')) {
          $('#reward_valid_from').val('');
          $('#reward_valid_to').val('');
        }

        // Initialize Datepicker
        $('.datepicker').datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            minDate: 0,
            onClose: function() {
                validateDateRange();
                $(this).valid();
            }
        });

        $('.datepicker').on('keydown paste', function(e) {
            e.preventDefault();
        });

        // Date validation
        function parseDate(str) {
            const parts = str.split('/');
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }

        function validateDateRange() {
            const fromStr = $('#reward_valid_from').val();
            const toStr = $('#reward_valid_to').val();

            let valid = true;
            $('#reward_valid_from').removeClass('is-invalid');
            $('#reward_valid_to').removeClass('is-invalid');

            if (fromStr && toStr) {
                const fromDate = parseDate(fromStr);
                const toDate = parseDate(toStr);

                if (toDate < fromDate) {
                    $('#reward_valid_to').addClass('is-invalid');
                    $('#valid_to-error-container').text('Valid To must be same or after Valid From');
                    valid = false;
                }
            }

            return valid;
        }

        $('#reward_valid_from, #reward_valid_to').on('change', validateDateRange);

        // jQuery Validator custom methods
        $.validator.addMethod("date", function(value, element) {
            if (this.optional(element)) return true;
            const bits = value.split('/');
            const d = new Date(bits[2], bits[1] - 1, bits[0]);
            return d && (d.getMonth() + 1) == bits[1] && d.getDate() == Number(bits[0]);
        }, "Please enter a valid date");

        $.validator.addMethod("greaterThanOrEqual", function(value, element, param) {
            const from = $(param).val();
            if (!value || !from) return true;
            return parseDate(value) >= parseDate(from);
        }, "Valid To must be same or after Valid From");

        $('#rewardForm').validate({
            rules: {
                type: {
                    required: true
                },
                value: {
                    required: true,
                    number: true,
                    min: 0.01,
                    max: 999999999
                },
                coupon_code: {
                    required: function() {
                        return $('#type').val() === 'coupon';
                    },
                    minlength: 6,
                    maxlength: 20
                },
                valid_from: {
                    required: true,
                    date: true
                },
                valid_to: {
                    required: true,
                    date: true,
                    greaterThanOrEqual: '#reward_valid_from'
                },
                validity_period: {
                  required: true,
                  min: 1
                },
                product_type: {
                    required: true
                },
                'product_ids[]': {
                    required: function() {
                        return $('#product_type').val() === 'specific';
                    }
                }
            },
            messages: {
                type: {
                    required: "{{ __('validation.required', ['attribute' => 'Reward Type']) }}"
                },
                value: {
                    required: "{{ __('validation.required', ['attribute' => 'Amount']) }}",
                    number: "{{ __('validation.number', ['attribute' => 'Amount']) }}",
                    min: "{{ __('validation.min', ['attribute' => 'Amount', 'min' => '0.01']) }}",
                    max: "{{ __('validation.max', ['attribute' => 'Amount', 'max' => '999999999']) }}"
                },
                coupon_code: {
                    required: "{{ __('validation.required', ['attribute' => 'Coupon Code']) }}",
                    minlength: "{{ __('validation.minlength', ['attribute' => 'Coupon Code', 'min' => '6']) }}",
                    maxlength: "{{ __('validation.maxlength', ['attribute' => 'Coupon Code', 'max' => '20']) }}"
                },
                valid_from: {
                    required: "{{ __('validation.required', ['attribute' => 'Valid From']) }}"
                },
                valid_to: {
                    required: "{{ __('validation.required', ['attribute' => 'Valid To']) }}"
                },
                validity_period: {
                  required: "{{ __('validation.required', ['attribute' => 'Validity Period']) }}",
                  min: "{{ __('validation.min', ['attribute' => 'Validity Period', 'min' => '1']) }}"
                },
                product_type: {
                    required: "{{ __('validation.required', ['attribute' => 'Product Eligibility']) }}"
                },
                'product_ids[]': {
                    required: "{{ __('validation.required', ['attribute' => 'Product Selection']) }}"
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
              if (!validateDateRange()) {
                    swalNotify("Error!", "Please correct the date fields.", "error");
                    return false;
                }
                let url = "{{ route('admin.scratch-card-rewards.store') }}";
                if (rewardData.id) {
                    url = `{{ route('admin.scratch-card-rewards.update', ':id') }}`.replace(':id', @json(Hashids::encode($reward->id ?? '')));
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
                        swalNotify("Error!", error.responseJSON.message, "error");
                    }
                });
            }
        });
    </script>
@endsection
