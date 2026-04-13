<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light" data-sidebar-image="none"
    data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>

{{-- @yield('body') --}}
<body data-page="{{ str_replace('.', '/', Route::currentRouteName() ?? '') }}">
    
@yield('content')

@include('layouts.vendor-scripts')

<!-- App js -->
@vite([
    'resources/js/app-auth.js',
])
</body>

</html>
