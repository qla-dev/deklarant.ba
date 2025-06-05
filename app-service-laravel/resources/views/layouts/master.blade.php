<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="enable"  data-bs-theme>
    <script>
  (function() {
    try {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark' || savedTheme === 'light') {
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
      } else {
        // Optional: default to dark or light based on prefers-color-scheme
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
      }
    } catch (e) {
      // just in case localStorage access fails
    }
  })();
</script>
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | deklarant.ai</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="deklarant.ai - Napredni AI skener deklaracija" name="description" />
    <meta content="qla.dev" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
   <link rel="shortcut icon" href="{{ URL::asset('build/images/homepage/logo/fav-logo-2.png?v=4')}}">
    
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    
    <!-- END layout-wrapper -->


    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    <!-- Bootstrap JS (Includes Popper.js for dropdowns) -->



    
</body>

</html>
