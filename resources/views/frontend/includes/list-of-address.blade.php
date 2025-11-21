<!-- resources/views/frontend/includes/list-of-address.blade.php -->
<div class="address_block flow-rootX">
    @forelse ($userAddresses as $item)
        <label class="d-flex border py-2 address-item" for="user_default_address_{{ $item->id ?? '' }}">
            <input type="radio"
                   name="user_default_address"
                   id="user_default_address_{{ $item->id ?? '' }}"
                   value="{{ $item->id ?? '' }}"
                   class="mx-3 select-address-radio"
                   data-address-id="{{ $item->id ?? '' }}"
                   data-attr-type="{{ $item->type ?? '' }}"
                   {{ !empty($item) && $item->primary == 1 ? 'checked' : '' }}>
            <div id="address_{{ $item->id ?? '' }}">
                <h4 class="fw-medium font20">{{ $item->name ?? '' }}</h4>
                <p class="mb-0 font16">
                    {{ $item->address_1 ? truncateNoWordBreak($item->address_1,100) : '' }},
                    {{ $item->city ?? '' }} - {{ $item->pin ?? '' }}, <br>
                    {{ $item->landmark ?? '' }}<br>
                    {{ $item->state_name ?? ($item->state->name ?? '') }}<br>
                    {{ $item->country_name ?? ($item->state->country->name ?? '') }}<br>
                    Phone: {{ $item->phone ?? '' }}
                </p>
                {{-- <button class="btn btn-sm btn-outline-dark edit-address-btn"
                        data-address='{{ json_encode($item) }}'>Edit</button> --}}
            </div>
        </label>
    @empty
        <p id="no-address">No addresses available.</p>
    @endforelse
    <input type="button"
           class="btn btn-dark w-100 py-3 mt-3 submit-default-address-btn"
           value="Submit"
           {{ $userAddresses->isEmpty() ? 'disabled' : '' }}>
</div>
