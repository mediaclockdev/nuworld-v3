@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')
  <section class="living__signupwrap">
    <div class="container">
      <div class="row">
        <div class="col-lg-10 offset-md-1">
          <div class="inswrp">
            <div class="left">
              @php
                $settings = json_decode($login_page_banner['settings'] ?? '{}', true);
                $imageName = $settings['image'] ?? '';
                $altText = $settings['alt_text'] ?? 'Mayuri';

                // Check if image exists, else use default
                $imagePath = !empty($imageName)
                    ? asset(config('defaults.banner_image_path') . $imageName)
                    : asset('public/frontend/assets/img/home/signup_popup_thumb.jpg');
              @endphp

              <figure class="mb-0">
                <img src="{{ $imagePath }}" alt="{{ $altText }}" title="{{ $altText }}" class="imageFit" />
              </figure>
              <div class="txt">
                <h2 class="font45">{{ $login_page_banner->title ?? 'Luxury' }}</h2>

                {!! $settings['content'] ?? 'Discover 30k+ varities' !!}
              </div>
            </div>

            {{-- Email/Mobile Login Form --}}
            <div class="commonforms flow-rootX2" id="emailForm">
              <div class="righthead">
                <h1 class="font25 fw-normal">Signup or Login</h1>
                <p class="c--menuc">Log In to track your order, create Wishlist & more.</p>
              </div>
              <form class="allForm" id="loginRegisterForm" autocomplete="off">
                @csrf
                <div class="form-element">
                  <label class="form-label">Email Address <em>*</em></label>
                  <input name="email" type="email" id="email" class="form-field" autocomplete="new-email" />
                  <span class="msg-error error"></span>
                </div>
                <div class="form-check required">
                  <input class="form-check-input" type="checkbox" name="flexCheckDefault" value="1"
                    id="flexCheckDefault" checked>
                  <label class="form-check-label font14" for="flexCheckDefault">
                    By continuing, you agree to Mayuri's <a href="{{ route('cms.page', 'terms-of-use') }}">Terms of
                      Use</a> and <a href="{{ route('cms.page', 'privacy-policy') }}">Privacy
                      Policy</a>.
                  </label>
                  <p class="msg-error error"></p>
                </div>
                <button type="submit" class="btn btn-dark w-100 btn-lg py-3">Request OTP</button>

                <div class="googlelogin mt-4">
                  <a href="{{ route('auth.google') }}" title="">
                    <span><img src="{{ asset('public/frontend/assets/img/icons/google-icon.svg') }}" alt="Google"
                        title="Google" /></span>
                    <span>Continue with Google</span>
                  </a>
                </div>
                @if (session('error'))
                  <div id="flash-message" style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px;">
                    {{ session('error') }}
                  </div>

                  <script>
                    // Hide flash message after 5 seconds
                    setTimeout(function() {
                      var flash = document.getElementById('flash-message');
                      if (flash) {
                        flash.style.display = 'none';
                      }
                    }, 5000); // 5000ms = 5 seconds
                  </script>
                @endif
              </form>
            </div>
            <div class="commonforms flow-rootX2 otpField" id="otpForm" style="display: none;">
              <div class="righthead">
                <h1 class="font24 mb-4 fw-normal">Verify your email to continue</h1>
                <p class="font18 c--gry">We’ve emailed a 6-digit verification code to <span id="otpSentTo"
                    class="fw-bold"></span>
                  <a href="javascript:void(0);" title="Change" class="text-decoration-none me-2" id="changeEmail"> Change
                  </a>
                  Please enter it below to verify your identity.
                </p>
              </div>

              <form class="allForm" id="otpVerifyForm" method="POST" action="{{ route('verifyotp') }}"
                autocomplete="off">
                @csrf
                <div class="otpElement">
                  <div class="form-element">
                    <input name="otp[0]" id="otp1" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                  <div class="form-element">
                    <input name="otp[1]" id="otp2" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                  <div class="form-element">
                    <input name="otp[2]" id="otp3" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                  <div class="form-element">
                    <input name="otp[3]" id="otp4" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                  <div class="form-element">
                    <input name="otp[4]" id="otp5" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                  <div class="form-element">
                    <input name="otp[5]" id="otp6" inputmode="numeric" type="text"
                      class="form-field text-center only-integers otp-input px-1">
                  </div>
                </div>
                <span id="otp-error" class="msg-error error"></span>

                <button type="submit" id="verifyOtpBtn" class="btn btn-dark w-100 btn-lg py-3">Verify &
                  Continue</button>
              </form>
              <div class="existing text-center">Not received your code? <a href="javascript:void(0);" id="resendOtpBtn"
                  title="Resend Code">Resend Code</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  {{-- This is FAQ page content --}}
  {{-- ============================================================================================================================================================ --}}
  {{-- <section class="faq">
    <div class="container">
      <div class="inner-container">
        <h1 class="font45">Frequently Asked Questions</h1>
        <div class="tabContainer">
          <div class="row">
            <div class="col-2 pr-0">
              <ul class="tabMenu">
                <li class="font20"><a href="javascript: void(0);" class="actv">Top Queries</a></li>
                <li class="font20"><a href="javascript: void(0);">My Account</a></li>
                <li class="font20"><a href="javascript: void(0);">Returns</a></li>
                <li class="font20"><a href="javascript: void(0);">Payment Options</a></li>
                <li class="font20"><a href="javascript: void(0);">Shipping and delivery</a></li>
                <li class="font20"><a href="javascript: void(0);">Warranty</a></li>
                <li class="font20"><a href="javascript: void(0);">Sign Up and Login</a></li>
              </ul>
            </div>
            <div class="col-10 pl-0">
              <div class="tabContents">
                <div class="tabContent" style="display: block;">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Top Queries</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">My Account</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Go to My
                          Account</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Returns</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Payment Options</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Shipping and delivery</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Warranty</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tabContent">
                  <div class="title_BtnWrap">
                    <div class="row">
                      <div class="col-8">
                        <h2 class="font25">Sign Up and Login</h2>
                        <p class="desc c--gry mb-0">Lorem ipsum dolor sit amet consectetur. At.</p>
                      </div>
                      <div class="col-4">
                        <a href="javascript: void(0);"
                          class="btn btn-outline-dark d-inline-flex px-4 py-3 align-items-center gap-2">Track Orders</a>
                      </div>
                    </div>
                  </div>
                  <div class="accordWrap accord">
                    <div class="accordion">
                      <h5 class="accord-btn font20 actv">Question will go here?</h5>
                      <div class="accord-target" style="display:block;">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                    <div class="accordion">
                      <h5 class="accord-btn font20">Question will go here?</h5>
                      <div class="accord-target">
                        <p class="desc c--gry">Gain real-time analytics and comprehensive reports to optimize performance
                          and
                          drive business growth.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section> --}}
  {{-- This is FAQ page content --}}
  {{-- ============================================================================================================================================================ --}}
@endsection

@push('scripts')
  <script src="{{ asset('public/common/js/custom_input.js') }}"></script>
  <script>
    $(document).ready(function() {
      const loginForm = $('#loginRegisterForm');
      const otpForm = $('#otpVerifyForm');
      clearInputs();

      function sendAjaxRequest(url, data, successCallback, errorCallback) {
        $.ajax({
          url,
          method: 'POST',
          data: {
            ...data,
            "_token": "{{ csrf_token() }}"
          },
          success: successCallback,
          error: errorCallback || function(error) {
            iziNotify("Oops!", error.responseJSON.message, "error");
          }
        });
      }

      function startResendTimer(button, duration = 20) {
        button.text(`Resend Code (${duration}s)`).prop('disabled', true).addClass('text-muted');
        let seconds = duration;
        let timer = setInterval(function() {
          seconds--;
          button.text(`Resend Code (${seconds}s)`);
          if (seconds <= 0) {
            clearInterval(timer);
            button.text('Resend Code').prop('disabled', false).removeClass('text-muted');
          }
        }, 1000);
      }

      const validationSettings = {
        errorPlacement: (error, element) => element.siblings('.msg-error').text(error.text()),
        highlight: element => $(element).addClass('is-invalid'),
        unhighlight: element => $(element).removeClass('is-invalid').siblings('.msg-error').text('')
      };

      loginForm.validate({
        ...validationSettings,
        rules: {
          email: {
            required: true,
            email: true
          },
          flexCheckDefault: {
            required: true
          }
        },
        messages: {
          email: {
            required: "{{ __('validation.required', ['attribute' => 'Email Address']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email Address']) }}"
          },
          flexCheckDefault: {
            required: "Please agree to the Terms of Use and Privacy Policy to continue."
          }
        },
        submitHandler: function() {
          let verifyBtn = loginForm.find('button[type="submit"]').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
          );
          sendAjaxRequest(
            '{{ route('signuplogin') }}', {
              email: $('#email').val()
            },
            function(res) {
              if (res.success) {
                iziNotify("", res.message, "success");
                $('#emailForm').hide();
                $('.otp-input').val('');
                $('#otp-error').text('');
                $('#otpForm').show();
                $('#otpSentTo').text($('#email').val());
                startResendTimer($('#resendOtpBtn')); // Disable resend for 20s
              } else {
                iziNotify("Oops!", res.message, "error");
              }
            }
          );
        }
      });

      // Handle paste event
      $('#otp1').on('paste', function(e) {
        clearInputs();
        e.preventDefault();
        let pasteData = e.originalEvent.clipboardData.getData('text');
        if (pasteData.length === 6 && /^\d{6}$/.test(pasteData)) {
          $('.otp-input').each((i, el) => $(el).val(pasteData[i]));
          $('#otp6').focus();
        }
      });

      // Handle input and navigation
      $('.otp-input').on('input keydown', function(e) {
        let $this = $(this);
        let value = $this.val();

        // Restrict to single digit
        if (e.type === 'input') {
          if (value.length > 1) $this.val(value.slice(0, 1));
          if (value.length === 1) {
            let $next = $this.closest('.form-element').next('.form-element').find('.otp-input');
            if ($next.length) $next.focus();
          }
        }

        // Handle backspace
        if (e.type === 'keydown' && e.key === 'Backspace' && !value) {
          let $prev = $this.closest('.form-element').prev('.form-element').find('.otp-input');
          if ($prev.length) $prev.focus();
        }

        // Handle Enter key
        if (e.type === 'keydown' && e.key === 'Enter') {
          $('#otpVerifyForm').submit();
        }
      });

      // Clear inputs
      function clearInputs() {
        $('.otp-input').val('');
        $('#otp-error').text('');
      }

      // Form validation
      $('#otpVerifyForm').validate({
        ...validationSettings,
        rules: {
          'otp[0]': {
            required: true
          },
          'otp[1]': {
            required: true
          },
          'otp[2]': {
            required: true
          },
          'otp[3]': {
            required: true
          },
          'otp[4]': {
            required: true
          },
          'otp[5]': {
            required: true
          }
        },
        messages: {
          'otp[0]': "{{ __('validation.required', ['attribute' => 'OTP']) }}",
          'otp[1]': "{{ __('validation.required', ['attribute' => 'OTP']) }}",
          'otp[2]': "{{ __('validation.required', ['attribute' => 'OTP']) }}",
          'otp[3]': "{{ __('validation.required', ['attribute' => 'OTP']) }}",
          'otp[4]': "{{ __('validation.required', ['attribute' => 'OTP']) }}",
          'otp[5]': "{{ __('validation.required', ['attribute' => 'OTP']) }}"
        },
        submitHandler: function() {
          let otp = $('.otp-input').map((_, el) => $(el).val()).get().join('');
          let $verifyBtn = $('#verifyOtpBtn').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
          );

          sendAjaxRequest(
            '{{ route('verifyotp') }}', {
              otp: otp
            },
            /* function(res) {
              if (res.success) {
                iziNotify("", res.message, "success");
                setTimeout(() => window.location.href = "{{ route('home') }}", 1500);
              } else {
                $('.otp-input').addClass('is-invalid');
                $('#otp-error').text(res.message);
                $verifyBtn.prop('disabled', false).html('Verify');
              }
            } */
           function(res) {
                if (res.success) {
                    iziNotify("", res.message, "success");
                    setTimeout(() => {
                        window.location.href = res.redirect || '{{ route('home') }}';
                    }, 1500);
                } else {
                    $('.otp-input').addClass('is-invalid');
                    $('#otp-error').text(res.message);
                }
            },
          );
        }
      });

      $(document).on('click', '#changeEmail', function(e) {
        e.preventDefault();
        $('#otpForm').hide();
        $('#otpSentTo').text('');
        $('#otp').val('');
        $('#emailForm').show();
      });

      $(document).on('click', '#resendOtpBtn', function(e) {
        e.preventDefault();
        sendAjaxRequest(
          '{{ route('resendotp') }}', {
            email: $('#email').val()
          },
          function(res) {
            if (res.success) {
              iziNotify("", res.message, "success");
              startResendTimer($('#resendOtpBtn'));
            } else {
              iziNotify("Oops!", res.message, "error");
            }
          }
        );
      });
    });
  </script>
@endpush
