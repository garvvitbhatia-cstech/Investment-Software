@php
$siteUrl = env('APP_URL');
@endphp
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{$siteUrl}}public/css/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="{{$siteUrl}}public/css/toastify.css">
    <link href="{{$siteUrl}}public/css/new-style.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CaaBaa Cars</title>
    <!-- JS -->
    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{$siteUrl}}public/js/toastify.js"></script>
    <!--<script src="{{$siteUrl}}public/js/owl.carousel.min.js"></script>  -->
    <!--<script type="text/javascript" src="{{$siteUrl}}public/js/custom.js"></script> -->
  </head>
  <body>
    <!--Desktop Header start here-->
    @include('element.header')
    <!--Desktop Header end here-->
    @yield('content')
    @include('element.footer')
  </body>
</html>