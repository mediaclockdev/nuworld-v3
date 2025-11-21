

<label class="d-flex border py-2 address-item" for="default_address_{{ $singleAddress->id ?? '' }}">
  <input type="radio" name="default_address" id="default_address_{{ $singleAddress->id ?? ''}}" value="{{ $singleAddress->id ?? '' }}"
    class="mx-3" data-attr-type="{{ $singleAddress->type ?? '' }}" {{ !empty($singleAddress) && $singleAddress->primary == 1 ? 'checked' : '' }}>
  <div id="address_{{ $singleAddress->id ?? '' }}">
    <h4 class="fw-medium font20">{{ $singleAddress->name ?? '' }}</h4>
    <p class="mb-0 font16">
      {{ $singleAddress->address_1 ? truncateNoWordBreak($singleAddress->address_1,100) : '' }},
      {{ $singleAddress->city ?? '' }} <br>
      {{ $singleAddress->landmark ?? '' }}<br>
      {{ $singleAddress->state_name ?? ($singleAddress->state->name ?? '') }} - {{ $singleAddress->pin ?? '' }},<br>
      {{ $singleAddress->country_name ?? ($singleAddress->state->country->name ?? '') }}<br>
      Phone: {{ $singleAddress->phone ?? '' }}
    </p>
  </div>
</label>
