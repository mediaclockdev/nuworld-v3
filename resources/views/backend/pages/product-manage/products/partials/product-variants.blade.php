<form id="variationForm">
  <div class="row">
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="variant_name" class="form-label">Product Name</label>
        <input type="text" name="base_product_name" id="base_product_name" class="form-control"
          value="{{ $product->name ?? '' }}">
        <div id="variant_name-error-container"></div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="variant_name" class="form-label">Base SKU</label>
        <input type="text" name="base_product_sku" id="base_product_sku" class="form-control uppercase-slug"
          value="{{ $product->sku ?? '' }}">
        <div id="variant_name-error-container"></div>
      </div>
    </div>

    <div class="idvblock">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
              <h4 class="header-title mb-0">Product Filter Attributes</h4>
            </div>
            <div class="card-body">
              <div class="col-md-6">
                <select class="form-select multiple-attribute-select" name="attribute_ids[]" multiple>
                  <option value="">Select Attributes</option>
                  @foreach ($attributes as $attribute)
                    <option value=" {{ Hashids::encode($attribute->id) }}" data-attribute-name="{{ $attribute->name }}">
                      {{ $attribute->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="idvblock">
      <div class="row">
        <div class="col-md-12">
          <div class="card" id="attributeContainer">
            <div class="d-flex card-header justify-content-between align-items-center">
              <h4 class="header-title mb-0">Product Attributes </h4>
              <button type="button" class="btn btn-sm btn-primary" id="addAttributeBtn">
                <i class="fas fa-plus"></i> Add Attribute
              </button>
            </div>
            <div class="card-body" id="otherAttributesContainer">
              {{-- <!-- Dynamic attributes will be added here --> --}}
            </div>
            <div class="text-end card-body border-top d-flex justify-content-end">
              <button type="button" class="btn btn-success d-flex gap-2 font-15" id="generateVariationsBtn">
                Generate Variations
              </button>
            </div>
          </div>
          <div class="card">
            <div class="d-flex card-header justify-content-between align-items-center">
              <h4 class="header-title mb-0">Product Variations </h4>
            </div>
            <div class="card-body pt-2" id="variationsOutputContainer">
            </div>
          </div>
        </div>
      </div>

      {{-- <!-- Template for dynamic attribute rows (hidden) --> --}}
      <div class="d-none" id="attributeRowTemplate">
        <div class="attribute-row mb-3">
          <div class="row g-3 align-items-center">
            <div class="col-md-5">
              <select class="form-select select2 attribute-select">
                <option value="">Select Attribute</option>
                @foreach ($attributes as $attribute)
                  <option value="{{ Hashids::encode($attribute->id) }}" data-attribute-name="{{ $attribute->name }}">
                    {{ $attribute->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <select class="form-select select2 attribute-values-select" multiple="multiple">
              </select>
            </div>
            <div class="col-md-1 text-end">
              <a href="javascript:void(0)" class="action-icon text-danger remove-attribute-btn font-24"
                title="Remove Attribute">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@push('component-scripts')
  <script>
    let variationCount = 0;
    const allAttributeValues = {!! json_encode($attributeValues) !!};

    $(document).ready(function() {

      $('.multiple-attribute-select').select2({
        'placeholder': 'Select Attributes',
      });

      // Only destroy Select2 instances where it is already initialized
      $('.select2').each(function() {
        if ($(this).data('select2')) {
          $(this).select2('destroy');
        }
      })

      initAttributeRowEvents();
      $('#otherAttributesContainer').empty();
      if (variationCount === 0) {
        $('#addAttributeBtn').trigger('click');
        variationCount++;
      }
    });

    function initAttributeRowEvents() {
      $('#addAttributeBtn').on('click', function() {
        const template = $('#attributeRowTemplate').html();
        const newRow = $(template).appendTo('#otherAttributesContainer');
        // Destroy any existing Select2 instance on the new row if present
        newRow.find('.select2').each(function() {
          if ($(this).data('select2')) {
            $(this).select2('destroy');
          }
        });
        initAttributeRow(newRow);
      });

      $('#otherAttributesContainer').on('click', '.remove-attribute-btn', function() {
        $(this).closest('.attribute-row').remove();
      });

      // Delegate change event to dynamically added select2 elements
      $('#otherAttributesContainer').on('change', '.select2', function() {
        $(this).valid();
      });
    }

    function initAttributeRow(row) {
      // Initialize Select2 only on the new row's select elements
      row.find('.select2').select2();

      row.find('.attribute-select').on('change', function() {
        const attributeId = $(this).val();
        const attributeName = $(this).find('option:selected').data('attribute-name');
        const isColor = attributeName === 'Color';
        const valuesSelect = row.find('.attribute-values-select');
        valuesSelect.empty().append($('<option>', {
          value: ''
        }).prop('disabled', true).prop('hidden', true));

        if (!attributeId) return;

        allAttributeValues
          .filter(v => v.attribute_id == attributeId)
          .forEach(value => {
            valuesSelect.append($('<option>', {
              value: value.encoded_id,
              text: value.value,
              'data-value': value.value,
              'data-color': isColor ? value.value_details : null
            }));
          });

        const renderOption = data => {
          if (!data.id) return data.text;
          const $el = $(data.element);
          const color = $el.data('color');
          const value = $el.data('value');
          return color ? $(
            `<span class="d-flex align-items-center">
          <span style="width:14px;height:14px;background:${color};border:1px solid #ccc;margin-right:6px;"></span>
          ${value}
        </span>`) : data.text;
        };

        valuesSelect.select2({
          templateResult: renderOption,
          templateSelection: renderOption,
          placeholder: ''

        }).trigger('change');
      });
    }

    function cartesianProduct(arrays) {
      return arrays.reduce((a, b) => a.flatMap(d => b.map(e => [...d, e])), [
        []
      ]);
    }

    $(document).on('click', '#generateVariationsBtn', function() {
      const mergedAttributes = {};

      $('.attribute-row').each(function() {
        const $row = $(this);
        const attrId = $row.find('.attribute-select').val();
        const attrName = $row.find('.attribute-select option:selected').data('attribute-name');
        const selectedValues = $row.find('.attribute-values-select').val();

        if (attrId && selectedValues?.length) {
          mergedAttributes[attrId] = mergedAttributes[attrId] || {
            attribute_id: attrId,
            attribute_name: attrName,
            values: new Map()
          };

          selectedValues.forEach(valueId => {
            const label = $row.find(`.attribute-values-select option[value="${valueId}"]`).text();
            mergedAttributes[attrId].values.set(valueId, {
              attribute_id: attrId,
              value_id: valueId,
              label: label,
              attribute_name: attrName
            });
          });
        }
      });

      const attributesData = Object.values(mergedAttributes).map(attr => Array.from(attr.values.values()));

      if (!attributesData.length) {
        $('#variationsOutputContainer').html(
          '<div class="alert alert-info text-center mb-0">Please select at least one attribute with values.</div>'
        );
        return;
      } else {
        $('#variationsOutputContainer').html('');
      }

      const combinations = cartesianProduct(attributesData);

      if (!combinations.length) {
        $('#variationsOutputContainer').html('<div class="alert alert-info">No variations generated.</div>');
        return;
      }

      const baseSku = $('#base_product_sku').val();
      const resultData = combinations.map(combination => {
        const labelString = combination.map(item => item.label).join('-').toUpperCase();
        return {
          variant_sku: `${baseSku}-${labelString}`,
          raw: combination
        };
      });

      generateVariationsTable(resultData);
    });

    function generateVariationsTable(data) {
      let tableHtml = `
        <table class="table table-bordered table-centered" id="variationsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Variation</th>
              <th class="action">Action</th>
            </tr>
          </thead>
          <tbody>`;

      if (!Array.isArray(data) || !data.length) {
        tableHtml += `
          <tr>
            <td colspan="3">
              <div class="alert alert-danger text-center">No Product Variants Found</div>
            </td>
          </tr>`;
      } else {
        tableHtml += data.map((item, index) => `
          <tr data-combo="${encodeURIComponent(JSON.stringify(item.raw))}">
            <td class="serial">${index + 1}</td>
            <td><strong>${item.variant_sku}</strong></td>
            <td class="action">
              <a href="javascript:void(0)" class="action-icon text-danger delete-variation-btn" title="Delete Variation">
                <i class="ri-delete-bin-line"></i>
              </a>
            </td>
          </tr>`).join('');
      }

      tableHtml += `</tbody></table>
        <div class="text-end d-flex justify-content-between">
          <div class="back-btn">
            <a href="javascript:void(0)" onclick="gotoPreviousTab(1)" title="Back" class="d-flex justify-content-start align-items-center font-16"><i class="uil-angle-left font-18 me-1"></i>Back</a>
          </div>
          <button type="button" class="btn btn-primary gap-2" id="saveVariationsBtn">Save & Continue</button>
        </div>`;

      $('#variationsOutputContainer').html(tableHtml);
      $('#attributeContainer').show();
    }

    $(document).on('click', '.delete-variation-btn', function() {
      const row = $(this).closest('tr');
      row.slideUp(300, function() {
        row.remove();
        updateSerials();
        if (!$('#variationsTable tbody tr').length) {
          $('#variationsOutputContainer').html('<div class="alert alert-info">No variations remaining.</div>');
        }
      });
    });

    function updateSerials() {
      $('#variationsTable tbody tr').each(function(index) {
        $(this).find('.serial').text(index + 1);
      });
    }

    $(document).on('click', '#saveVariationsBtn', function() {
      const variations = [];

      $('#variationsTable tbody tr').each(function() {
        const encoded = $(this).data('combo');
        if (!encoded) return; // skip if no data
        try {
          const parsed = JSON.parse(decodeURIComponent(encoded));
          variations.push(parsed);
        } catch (e) {
          console.log('No New variations');
        }
      });

      if (!variations.length && variationCount == 0) {
        swalNotify("Oops!", "Add at least 1 variant to proceed", "error");
        return;
      } else if (!variations.length && variationCount > 0) {
        gotoNextTab(3);
        return;
      }
      const product_id = $('#p_id').val();
      const attributeIds = $('.multiple-attribute-select').val() || [];

      swalConfirm("Are you sure?", "Once saved, you won't be able to edit the variations from here.").then(result => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url: "{{ route('admin.product-variations.store') }}",
            data: {
              product_id: product_id,
              variations: variations,
              attribute_ids: attributeIds,
              _token: "{{ csrf_token() }}"
            },
            success: function(response) {
              if (response.success) {
                variationsSaved(true);
                swalNotify("Success!", response.message, "success");
                gotoNextTab(3);
                Livewire.dispatch('refreshComponent');
              } else {
                swalNotify("Oops!", response.message, "error");
                variationsSaved(false);
              }
            },
            error: function(error) {
              console.error(error);
              swalNotify("Error!", error.responseJSON?.message || "An error occurred", "error");
              variationsSaved(false);
            }
          });
        }
      });
    });

    function variationsSaved(saved = false) {
      if (saved) {
        $('#variationsTable td.action').html('');
      }
    }

    function existingVariants(value = false) {
      if (!value) return;
      $.ajax({
        type: "POST",
        url: "{{ route('admin.product-variations.variations-list-by-product', ':id') }}".replace(':id', $('#p_id')
          .val()),
        data: {
          _token: "{{ csrf_token() }}"
        },
        success: function(response) {
          if (response.success) {
            generateVariationsTable(response.data);
            variationsSaved(true);
            if (response.data.length > 0) {
              variationCount = response.data.length;
            }
          } else {
            swalNotify("Oops!", response.message, "error");
          }
        },
        error: function(xhr) {
          const message = xhr.responseJSON?.message || "Something went wrong";
          console.error(xhr);
          swalNotify("Error!", message, "error");
        }
      });
    }

    function gotoPreviousTab(tabNumber) {
      $('#page' + tabNumber + '-tab').removeClass('disabled');
      $('.nav-tabs a').removeClass('active');
      $('.tab-pane').removeClass('show active');
      $('#page' + tabNumber + '-tab').addClass('active');
      $('#page' + tabNumber).addClass('show active');
      existingVariants(true);
      window.scrollTo(0, 0);
    }
  </script>
@endpush
