@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')

  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>Account</li>
      </ul>
    </div>
  </section>
  <section class="furniture_myaccount_wrap pt-4">
    <div class="container flow-rootX3">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="fw-normal mt-0 font45 c--blackc">Account</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="my_account_wrap">
            @include('frontend.pages.user.includes.profile-sidebar')
            <div class="right_content">
              <div class="profile_details overflow-hidden border flow-rootX3 h-100">
                <div class="heading d-flex justify-content-between align-items-center border-bottom pb-4">
                  <h2 class="font25 fw-medium m-0 c--blackc">Saved Addresses</h2>
                  <a href="javascript:void();"
                    class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2 create-or-edit-address"
                    title="Add New Address"><span class="material-symbols-outlined">add</span> Add New Address</a>
                </div>
                <div class="info flow-rootX2" id="address_block_profile">
                  @include('frontend.includes.list-of-profile-address', ['addresses' => $addresses])

                  {{-- <div class="address_blocka flow-rootX">
                    <h3 class="font20 fw-normal mb-2">Default Address</h3>
                    @forelse ($addresses->where('primary', 1)->sortByDesc('id')->take(1) as $address)
                      <div class="profile_address_block border">
                        <address>
                          <h4 class="fw-medium font20">{{ $address->name }}</h4>
                          <p class="mb-0 font16">{{ $address->address_1 }}, <br>
                            {{ $address->city }} - {{ $address->pin }}, <br>
                            {{ $address->state->name }} </p>
                        </address>
                        <div class="action d-flex justify-content-center align-items-center gap-0">
                          <a href="javascript:void();"
                            class="btn btn-light d-inline-flex align-items-center font16 c--blackc gap-2 create-or-edit-address"
                            title="Edit" title="Edit" data-address='@json($address)'><span
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
                  <div class="address_blockb flow-rootX">
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
                            title="Edit" title="Edit" data-address='@json($address)'><span
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
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <x-create-or-edit-address-modal :states="$states" />
@endsection


@push('scripts')
  <script>
    function toggleOptions() {
      const options = document.getElementById("options");
      const selected = document.getElementById("selected");
      const isOpen = options.style.display === "block";

      options.style.display = isOpen ? "none" : "block";
      selected.classList.toggle("active", !isOpen);
    }

    function selectOption(name, img) {
      const selected = document.getElementById("selected");
      selected.innerHTML = `<img src="${img}" alt="${name}"> ${name}`;
      document.getElementById("options").style.display = "none";

      // Ensure border stays black
      selected.classList.remove("active");
      selected.classList.add("has-value");
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function(e) {
      const options = document.getElementById("options");
      const selected = document.getElementById("selected");

      if (!e.target.closest('.custom-select')) {
        if (options && selected) {
          options.style.display = "none";
          selected.classList.remove("active");
        }
      }
    });


    // Address modal operation





  </script>

@endpush
