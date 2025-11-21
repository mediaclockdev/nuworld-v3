<div class="furniture__menuwrap">
  <div class="container-xxl">
    <div class="row">
      <div class="col-lg-12">
        <nav class="navbar navbar-expand-xl navbar-light">
          <div class="container-fluid p-0">
            <a href="{{ route('home') }}" title="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}" class="mobilelogo" id="ham-menu"><img
                src="{{ siteLogo() }}" alt="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}"
                title="{{ $siteSettings['sitename'] ?? 'Sundew Ecomm' }}" /></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav"
              aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_nav">
              <ul class="navbar-nav left-items">
                @foreach ($navMenus as $index => $navMenu)
                  @if ($navMenu->parent_id == 0)
                    @php
                      $hasGrandChildren = false;
                      $hasChildren = $navMenu->children->isNotEmpty();
                      if ($hasChildren) {
                          foreach ($navMenu->children as $subnavMenu) {
                              if ($subnavMenu->children->isNotEmpty()) {
                                  $hasGrandChildren = true;
                                  break;
                              }
                          }
                      }
                      // Get the banner corresponding to this menu index (fallback to first banner)
                      $bannerData = $mega_menu_banner[$index] ?? ($mega_menu_banner[0] ?? null);
                      $settings = [];
                      if (!empty($bannerData) && !empty($bannerData['settings'])) {
                          $settings = json_decode($bannerData['settings'], true);
                      }
                    @endphp
                    {{-- Type 1: Mega Menu (has grand children) --}}
                    @if ($hasGrandChildren)
                      <li class="nav-item dropdown has-megamenu">
                        <a class="nav-link dropdown-toggle" href="#"
                          data-bs-toggle="dropdown">{{ $navMenu->title }}</a>
                        <div class="dropdown-menu megamenu" role="menu">
                          <div class="row g-3">
                            <!-- Left side -->
                            <div class="col-lg-8 col-8">
                              <div class="col-megamenu_wrap">
                                @foreach ($navMenu->children as $subnavMenu)
                                  <div class="col-megamenu">
                                    <h6 class="text-uppercase font18 fw-medium mb-2">{{ $subnavMenu->title }}</h6>
                                    <ul class="list-unstyled">
                                      @foreach ($subnavMenu->children as $level3)
                                        <li>
                                          <a href="{{ filter_var($level3->slug, FILTER_VALIDATE_URL)
                                              ? $level3->slug
                                              : ($level3->slug == '#' || empty($level3->slug)
                                                  ? '#'
                                                  : route('category.slug', $level3->slug)) }}"
                                            title="{{ $level3->title }}">
                                            {{ $level3->title }}
                                          </a>
                                        </li>
                                      @endforeach
                                    </ul>
                                  </div>
                                @endforeach
                              </div>
                            </div>
                            <!-- Right side: Dynamic Banner -->
                            <div class="col-lg-4 col-4">
                              <div class="menu-banner">
                                <figure class="m-0">
                                  <a href="{{ $settings['hyper_link'] ?? '#' }}" class="mega-banner-link">
                                    <img
                                      src="{{ !empty($settings['image']) ? asset(config('defaults.banner_image_path') . $settings['image']) : asset('public/frontend/assets/img/home/megamenu-banner.jpg') }}"
                                      alt="{{ $settings['alt_text'] ?? 'Alt text' }}"
                                      title="{{ $settings['alt_text'] ?? 'Alt text' }}"
                                      class="imageFit img-with-radius" />
                                  </a>
                                </figure>
                              </div>
                            </div>
                          </div> <!-- end row -->
                        </div> <!-- dropdown-menu -->
                      </li>
                      {{-- Type 2: Simple Dropdown (has children but no grand children) --}}
                    @elseif ($hasChildren)
                      <li class="nav-item dropdown has-singleListing">
                        <a class="nav-link dropdown-toggle" href="#"
                          data-bs-toggle="dropdown">{{ $navMenu->title }}</a>
                        <div class="dropdown-menu singleListing-menu" role="menu">
                          <div class="dropdown-content">
                            @foreach ($navMenu->children as $subnavMenu)
                              <a href="{{ filter_var($subnavMenu->slug, FILTER_VALIDATE_URL)
                                  ? $subnavMenu->slug
                                  : ($subnavMenu->slug == '#' || empty($subnavMenu->slug)
                                      ? '#'
                                      : route('category.slug', $subnavMenu->slug)) }}"
                                class="dropdown-item" title="{{ $subnavMenu->title }}">
                                {{ $subnavMenu->title }}
                              </a>
                            @endforeach
                          </div>
                        </div>
                      </li>
                      {{-- Type 3: Simple Link (no children) --}}
                    @else
                      <li class="nav-item">
                        <a class="nav-link"
                          href="{{ filter_var($navMenu->slug, FILTER_VALIDATE_URL) ? $navMenu->slug : '#' }}"
                          title="{{ $navMenu->title }}">
                          {{ $navMenu->title }}
                        </a>
                      </li>
                    @endif
                  @endif
                @endforeach
              </ul>
              <ul class="navbar-nav ms-auto right-items gap-4">
                {{-- Add other nav items if needed --}}
                <li class="nav-item"><a class="nav-link" href="{{ route('stores') }}">Find a store</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cms.page', 'contact-us') }}">Help</a></li>
              </ul>
            </div>
          </div> <!-- container-fluid.// -->
        </nav>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Initialize all dropdowns
    document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
      // Create Bootstrap dropdown instance
      const dropdown = new bootstrap.Dropdown(toggle);
      // Ensure click toggles show/hide correctly
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        // Close other open dropdowns first (optional)
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
          if (menu !== toggle.nextElementSibling) {
            menu.classList.remove('show');
          }
        });
        document.querySelectorAll('.dropdown-toggle.show').forEach(btn => {
          if (btn !== toggle) {
            btn.classList.remove('show');
          }
        });
        // Toggle the clicked dropdown
        dropdown.toggle();
      });
    });
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => menu.classList.remove('show'));
        document.querySelectorAll('.dropdown-toggle.show').forEach(toggle => toggle.classList.remove('show'));
      }
      // Close navbar collapse if clicking outside navbar
      if (!e.target.closest('.navbar')) {
        const collapse = document.getElementById('main_nav');
        if (collapse && collapse.classList.contains('show')) {
          const bsCollapse = bootstrap.Collapse.getInstance(collapse);
          if (bsCollapse) bsCollapse.hide();
        }
      }
    });
    // Handle ham-menu click for mobile toggle
    const hamMenu = document.getElementById('ham-menu');
    if (hamMenu) {
      hamMenu.addEventListener('click', function(e) {
        e.preventDefault();
        const collapse = document.getElementById('main_nav');
        if (collapse) {
          const bsCollapse = bootstrap.Collapse.getInstance(collapse);
          if (bsCollapse) {
            bsCollapse.toggle();
          }
        }
      });
    }
  });
</script>
<script>
  document.querySelectorAll('.mega-banner-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
      e.preventDefault(); // prevent default blocked click
      window.location.href = link.href; // navigate manually
    });
  });
</script>
