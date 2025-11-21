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
                  <h2 class="font25 fw-medium m-0 c--blackc">Change Password</h2>
                </div>
                <div class="info">
                  <form class="change_password form_area" id="change_password">
                    @csrf
                    <div class="form-box passwrd">
                      <label class="form-label">Current Password</label>
                      <input name="current_password" type="password" class="form-control" id="current_password"
                        placeholder="Enter your current password">
                      <span class="material-symbols-outlined" style="cursor: pointer;"
                        onclick="togglePassword('current_password')">visibility</span>
                    </div>
                    <div class="form-box passwrd">
                      <label class="form-label">New Password</label>
                      <input name="new_password" id="new_password" type="password" class="form-control"
                        placeholder="Enter your new password">
                      <span class="material-symbols-outlined" style="cursor: pointer;"
                        onclick="togglePassword('new_password')">visibility</span>
                    </div>
                    <div class="form-box passwrd">
                      <label class="form-label">Confirm Password</label>
                      <input name="confirm_password" id="confirm_password" type="password" class="form-control"
                        placeholder="Re-Enter Your New Password">
                      <span class="material-symbols-outlined" style="cursor: pointer;"
                        onclick="togglePassword('confirm_password')">visibility</span>
                    </div>
                    <div class="action mt-2">
                      <button type="submit" class="btn btn-dark w-100 btn-lg py-3">Reset Password</button>
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
  <script>
    $(document).ready(function() {
      $('#change_password').validate({
        rules: {
          current_password: {
            required: true,
          },
          new_password: {
            required: true,
          },
          confirm_password: {
            required: true,
            equalTo: "#new_password",
          },
        },
        messages: {
          current_password: {
            required: "{{ __('validation.required', ['attribute' => 'Current Password']) }}",
          },
          new_password: {
            required: "{{ __('validation.required', ['attribute' => 'New Password']) }}",
          },
          confirm_password: {
            required: "{{ __('validation.required', ['attribute' => 'Confirm Password']) }}",
            equalTo: "{{ __('validation.confirmed', ['attribute' => 'Confirm Password']) }}",
          },
        },
        errorElement: "i",
        errorPlacement: function(error, element) {
          let errorContainer = $(element).closest('.form-box').find('.msg-error');
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
          $.ajax({
            url: "{{ route('user.update-password') }}",
            type: "POST",
            data: $(form).serialize(),
            success: function(response) {
              if (response.success) {
                iziNotify("", response.message, "success");
                setTimeout(function() {
                  window.location.reload();
                }, 2000);
              } else {
                iziNotify("Oops!", response.message, "error");
              }
            }
          });
        }
      });
    })
    // SHOW TEXT FROM PASSWORD
    function togglePassword(id) {
      let input = document.getElementById(id);
      let spanIcon = $(this).find('.material-symbols-outlined');
      if (input.type === "password") {
        input.type = "text";
        spanIcon.text('visibility_off');
      } else {
        input.type = "password";
        spanIcon.text('visibility');
      }
    }
  </script>
@endpush
