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
              <div class="profile_details h-100 overflow-hidden border flow-rootX2">
                <div class="heading d-flex justify-content-between align-items-center border-bottom pb-4">
                  <h2 class="font25 fw-medium m-0">Profile Details</h2>
                  <a href="{{ route('user.profile.edit') }}" class="btn btn-outline-dark px-5 py-3"
                    title="Edit Details">Edit Details</a>
                </div>
                <div class="info">
                  <ul class="profiledetails">
                    <li>Mobile No</li>
                    <li>{{ $user->phone ?? 'N/A' }}</li>
                    <li>Full Name</li>
                    <li>{{ $user->name }}</li>
                    <li>Email</li>
                    <li>{{ $user->email }}</li>
                    <li>Gender</li>
                    <li>{{ $user->gender_text }}</li>
                    <li>Date of Birth</li>
                    <li>{{ $user->dob ? date('d/m/Y', strtotime($user->dob)) : 'N/A' }}</li>
                  </ul>
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
@endpush
