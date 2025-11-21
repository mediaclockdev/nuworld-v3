<div class="left_content_wrap">
  <div class="left_content_inside">
    <div class="afterlogin_profile midsize d-flex justify-content-start align-items-center gap-3">
      <a href="{{ route('profile-details') }}" title="My Account"></a>
      {{-- @php
            dd(auth()->guard('web')->user());
          @endphp --}}
      <figure class="m-0"><img
          src="{{ userImageById('web', auth()->guard('web')->user()->id) ? userImageById('web', auth()->guard('web')->user()->id)['image'] : asset('public/frontend/assets/img/home/top_user_thumb.jpg') }}"
          alt="Mayuri" title="Mayuri" class="imageFit" /></figure>
      <div class="user_details">
        <p class="font20 mt-0 mb-2 c--blackc">{{ auth()->guard('web')->user()->name }}</p>
        <h5 class="font16 m-0 fw-normal c--gry">{{ auth()->guard('web')->user()->email }}</h5>
      </div>
    </div>
    <ul>
      <li>
        <a href="{{ route('profile') }}" title="Overview"
          class="{{ request()->segment(1) == 'profile' ? 'active' : '' }}">Overview</a>
      </li>
      <li>
        <h4 class="font16 fw-normal text-capital c--gry">Orders</h4>
        <ul>

          <li><a href="{{ route('orders') }}" title="Orders & Returns"
              class="{{ request()->segment(1) == 'orders' ? 'active' : '' }}">Orders & Returns</a></li>
        </ul>
      </li>
      <li>
        <h4 class="font16 fw-normal text-capital c--gry">Account</h4>
        <ul>
          <li><a href="{{ route('profile-details') }}" title="Profile"
              class="{{ request()->segment(1) == 'profile-details' ? 'active' : '' }}">Profile Details</a></li>
          {{-- <li><a href="{{ route('change-password') }}" title="Password">Password</a></li> --}}
          {{-- <li><a href="{{ route('saved-payment') }}" title="Saved Payment Method">Saved Payment Method</a></li>
          <li><a href="{{ route('wishlist') }}" title="Collections & Wishlist">Collections & Wishlist</a></li>
          <li><a href="{{ route('reward') }}" title="Reward">Reward</a></li> --}}
          <li><a href="#" title="Saved Payment Method">Saved Payment Method</a></li>
          <li><a href="#" title="Collections & Wishlist">Collections & Wishlist</a></li>
          <li><a href="#" title="Reward">Reward</a></li>
          <li><a href="{{ route('address') }}" title="Addresses">Addresses</a></li>
        </ul>
      </li>
      <li>
        <h4 class="font16 fw-normal text-capital c--gry">Legal</h4>
        <ul>
          <li><a href="{{ route('cms.page','terms-of-use') }}" title="Terms of Use">Terms of Use</a></li>
          <li><a href="{{ route('cms.page','privacy-policy') }}" title="Privacy Policy">Privacy Policy</a></li>
        </ul>
      </li>
    </ul>
  </div>
</div>
