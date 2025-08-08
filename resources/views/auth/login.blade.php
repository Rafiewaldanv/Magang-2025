@extends('template/main')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-header text-center bg-warning text-white">
            <h4 class="mb-0">Login Akun</h4>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-warning text-white fw-bold">Masuk</button>
                </div>
            </form>
        </div>

        <div class="card-footer text-center">
            <small>Belum punya akun? Hubungi admin untuk mendaftar.</small>
        </div>
    </div>
</div>
@endsection
