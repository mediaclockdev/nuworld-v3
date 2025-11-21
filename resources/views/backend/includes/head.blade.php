<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>{{ $pageTitle ?? '' }} | {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Theme Config Js -->
  <script src={{ asset('/public/backend/assetss/js/theme-config.js') }}></script>

  <!-- App css -->
  <link href={{ asset('/public/backend/assetss/app-saas.min.css?v=' . time()) }} rel="stylesheet" type="text/css"
    id="app-style" />
  <link href={{ asset('/public/backend/assetss/dart-scss/style.css?v=' . time()) }} rel="stylesheet" type="text/css" />

  <!-- Icons css -->
  <link href={{ asset('/public/backend/assetss/icons.min.css') }} rel="stylesheet" type="text/css" />

  {{-- Favicon --}}
  <link rel="shortcut icon" href={{ asset('/public/backend/assetss/images/favicon/favicon-32x32.png') }}>
