<div class="address_block_profile flow-rootX">
  <h3 class="font20 fw-normal mb-2">Default Address</h3>
  @forelse ($addresses->where('primary', 1)->sortByDesc('id')->take(1) as $address)
    <div class="profile_address_block border">
      <address>
        <h4 class="fw-medium font20">{{ $address->name }}</h4>
        <p class="mb-0 font16">{{ $address->address_1 ? truncateNoWordBreak($address->address_1,100) : '' }}, <br>
          {{ $address->city }} - {{ $address->pin }}, <br>
          {{ $address->state->name }} </p>
      </address>
      <div class="action d-flex justify-content-center align-items-center gap-0">
        <a href="javascript:void();"
          class="btn btn-light d-inline-flex align-items-center font16 c--blackc gap-2 create-or-edit-address"
          title="Edit" data-address='@json($address)' data-address-id="{{ Hashids::encode($address->id ?? '') }}"><span
            class="material-symbols-outlined font15">edit</span> Edit</a>

        <a href="javascript:void();"
          class="btn btn-light font16 c--primary d-inline-flex align-items-center border-left gap-2 delete-default-address"
          title="Remove" data-address-id="{{ $address->id ?? '' }}"><span
            class="material-symbols-outlined font15">delete</span> Remove</a>
      </div>
    </div>
  @empty
    No default address found
  @endforelse
</div>
<div class="address_block_profile flow-rootX">
  <h3 class="font20 fw-normal mb-2">Other Address</h3>
  @forelse ($addresses->where('primary', 0) as $address)
    <div class="profile_address_block border">
      <address>
        <h4 class="fw-medium font20">{{ $address->name }}</h4>
        <p class="mb-0 font16">{{ $address->address_1 }}, <br>
          {{ $address->city }} - {{ $address->pin }}, <br>
          {{ $address->state->name }}</p>
      </address>
      <div class="action d-flex justify-content-center align-items-center gap-0">
        <a href="javascript:void();"
          class="btn btn-light d-inline-flex align-items-center font16 c--blackc gap-2 create-or-edit-address"
          title="Edit" data-address='@json($address)' data-address-id="{{ Hashids::encode($address->id ?? '') }}"><span
            class="material-symbols-outlined font15">edit</span> Edit</a>

        <a href="javascript:void();"
          class="btn btn-light font16 c--primary d-inline-flex align-items-center border-left gap-2 delete-default-address"
          title="Remove" data-address-id="{{ $address->id ?? '' }}"><span
            class="material-symbols-outlined font15">delete</span> Remove</a>
      </div>
    </div>
  @empty
    No other addresses available
  @endforelse
</div>
