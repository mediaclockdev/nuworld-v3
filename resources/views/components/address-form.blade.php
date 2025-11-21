<form id="addressForm">
  @csrf
  <div class="row">
    <div class="col-md-12">
      <div class="mb-3 required">
        <label for="address" class="form-label">Address Line 1</label>
        <input type="text" class="form-control" name="address1" id="address1" placeholder="Enter Address Line 1"
          value="{{ $address1 }}">
        <div class="address1-error-container"> </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 position-relative">
        <label for="address" class="form-label">Address Line 2</label>
        <input type="text" class="form-control" name="address2" id="address2" placeholder="Enter Address Line 2"
          value="{{ $address2 }}">
        <div class="address2-error-container"> </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="mb-3 position-relative">
        <label for="address" class="form-label">Landmark</label>
        <input type="text" class="form-control" name="landmark" id="landmark" placeholder="Enter Landmark"
          value="{{ $landmark }}">
        <div class="landmark-error-container"> </div>
      </div>
    </div>
    @if (!config('defaults.country_id'))
      <div class="col-md-6">
        <div class="mb-3 required">
          <label for="country" class="form-label">Country</label>
          <select class="form-select select2" name="country" id="country">
            @foreach ($countries as $country)
              <option value="{{ Hashids::encode($country['id']) }}"
                {{ $country_id == $country['id'] ? 'selected' : '' }}>
                {{ $country['name'] }}
              </option>
            @endforeach
          </select>
          <div class="country-error-container"></div>
        </div>
      </div>
    @else
      <input type="hidden" name="country" value="{{ Hashids::encode(config('defaults.country_id')) }}">
    @endif
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="state" class="form-label">State</label>
        <select class="form-select select2" name="state" id="state">
          <option value=""></option>
        </select>
        <div class="state-error"> </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="city" class="form-label">City</label>
        <input type="text" class="form-control" name="city" id="city" placeholder="Enter City"
          value="{{ $city }}">
        <div class="city-error-container"> </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="mb-3 required">
        <label for="zip_code" class="form-label">Zip Code</label>
        <input type="text" class="form-control only-zip-code" name="zip_code" id="zip_code"
          placeholder="Enter Zip Code" value="{{ $zip }}">
        <div class="zip_code-error-container"> </div>
      </div>
    </div>

  </div>
</form>

@push('component-scripts')
  <script>
    let countryId =
      '{{ $country_id ? Hashids::encode($country_id) : Hashids::encode(config('defaults.country_id') ?? 0) }}';

    function initializeSelect2(t,e){$(t).select2({placeholder:e})}function fetchStateList(t){const e=$("#state");t?(e.html('<option value="">Loading...</option>'),$.ajax({url:"{{ route('admin.state_list', ':country') }}".replace(":country",t),method:"GET",success:function(t){populateDropdown(e,t,"id","name","{{ $state }}")},error:function(){e.html('<option value="">Error fetching states</option>')}})):e.html('<option value="">Select State</option>').select2()}function populateDropdown(t,e,o,n,i=null){t.empty().append('<option value="">Select State</option>'),Array.isArray(e)&&$.each(e,(function(e,a){t.append(`<option value="${a[o]}" ${a[o]==i?"selected":""}>${a[n]}</option>`)})),t.select2()}initializeSelect2("#state","Select State"),fetchStateList(countryId),$(document).on("change","#country",(function(){fetchStateList($(this).val())}));

  </script>
@endpush
