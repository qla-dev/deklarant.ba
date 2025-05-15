<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">

    <head>
    <meta charset="utf-8" />
    <title>@yield('title') | deklarant.ba - Napredni AI skener deklaracija</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="deklarant.ba - Napredni AI skener deklaracija" name="description" />
    <meta content="qla.dev" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
        @include('layouts.head-css')
  </head>

    @yield('body')

    @yield('content')

    @include('layouts.vendor-scripts')
    </body>
</html>
