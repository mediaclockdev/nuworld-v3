@include('backend.includes.head')

@yield('page-styles')
@stack('component-styles')
@livewireStyles
</head>

<body>
  @include('backend.includes.preloader')
  <div class="wrapper">
    @include('backend.includes.topbar')
    @include('backend.includes.sidebar')

    <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
    @include('backend.includes.footer')
  </div>
  @include('backend.includes.foot')
  @livewireScripts
</body>

</html>
