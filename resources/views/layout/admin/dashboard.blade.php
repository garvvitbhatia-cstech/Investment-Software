@php
$siteUrl = env('APP_URL');
@endphp
<!doctype html>
<html
  lang="en"
  class="layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-skin="default"
  data-assets-path="{{$siteUrl}}public/admin/"
  data-template="vertical-menu-template"
  data-bs-theme="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Admin | MSP</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{$siteUrl}}public/admin/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/pickr/pickr-themes.css" />

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/css/core.css" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/css/demo.css" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/fonts/flag-icons.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/css/pages/cards-advance.css" />
    <link href="{{ asset('public/css/sweet-alert.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{$siteUrl}}public/admin/vendor/css/pages/page-profile.css" />
    
     <script src="{{$siteUrl}}public/admin/vendor/libs/jquery/jquery.js"></script>

    <!-- Helpers -->
    <script src="{{$siteUrl}}public/admin/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{$siteUrl}}public/admin/vendor/js/template-customizer.js"></script>

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{$siteUrl}}public/admin/js/config.js"></script>
    <style>
.user_details label{ font-weight: bold;width: 205px;}
</style>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        @include('element.admin.sidebar')
        
         @include('element.admin.jquery')

        <div class="menu-mobile-toggler d-xl-none rounded-1">
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
            <i class="ti tabler-menu icon-base"></i>
            <i class="ti tabler-chevron-right icon-base"></i>
          </a>
        </div>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <!-- / Navbar -->
          @include('element.admin.header')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
             @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            @include('element.admin.footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js -->

   

    <script src="{{$siteUrl}}public/admin/vendor/libs/popper/popper.js"></script>
    <script src="{{$siteUrl}}public/admin/vendor/js/bootstrap.js"></script>
    <script src="{{$siteUrl}}public/admin/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/libs/@algolia/autocomplete-js.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/libs/pickr/pickr.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/libs/hammer/hammer.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/libs/i18n/i18n.js"></script>

    <script src="{{$siteUrl}}public/admin/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{$siteUrl}}public/admin/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{$siteUrl}}public/admin/vendor/libs/swiper/swiper.js"></script>
    <script src="{{$siteUrl}}public/admin/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>

    <!-- Main JS -->

    <script src="{{$siteUrl}}public/admin/js/main.js"></script>
    <script src="{{$siteUrl}}public/admin/js/forms-selects.js"></script>

    <!-- Page JS -->
    <script src="{{$siteUrl}}public/admin/js/dashboards-analytics.js"></script>
    <script src="{{ asset('public/js/sweet-alert.min.js') }}" ></script>
  </body>
</html>
