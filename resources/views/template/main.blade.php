<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('template/_head')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('css-extra')
</head>
{{-- <body class="bg-light" oncontextmenu="return false"> --}}
<body class="bg-light">
    @include('template/_navbar')
    @yield('content')
    @include('template/_js')
    @include('template/admin/_js')
    @yield('js-extra')
</body>
</html>
