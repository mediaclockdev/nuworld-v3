<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
  <!-- Brand Logo Light -->
  <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
    <span class="logo-lg">
      {{-- <img src={{ siteLogo() }} alt="logo"> --}}
      <h2>NEUWRLD</h2>
    </span>
    <span class="logo-sm">
      <img src={{ asset('/public/backend/assetss/images/logo-dark-sm.png') }} alt="small logo">
    </span>
  </a>
  <!-- Brand Logo Dark -->
  <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
    <span class="logo-lg">
      {{-- <img src={{ siteLogo() }} alt="dark logo" id="header-logo"> --}}
      <h2>NEUWRLD</h2>
    </span>
    <span class="logo-sm">
      <img src={{ asset('/public/backend/assetss/images/logo-dark-sm.png') }} alt="small logo">
    </span>
  </a>
  <!-- Sidebar Hover Menu Toggle Button -->
  <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
    <i class="ri-checkbox-blank-circle-line align-middle"></i>
  </div>
  <!-- Full Sidebar Menu Close Button -->
  <div class="button-close-fullsidebar">
    <i class="ri-close-fill align-middle"></i>
  </div>
  <!-- Sidebar -left -->
  <div class="h-100" id="leftside-menu-container" data-simplebar>
    <!-- Leftbar User -->
    <div class="leftbar-user">
      <a href="javascript:">
        <img src="{{ asset('/public/backend/assetss/images/users/avatar-1.jpg') }}" alt="user-image" height="42"
          class="rounded-circle shadow-sm">
        <span class="leftbar-user-name mt-2">Dominic Keller</span>
      </a>
    </div>
    {{-- <!--- Sidemenu --> --}}
    <ul class="side-nav">
      <li class="side-nav-item">
        <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
          <i class="ri-dashboard-line"></i>
          {{-- <span class="badge bg-warning text-dark float-end"> 5 </span> --}}
          <span> Dashboard</span>
        </a>
      </li>
      {{-- Display all menus for all users --}}
      @if (isset($menus) && is_array($menus))
        @foreach ($menus as $menu)
          <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebar{{ $menu['id'] }}" aria-expanded="false"
              aria-controls="sidebarDropdown" class="side-nav-link">
              <i class="{{ $menu['icon'] }}"></i>
              <span> {{ $menu['name'] }} </span>
              @if (isset($menu['submodules']) && count($menu['submodules']) > 0)
                <span class="menu-arrow"></span>
              @endif
            </a>
            @if (isset($menu['submodules']) && count($menu['submodules']) > 0)
              <div class="collapse" id="sidebar{{ $menu['id'] }}">
                <ul class="side-nav-second-level">
                  @foreach ($menu['submodules'] as $submodule)
                    <li>
                      @routeExists($submodule['path'])
                        <a href="{{ route($submodule['path']) }}">{{ $submodule['name'] }}</a>
                      @else
                        <a href="{{ route('coming-soon') }}">{{ $submodule['name'] }}</a>
                      @endrouteExists
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </li>
        @endforeach
      @endif
    </ul>
  </div>
  {{-- <!--- End Sidemenu --> --}}
  <div class="clearfix"></div>
</div>
