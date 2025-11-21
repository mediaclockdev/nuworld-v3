<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card">
      <div class="d-flex card-header justify-content-between align-items-center">
        <h4 class="header-title">User Management @if ($link)
            <a href="{{ route('admin.users') }}" title="Sales Analytics"><i class="ri-arrow-right-up-line"></i></a>
          @endif
        </h4>

        <div class="dropdown ms-1">
          <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="mdi mdi-dots-vertical"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a href="{{ route('admin.users.export.stream') }}" class="dropdown-item">Export to CSV</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-centered mb-0">
            <thead>
              <tr>
                <th>Sl.</th>
                <th>User Name</th>
                <th>Email Address</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined On</th>

              </tr>
            </thead>
            <tbody>
              @forelse ($adminUsers as $key => $user)
                <tr>
                  <td>{{ $key + 1 }}</td>

                  <td class="updatedby">
                    <div class="thumb">
                      <span class="account-user-avatar">
                        <img src="{{ asset('public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image"
                          width="32" class="rounded-circle">
                      </span>
                      <div class="inf">
                        {{ $user['first_name'] }} {{ $user['middle_name'] }} {{ $user['last_name'] }}
                      </div>
                    </div>
                  </td>
                  <td>{{ $user['email'] }}</td>
                  <td>{{ $user['roleNames'] ?? 'N/A' }}</td>
                  <td>
                    <span role="button"
                      class="badge {{ $user['status'] == 1 ? 'badge-success-lighten' : 'badge-danger-lighten' }}"
                      title="">
                      {{ $user['status'] == 1 ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>{{ convertDateTimeHours($user['created_at']) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6">
                    <div role="alert" class="alert alert-danger text-center text-danger">
                      No Users Found
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>


          </table>
        </div> <!-- end table-responsive-->
      </div> <!-- end card-body-->
    </div> <!-- end card-->
  </div> <!-- end col -->
</div>
<!-- end row -->
