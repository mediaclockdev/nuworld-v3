@extends('frontend.layouts.app')

@section('title', @$title)
@section('content')
  <main class="pg_contact">
    <section class="getIntouch">
      <div class="container">
        <div class="inner-container">
          <div class="row justify-content-between">
            <div class="left image col-lg-5">
              <figure><img src="{{ asset('public/frontend/assets/img/contact/banner.webp') }}" alt="Mayuri"
                  title="Mayuri" />
              </figure>
            </div>
            <div class="content col-lg-6">
              <h1 class="fw-normal font45">Get in touch</h1>
              <p class="desc c--gry">At risus viverra adipiscing at in tellus integer feugiat nisl pretium fusce id velit
                ut tortor sagittis orci a scelerisque purus semper eget at lectus</p>
              <form action="" class="allForm">
                <div class="form-element fname">
                  <label class="form-label">First Name <em>*</em></label>
                  <input name="first name" type="text" class="form-field">
                </div>
                <div class="form-element lname">
                  <label class="form-label">Last Name <em>*</em></label>
                  <input name="last name" type="text" class="form-field">
                </div>
                <div class="form-element email">
                  <label class="form-label">Enter Your Email <em>*</em></label>
                  <input name="last name" type="text" class="form-field">
                </div>
                <div class="form-element textarea">
                  <label class="form-label">Enter Your Message</label>
                  <textarea class="form-field" name="" id="" type="text"></textarea>
                </div>
                <div class="action">
                  <button type="button" class="btn btn-dark btn-lg py-2 px-4">Send Message</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="contact">
      <div class="container">
        <div class="inner-container">
          <div class="row justify-content-between">
            <div class="col-3">
              <h1 class="fw-normal font45">Contact Us</h1>
              <p class="desc c--gry">Lorem ipsum dolor sit amet consectetur. Urna ac nulla in praesent eu maecenas
                viverra.</p>
            </div>
            <div class="col-9">
              <div class="row contactBoxwrap">
                <div class="col-4">
                  <p class="count font24 c--gry mb-1">.01</p>
                  <p class="text font24 mb-5">Call Us</p>
                  <a href="tel:+123458786" class=cta font20"><svg width="13" height="13" viewBox="0 0 13 13"
                      fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                    </svg>
                    +1 23 458 786</a>
                </div>
                <div class="col-4">
                  <p class="count font24 c--gry mb-1">.02</p>
                  <p class="text font24 mb-5">Send us an email</p>
                  <a href="mailto:example@gmail.com" class=cta font20"><svg width="13" height="13"
                      viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                    </svg>
                    example@gmail.com</a>
                </div>
                <div class="col-4">
                  <p class="count font24 c--gry mb-1">.03</p>
                  <p class="text font24 mb-5">Live chat with us</p>
                  <a href="javascript: void(0);" class=cta font20"><svg width="13" height="13" viewBox="0 0 13 13"
                      fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0.70775 13L0 12.2923L11.2923 1H4V0H13V9H12V1.70775L0.70775 13Z" fill="#1C1B1F" />
                    </svg>
                    Open Live Chat</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="showrooms">
      <div class="container">
        <div class="inner-container">
          <div class="row">
            <div class="col-6">
              <figure><img src="{{ asset('public/frontend/assets/img/contact/contact-image1.jpg') }}" alt="Mayuri"
                  title="Mayuri" />
              </figure>
              <div class="details">
                <div class="row">
                  <div class="col-8">
                    <h5 class="font32">
                      California
                    </h5>
                    <p class="c--gry">3891 Ranchview Dr. Richardson, California 62639</p>
                    <a href="javascript: void(0);" class="font18">Get Direction</a>
                  </div>
                  <div class="col-4 text-right">
                    <a href="javascript:void();"
                      class="btn btn-outline-dark px-4 py-2 d-flex justify-content-center align-items-center gap-2"
                      title="Need Help"><span class="material-symbols-outlined font20">call</span> Contact</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <figure><img src="{{ asset('public/frontend/assets/img/contact/contact-image2.jpg') }}" alt="Mayuri"
                  title="Mayuri" />
              </figure>
              <div class="details">
                <div class="row">
                  <div class="col-8">
                    <h5 class="font32">
                      California
                    </h5>
                    <p class="c--gry">3891 Ranchview Dr. Richardson, California 62639</p>
                    <a href="javascript: void(0);" class="font18">Get Direction</a>
                  </div>
                  <div class="col-4 text-right">
                    <a href="javascript:void();"
                      class="btn btn-outline-dark px-4 py-2 d-flex justify-content-center align-items-center gap-2"
                      title="Need Help"><span class="material-symbols-outlined font20">call</span> Contact</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
@push('scripts')
  <script></script>
@endpush
