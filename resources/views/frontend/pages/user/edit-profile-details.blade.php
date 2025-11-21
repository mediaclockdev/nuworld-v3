@extends('frontend.layouts.app')

@section('title', @$title)

@section('content')

  <section class="breadcrumb-wrapper py-4 border-top">
    <div class="container-xxl">
      <ul class="breadcrumbs">
        <li><a href="javascript:void();">Home</a></li>
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
                <div class="heading border-bottom pb-4">
                  <h2 class="font25 fw-medium m-0 c--blackc">Edit Details</h2>
                </div>
                <div class="info">
                  <form class="allForm edit_details" id="edit_details">
                    @csrf
                    <div class="form-element has-value">
                      <label class="form-label">First Name <em>*</em></label>
                      <input name="first_name" type="text" class="form-field only-alphabet-symbols"
                        value="{{ user()->first_name ?? '' }}">
                    </div>
                    <div class="form-element has-value">
                      <label class="form-label">Last Name </label>
                      <input name="last_name" type="text" class="form-field only-alphabet-symbols"
                        value="{{ user()->last_name ?? '' }}">
                    </div>
                    <div class="form-element has-value">
                      <label class="form-label">Email <em>*</em></label>
                      <input name="email" type="email" class="form-field" value="{{ user()->email ?? '' }}" readonly>
                    </div>
                    <div class="form-element has-value">
                      {{-- <input name="phone" type="text" class="form-field" value="{{ user()->phone ?? '' }}"> --}}
                      <x-phone-number-frontend :required="true" :previousValue="user()->phone ?? ''" :name="'phone'" :id="'phone'" />
                      <label class="form-label">Mobile No <em>*</em></label>
                    </div>
                    <div class="form-element has-value form-selects">
                      <label class="form-label">Gender <em>*</em></label>
                      <select name="gender" id="" class="form-select">
                        <option value="" {{ !empty(user()->gender) ? '' : 'selected' }}> Select Gender</option>
                        <option value="1" {{ user()->gender == '1' ? 'selected' : '' }}>Male</option>
                        <option value="2" {{ user()->gender == '2' ? 'selected' : '' }}>Female</option>
                        <option value="3" {{ user()->gender == '3' ? 'selected' : '' }}>Other</option>
                      </select>
                    </div>
                    <div class="form-element has-value birthdate">
                      <label class="form-label">Date of Birth </label>
                      <input name="dob" type="date" class="form-field" max="{{ date('Y-m-d') }}"
                        value="{{ user()->dob ? date('Y-m-d', strtotime(user()->dob)) : '' }}">
                    </div>
                    <div class="action mt-2">
                      <button type="submit" class="btn btn-dark w-100 btn-lg py-3">Save Details</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>

  <script>
    $(document).ready(function() {
      $("#edit_details").validate({
        rules: {
          first_name: {
            required: true
          },
          /* last_name: {
              required: true
          }, */
          email: {
            required: true,
            email: true
          },
          phone: {
            required: true,
            validPhone: true // Custom validation method from PhoneNumber Component
          },
          gender: {
            required: true
          },
          dob: {
            date: true
          }
        },
        messages: {
          first_name: {
            required: "{{ __('validation.required', ['attribute' => 'First Name']) }}"
          },
          /* last_name: {
              required: "{{ __('validation.required', ['attribute' => 'Last Name']) }}"
          }, */
          email: {
            required: "{{ __('validation.required', ['attribute' => 'Email']) }}",
            email: "{{ __('validation.email', ['attribute' => 'Email']) }}"
          },
          phone: {
            required: "{{ __('validation.required', ['attribute' => 'Pnone No']) }}",
            number: "{{ __('validation.numeric', ['attribute' => 'Pnone No']) }}"
          },
          gender: {
            required: "{{ __('validation.required', ['attribute' => 'Gender']) }}"
          },
          dob: {
            date: "{{ __('validation.date', ['attribute' => 'Date of Birth']) }}"
          }
        },
        errorElement: "i",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
          $.ajax({
            url: "{{ route('user.update-profile') }}",
            type: "POST",
            data: $(form).serialize(),
            success: function(response) {
              if (response.success) {
                iziNotify("", response.message, "success");
              } else {
                console.log("response: ", response);

                iziNotify("Oops!", response.message, "error");
              }
            },
            error: function(xhr) {
              iziNotify("Oops!", xhr.responseJSON.message, "error");
            }
          });
        }
      });
    });
  </script>
@endpush
