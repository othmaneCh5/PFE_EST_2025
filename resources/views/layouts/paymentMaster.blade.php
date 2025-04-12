<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  <!-- Include any required head assets (CSS, fonts, etc.) -->
  @vite(['resources/assets/scss/app.scss'])
  @yield('page-style')
</head>
<body>
  <!-- No Navbar or Footer! -->
  <div class="payment-page-wrapper">
    @yield('content')
  </div>
  <!-- Include necessary scripts -->
  @vite(['resources/assets/js/app.js'])
  @yield('vendor-script')
  @yield('page-script')
</body>
</html>
