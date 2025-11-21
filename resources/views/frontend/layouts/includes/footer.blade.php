<!-- Footer Start -->
@php
  //dd($siteSettings);
@endphp
<section class="furniture_footer fullBG mt-0">
  <div class="container">
    <div class="footer-logo-wrap">


      <div class="row align-items-center">
        <div class="col-lg-6">
          <a href="{{ route('home') }}" class="footer-logo">
            <img src="{{ siteLogo() }}" alt="log0" title="logo" />
          </a>
        </div>
        <div class="col-lg-6">
          <ul class="footer-menu">
            {{-- @php
              pd($pages);
            @endphp --}}
            @foreach ($pages as $slug => $page)
              <li><a href="{{ url($slug) }}" title="{{ $page->title }}">{{ $page->title }}</a></li>
            @endforeach
            <li><a href="#">FAQ’s</a></li>
            {{-- <li><a href="{{ url('blogs') }}" title="Blogs">Blogs</a></li> --}}
            {{-- <li><a href="#">About Us</a></li>
            <li><a href="#">FAQ’s</a></li>
            <li><a href="#">Contact Us</a></li> --}}
          </ul>
          <!-- <div class="footer_linkblock_wrap" style="column-gap: 65rem;">
          <div class="footer_links">
            <ul>
              @foreach ($pages as $slug => $page)
{{-- @php
                  pd($page);
                @endphp --}}
              <li>
                <a href="{{ url($slug) }}" title="{{ $page->title }}">
                  {{ $page->title }}
                </a>
              </li>
@endforeach
              {{-- Static Blog Link --}}
              <li>
                <a href="{{ url('blogs') }}" title="Blog">
                  Blog
                </a>
              </li>
            </ul>
          </div>
        </div> -->
        </div>
      </div>
    </div>
    @if (optional($siteSettings)['google_play_link'] || optional($siteSettings)['apple_app_link'])
      <div class="row">
        <div class="col-lg-12">
          <div class="popular_brands_wrap">
            <div class="popular_brands flow-rootX">
            </div>
            <div class="download_app flow-rootX">
              <h5 class="font20 fw-medium mb-3">Download our App</h5>
              <div class="d-flex align-items-center justify-content-start gap-2">
                @if ($siteSettings['google_play_link'])
                  <a href="{{ $siteSettings['google_play_link'] ?? 'javascript:void();' }}" title=""><img
                      src="{{ asset('public/frontend/assets/img/footer/google_play.png') }}" alt="Google Play"
                      title="Google Play" /></a>
                @endif
                @if ($siteSettings['apple_app_link'])
                  <a href="{{ $siteSettings['apple_app_link'] ?? 'javascript:void();' }}" title=""><img
                      src="{{ asset('public/frontend/assets/img/footer/ios_app.png') }}" alt="App Store"
                      title="App Store" /></a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="row">
      <div class="col-lg-12">
        <div class="we_accept_wrap">
          <div class="we-accept d-flex align-items-center gap-3">
            <h5 class="font20 mb-0">We Accept</h5>
            <div class="iconswrp d-flex align-items-center gap-2">
              <span><img src="{{ asset('public/frontend/assets/img/footer/visa.jpg') }}" alt="Visa"
                  title="Visa" /></span>
              <span><img src="{{ asset('public/frontend/assets/img/footer/american-express.jpg') }}"
                  alt="American Express" title="American Express" /></span>
              <span><img src="{{ asset('public/frontend/assets/img/footer/mastercard.jpg') }}" alt="Mastercard"
                  title="Mastercard" /></span>
              <span><img src="{{ asset('public/frontend/assets/img/footer/dinersclub.jpg') }}" alt="Diners Club"
                  title="Diners Club" /></span>
              <span><img src="{{ asset('public/frontend/assets/img/footer/maestro.jpg') }}" alt="Maestro"
                  title="Maestro" /></span>
              <span><img src="{{ asset('public/frontend/assets/img/footer/rupay.jpg') }}" alt="RuPay"
                  title="RuPay" /></span>
            </div>
          </div>
          <div class="follow-us d-flex justify-content-end align-items-center gap-4">
            <h5 class="font20 mb-0">Follow Us</h5>
            <div class="socialwrap d-flex align-items-center gap-3">
              <span><a href="{{ $siteSettings['x_link'] ?? 'javascript:void();' }}" title="Twitter"
                  class="twitter"><img src="{{ asset('public/frontend/assets/img/footer/twitter.svg') }}"
                    alt="Twitter" title="Twitter" /></a></span>
              <span><a href="{{ $siteSettings['youtube_link'] ?? 'javascript:void();' }}" title="YouTube"
                  class="youtube"><img src="{{ asset('public/frontend/assets/img/footer/youtube.svg') }}"
                    alt="YouTube" title="YouTube" /></a></span>
              <span><a href="{{ $siteSettings['instagram_link'] ?? 'javascript:void();' }}" title="Instagram"
                  class="instagram"><img src="{{ asset('public/frontend/assets/img/footer/instagram.svg') }}"
                    alt="Instagram" title="Instagram" /></a></span>
              <span><a href="{{ $siteSettings['facebook_link'] ?? 'javascript:void();' }}" title="Facebook"
                  class="facebook"><img src="{{ asset('public/frontend/assets/img/footer/facebook.svg') }}"
                    alt="Facebook" title="Facebook" /></a></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-0">
      <div class="col-lg-12">
        <div class="footer_links_wrp">
          <div class="copyrightwrp w-100 d-flex align-items-center justify-content-between">
            <span class="font16 copyrightwrp-items">
              @if (!empty($terms_and_conditions_page))
                <a href="{{ route('cms.page', $terms_and_conditions_page->slug) }}">
                  {{ $terms_and_conditions_page->title }}
                </a>
              @endif

              @if (!empty($privacy_policy_page))
                | <a href="{{ route('cms.page', $privacy_policy_page->slug) }}">
                  {{ $privacy_policy_page->title }}
                </a>
              @endif
            </span>
            <span class="font18 d-flex gap-4"> <span>© Copyright {{ $siteSettings['sitename'] ?? 'Mayuri' }}</span>
              <span>Built by <a href="https://sundewsolutions.com/" target="_blank"
                  title="Sundew">Sundew</a></span></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a href="javascript:void(0)" class="back-to-top" id="backtotop">
    <span class="material-symbols-outlined">north</span>
  </a>
</section>
<!-- Footer End -->
