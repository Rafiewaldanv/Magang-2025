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
    <!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Anda yakin ingin keluar dari akun Anda?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form id="form-logout" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Ya, Keluar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ... isi layout ... -->

<!-- Modal Logout -->


</body>
</html>

</body>
</html>
