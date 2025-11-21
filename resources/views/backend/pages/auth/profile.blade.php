@extends('backend.layouts.app')
@section('page-styles')
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/backend/assetss/cropper/cropper.min.css') }}" />
  <script src="{{ asset('/public/backend/assetss/cropper/cropper.min.js') }}"></script>
  <link href="{{ asset('/public/backend/assetss/select2/select2.min.css') }}" rel="stylesheet" />

  <style>
    .select2-results__option--selected {
      display: none;
    }

    .select2-selection__choice {
      background-color: #0062D0 !important;
    }
  </style>
@endsection
@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="page-title-box pt-3 pb-3">
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">My Profile</li>
            </ol>
          </div>
          <h4 class="page-title text-primary">My Profile</h4>
        </div>
      </div>
    </div>
    <!-- end page title -->
    <div class="row min-VH">
      <div class="col-xl-12 col-lg-12">
        <div class="card card-h-100">
          <div class="card-body p-0">
            <div class="myaccount-wrap">
              <div class="leftarea">
                <div class="user_section">
                  <x-profile-picture-upload :imageLink="userImageById('admin', user('admin')->id)['image']" :route="route('admin.upload_profile_picture')" />
                  <div class="info">
                    <h4 class="mb-1 mt-0 text-white">{{ $user->first_name }} {{ $user->middle_name }}
                      {{ $user->last_name }}</h4>
                    <p class="text-white font-14">{{ $role->name }}</p>
                  </div>
                </div>
                <div class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                  <a class="nav-link active show" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                    role="tab" aria-controls="v-pills-profile" aria-selected="true">
                    <span class="d-flex align-items-center w-100"><i class="mdi mdi-account-box-outline"></i>
                      Profile</span>
                  </a>
                  <a class="nav-link" id="v-pills-changepassword-tab" data-bs-toggle="pill" href="#v-pills-changepassword"
                    role="tab" aria-controls="v-pills-changepassword" aria-selected="false">
                    <span class="d-flex align-items-center w-100"><i class="mdi mdi-lock-outline"></i>
                      Change Password</span>
                  </a>
                  <a class="nav-link" id="login-history-tab" data-bs-toggle="pill" href="#login-history" role="tab"
                    aria-controls="v-pills-loginhistory" aria-selected="false">
                    <span class="d-flex align-items-center w-100"><i class="mdi mdi-history"></i> Login
                      History</span>
                  </a>
                  {{-- <a class="nav-link " id="v-pills-activities-tab" data-bs-toggle="pill" href="#v-pills-activities"
                    role="tab" aria-controls="v-pills-activities" aria-selected="false">
                    <span class="d-flex align-items-center w-100"><i class="mdi mdi-television"></i>
                      Activities</span>
                  </a> --}}
                  <a class="nav-link " id="v-pills-permissions-tab" data-bs-toggle="pill" href="#v-pills-permissions"
                    role="tab" aria-controls="v-pills-permissions" aria-selected="false">
                    <span class="d-flex align-items-center w-100"><i class="mdi mdi-lock-open-outline"></i>
                      Permissions</span>
                  </a>
                </div>
              </div>

              <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade active show" id="v-pills-profile" role="tabpanel"
                  aria-labelledby="v-pills-profile-tab">
                  <div class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                    <h3 class="fw-medium">Profile</h3>
                  </div>
                  <form id="profileForm">
                    <div class="idvblock">
                      <h5 class="mb-3 text-primary font-16 fw-medium">
                        <span>Personal Details</span>
                      </h5>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="mb-4 required"><label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control only-alphabet-symbols" id="firstname"
                              name="firstname" placeholder="Enter first name" value = "{{ $user->first_name }}">
                            <div class="firstname-error-container"></div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="mb-4 position-relative"><label for="middlename" class="form-label">Middle
                              Name</label>
                            <input type="text" class="form-control only-alphabet-symbols" id="middlename"
                              name="middlename" id="middlename" placeholder="Enter middle name"
                              value = "{{ $user->middle_name }}">
                            <div class="middlename-error-container"></div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="mb-4 position-relative"><label for="lastname" class="form-label">Last
                              Name</label>
                            <input type="text" class="form-control only-alphabet-symbols" id="lastname"
                              name="lastname" placeholder="Enter last name" value = "{{ $user->last_name }}">
                            <div class="lastname-error-container"></div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-4 required"><label for="email" class="form-label">Email ID</label>
                            <input type="email" class="form-control" name="useremail" id="useremail"
                              placeholder="Enter email" value = "{{ $user->email }}">
                            <div class="email-error-container"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <x-phone-number :required="true" :previousValue="$user->phone" :name="'usermobile'" :id="'usermobile'" />
                        </div>
                      </div>
                      <x-address-form />

                      <div class="row">
                        <div class="col-md-12 ">
                          <div class="d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-auto">Submit</button>
                          </div>

                        </div>
                      </div>
                    </div>

                    <div class="idvblock">
                      <h5 class="mb-3 text-primary font-16 fw-medium">
                        <span>Official Details</span>
                      </h5>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="mb-4"><label for="designation" class="form-label">Designation</label>
                            <p class="font-16 page-title text-secondary"> N/A </p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="mb-4"><label for="superadmin" class="form-label">Email ID</label>
                            <p class="font-16 page-title text-secondary">
                              {{ $user->email }}</p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="mb-4"><label for="department" class="form-label">Department</label>
                            <p class="font-16 page-title text-secondary">Admin
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </form>
                </div>
                <div class="tab-pane fade " id="v-pills-changepassword" role="tabpanel"
                  aria-labelledby="v-pills-changepassword-tab">
                  <div class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                    <h3 class="fw-medium">Change Password</h3>
                  </div>
                  <form id="passwordForm">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="mb-4 required">
                          <label for="currentpassword" class="form-label">Current Password</label>
                          <div class="input-group input-group-merge">
                            <input type="password" name="currentpassword" id="currentpassword" class="form-control "
                              placeholder="Enter your current password">
                            <div class="input-group-text" data-password="false">
                              <span class="password-eye"></span>
                            </div>
                          </div>
                          <div class="currentpassword-error-container"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-5">
                        <x-password-strength />
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5">
                        <div class="mb-4 required">
                          <label for="confirmpassword" class="form-label">Confirm New Password</label>
                          <div class="input-group input-group-merge">
                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control"
                              placeholder="Re-enter the new password">
                            <div class="input-group-text" data-password="false">
                              <span class="password-eye"></span>
                            </div>
                          </div>
                          <div class="confirmpassword-error-container"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-auto">Submit</button>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade " id="login-history" role="tabpanel"
                  aria-labelledby="v-pills-loginhistory-tab">
                  <div class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                    <h3 class="fw-medium">Login History</h3>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item" onclick="exportTableCSV(true)">Export
                          Visible to CSV</a>
                        <a href="javascript:void(0);" class="dropdown-item" onclick="exportTableCSV()">Export All to
                          CSV</a>
                      </div>
                    </div>
                  </div>
                  <livewire:admin.login-history-table />
                </div>
                <div class="tab-pane fade " id="v-pills-activities" role="tabpanel"
                  aria-labelledby="v-pills-activities-tab">
                  <div class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                    <h3 class="fw-medium">Activities</h3>
                    <div class="dropdown ms-1">
                      <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="mdi mdi-dots-vertical"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item">Import
                          from CSV</a>
                        <a href="javascript:void(0);" class="dropdown-item">Export
                          to CSV</a>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex filter-small justify-content-end align-items-center mb-2">
                    <div class="d-flex me-2">
                      <select class="form-select">
                        <option selected="">Filter by Module</option>
                        <option value="1">Admin Settings</option>
                      </select>
                    </div>
                    <div class="d-flex me-2">
                      <select class="form-select">
                        <option selected="">Filter by Submodule</option>
                        <option value="1">Roles</option>
                      </select>
                    </div>
                    <div class="d-flex">
                      <div class="input-group input-group-text font-14 bg-white" id="reportrange">
                        <i class="mdi mdi-calendar-range font-14 d-flex me-2"></i>
                        <span class="">January 9, 2025 - February 7, 2025</span>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex filter-small justify-content-start align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                      <label class="me-2 fw-medium">Show rows: </label>
                      <select class="form-select w-auto">
                        <option selected="">10</option>
                        <option value="1">8</option>
                      </select>
                    </div>
                  </div>

                  <div class="table-responsive activities-table">
                    <table class="table table-centered mb-0">
                      <thead>
                        <tr>
                          <th>Sl.</th>
                          <th>Date & Time</th>
                          <th>Accessed By</th>
                          <th>IP Address</th>
                          <th>Activity Type</th>
                          <th>Action Taken</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>2025-01-10 12:322:59</td>
                          <td>johnsm@mailinator.com</td>
                          <td>256.356.1.2</td>
                          <td><span role="button" class="badge badge-success-lighten" title=""
                              onclick="">Created</span></td>
                          <td>Importing CSV files</td>
                        </tr>
                      </tbody>
                    </table>
                  </div> <!-- end table-responsive-->

                  <div class="pagination mt-3 mb-1 d-flex justify-content-between align-items-center">
                    <div class="showing">Showing 1 to 10 of 30 entries</div>
                    <nav aria-label="...">
                      <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1"
                            aria-disabled="true">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page"><a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a>
                        </li>
                      </ul>
                    </nav>
                  </div>
                </div>
                <div class="tab-pane fade " id="v-pills-permissions" role="tabpanel"
                  aria-labelledby="v-pills-permissions-tab">
                  <div class="heading d-flex align-items-center justify-content-between pb-2 mb-3">
                    <h3 class="fw-medium">Permissions</h3>
                  </div>
                  <livewire:admin.view-permission-table />
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('page-scripts')
  <script src="{{ asset('/public/backend/assetss/js/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/moment.min.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/js/daterangepicker.js') }}"></script>
  <script src="{{ asset('/public/backend/assetss/select2/select2.min.js') }}"></script>
  <script src="{{ asset('/public/common/js/custom_input.js?v=1' . time()) }}"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2();

      $('.cancel-btn').on('click', function() {
        window.location.reload();
      });

      function ajaxFormSubmission(url, data, callback) {
        $.ajax({
          type: "POST",
          url: url,
          data: data,
          success: function(response) {
            if (response.success) {
              swalNotify("Success!", response.message, "success");
              callback(true);
            } else {
              swalNotify("Oops!", response.message, "error");
              callback(false);
            }
          },
          error: function(error) {
            swalNotify("Error!", error.responseJSON.message, "error");
            callback(false);
          }
        });
      }

      $("#profileForm").validate({
        rules: {
          firstname: {
            required: true,
            minlength: 3,
            maxlength: 100
          },
          middlename: {
            required: false,
            maxlength: 100
          },
          lastname: {
            maxlength: 100
          },
          usermobile: {
            required: true,
            validPhone: true // Custom validation method from PhoneNumber Component
          },
          useremail: {
            required: true,
            email: true,
            maxlength: 100
          },
          address1: {
            required: true,
            maxlength: 100
          },
          address2: {
            maxlength: 100
          },
          landmark: {
            maxlength: 100
          },
          city: {
            required: true,
            maxlength: 100
          },
          state: {
            required: true
          },
          zip_code: {
            required: true,
            maxlength: 15
          },
          country: {
            required: true
          }
        },
        messages: {
          firstname: {
            required: "{{ __('validation.required', ['attribute' => 'First Name']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'First Name', 'min' => 3]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'First Name', 'max' => 100]) }}"
          },
          middlename: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Middle Name', 'max' => 100]) }}"
          },
          lastname: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Last Name', 'max' => 100]) }}"
          },
          usermobile: {
            required: "{{ __('validation.required', ['attribute' => 'Phone']) }}",
            validPhone: "{{ __('validation.invalid', ['attribute' => 'Phone']) }}"
          },
          useremail: {
            required: "{{ __('validation.required', ['attribute' => 'Email ID']) }}",
            email: "{{ __('validation.invalid', ['attribute' => 'Email ID Format']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Email ID', 'max' => 100]) }}"
          },
          address1: {
            required: "{{ __('validation.required', ['attribute' => 'Address 1']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Address 1', 'max' => 100]) }}"
          },
          address2: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Address 2', 'max' => 100]) }}"
          },
          landmark: {
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Landmark', 'max' => 100]) }}"
          },
          city: {
            required: "{{ __('validation.required', ['attribute' => 'City']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'City', 'max' => 100]) }}"
          },
          state: {
            required: "{{ __('validation.required', ['attribute' => 'State']) }}"
          },
          zip_code: {
            required: "{{ __('validation.required', ['attribute' => 'Zip Code']) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'Zip Code', 'max' => 15]) }}"
          },
          country: {
            required: "{{ __('validation.required', ['attribute' => 'Country']) }}"
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          let errorContainer = $(`#${element.attr('id')}-error-container`);
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback
          }
        },
        highlight: function(element) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function(form) {
          let data = $('#profileForm').serialize();

          ajaxFormSubmission("{{ route('admin.profile') }}", data, function(status) {
            if (status) {
              console.log('success');
            }
          }); //route('admin.update_address')
        }
      });

      $("#passwordForm").validate({
        rules: {
          currentpassword: {
            required: true
          },
          newpassword: {
            required: true,
            minlength: 8,
            maxlength: 60
          },
          confirmpassword: {
            required: true,
            equalTo: "#newpassword"
          }
        },
        messages: {
          currentpassword: {
            required: "{{ __('validation.required', ['attribute' => 'Current Password']) }}"
          },
          newpassword: {
            required: "{{ __('validation.required', ['attribute' => 'New Password']) }}",
            minlength: "{{ __('validation.minlength', ['attribute' => 'New Password', 'min' => 8]) }}",
            maxlength: "{{ __('validation.maxlength', ['attribute' => 'New Password', 'max' => 60]) }}"
          },
          confirmpassword: {
            required: "{{ __('validation.required', ['attribute' => 'Confirm Password']) }}",
            equalTo: "{{ __('validation.match', ['attribute' => 'New Password', 'target' => 'Confirm Password']) }}"
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          let errorContainer = element.closest('.mb-4').find(
            `.${element.attr('id')}-error-container`); // For Passwords
          if (errorContainer.length) {
            error.appendTo(errorContainer);
          } else {
            error.insertAfter(element); // Fallback
          }
        },
        highlight: function(element) {
          $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid").addClass("is-valid");
        },
        submitHandler: function(form) {
          let currPass = $("#currentpassword").val();
          let newPass = $("#newpassword").val();
          if (currPass == newPass) {
            $("#newpassword").addClass('is-invalid');
            $(".newpassword-error-container").addClass('error').html(
              "{{ __('validation.cant_match', ['attribute' => 'New Password', 'target' => 'Current Password']) }}"
            );
            return
          }
          $("#changepasswordBtn").prop('disabled', true).text('Updating...');
          const data = {
            _token: "{{ csrf_token() }}",
            currentpassword: $("#currentpassword").val(),
            newpassword: $("#newpassword").val()
          };
          ajaxFormSubmission("{{ route('admin.update_password') }}", data, function(status) {
            if (status) {
              $('#passwordForm').trigger('reset');
              $('#password-strength').css('display', 'none');
              $("#changepasswordBtn").prop('disabled', false).text('Change Password');
              $('.is-valid').removeClass('is-valid');
            }
          });
        }
      });

      // Submit Profile Form on Enter
      $("#profileForm").on('keypress', function(e) {
        if (e.keyCode === 13) {
          $("#profileForm").submit();
        }
      })

    });

    function exportTableCSV(filtered = false) {
      Livewire.dispatch('exportCSVComponent', [filtered]);
    }
  </script>
@endsection
