
<nav class="navbar navbar-expand navbar-dark bg-theme-1 fixed-top shadow-sm">
    <div class="container">
        <ul class="nav navbar-nav">
            <li class="nav-item" style="{{ is_int(strpos(Request::path(), 'dashboard')) ? 'visibility:hidden' : 'visibility:visible' }}">
                <a onclick="sessionStorage.clear();" class="nav-link text-white fw-bold" href="/"><i class="fa fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span></a>
            </li>
        </ul>
        <a class="navbar-brand mx-auto" href="/">
            <img src="{{ asset('assets/images/logo-2-white.png') }}" height="40" alt="img">
        </a>
        <ul class="nav navbar-nav">
            <li class="nav-item">
            <a class="nav-link text-white fw-bold" id="btn-logout" href="#">
    <span class="d-none d-md-inline">Keluar</span> <i class="fa fa-sign-out"></i>
</a>

<form id="form-logout" class="d-none" method="POST" action="{{ route('logout') }}">
    @csrf
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('btn-logout').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm("Apakah kamu yakin ingin logout?")) {
                document.getElementById('form-logout').submit();
            }
        });
    });
</script>

</li>
        </ul>
    </div>
</nav>
