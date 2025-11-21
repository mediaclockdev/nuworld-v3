<header class="furniture__header">
  <div class="furniture__logowrap">
    <div class="container-xxl">
      <div class="row">
        <div class="col-lg-12">
          <div class="furniture_logowrap">
            <div class="location__link">
              <div class="inside">

                @if (!auth()->check())
                  <a href="javascript:void();" class="d-flex justify-content-start align-items-center locationModal"><span
                      class="material-symbols-outlined me-1">location_on</span> Delivering to:
                    <strong class="default-pin ms-2">
                      {{ isset(session('user_pincode')['Name']) ? truncateNoWordBreak(session('user_pincode')['Name'], 20) : config('defaults.default_location') }}
                      {{ session('user_pincode')['Pincode'] ?? config('defaults.default_pincode') }}
                    </strong></a>
                @else
                  <a href="javascript:void();"
                    class="d-flex justify-content-start align-items-center list-address"
                    ><span
                      class="material-symbols-outlined me-1">location_on</span> Delivering to:
                    <strong class="default-pin ms-2">
                      {{ isset(session('user_pincode')['Name']) ? truncateNoWordBreak(session('user_pincode')['Name'], 20) : config('defaults.default_location') }}
                      {{ session('user_pincode')['Pincode'] ?? config('defaults.default_pincode') }}
                    </strong></a>
                @endif

              </div>
            </div>
            <div class="furniture__headerlogo text-center"><a href="{{ route('home') }}"
                title="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}"><img src="{{ siteLogo() }}"
                  alt="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}"
                  title="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}" /></a></div>
            <div class="actionwrap">
              <div class="itemswrp">
                <div class="top-nav-item search-icon">
                  <a href="javascript:void();" title="Search" data-bs-toggle="modal" data-bs-target="#searchModal"><span
                      class="material-symbols-outlined">search</span></a>
                </div>

                @if (auth()->check())
                  <div class="top-nav-item cart-icon wish-icon">
                    <a href="{{ route('wishlist') }}" title="Wishlist">
                      <span class="material-symbols-outlined">favorite</span>
                      <div class="cart-number wishlist-number {{ savedForLaterCount() > 0 ? '' : 'd-none' }}">
                        {{ savedForLaterCount() }}</div>
                    </a>
                  </div>
                @endif

                <div class="top-nav-item cart-icon">
                  <a href="{{ route('cart.index') }}" title="Shopping Cart">
                    <span class="material-symbols-outlined">local_mall</span>
                    {{-- <div class="cart-number">{{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</div> --}}
                    <div class="cart-number {{ cartCount() > 0 ? '' : 'd-none' }}">{{ cartCount() }}</div>
                  </a>
                </div>
              </div>

              <div class="top_accounts_wrap">
                <div class="buttons-inline gap-3 align-items-center d-flex">
                  @if (auth()->guard('web')->check())
                    {{-- After Login --}}
                    {{-- <div class="user_details text-start">
                      <p class="font16 m-0">Hello</p>
                      <h5 class="font18 m-0 fw-normal">
                        {{ auth()->guard('web')->user()->name == '' ? 'Guest' : auth()->guard('web')->user()->name ?? '' }}
                      </h5>
                    </div> --}}

                    <figure class="m-0">
                      <a href="{{ route('profile') }}" title="User"><img
                          src="{{ userImageById('web', auth()->guard('web')->user()->id) ? userImageById('web', auth()->guard('web')->user()->id)['image'] : asset('public/frontend/assets/img/home/top_user_thumb.jpg') }}"
                          alt="User" title="User" class="imageFit rounded-circle"
                          style="width: 50px; height: 50px;" />
                      </a>
                    </figure>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-dark">Logout</button>
                    </form>
                  @else
                    {{-- Before Login --}}
                    <a class="btn btn-dark" href="{{ route('signuplogin') }}">Login</a>
                    {{-- <a class="btn btn-dark" href="{{ route('login') }}">Login</a> --}}
                  @endif
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('frontend.layouts.includes.navbar')
</header>

@livewire('frontend-search')
