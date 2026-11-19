@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<div class="container-fluid px-0" style="max-width: 460px;">
    <div class="sg-card p-4 p-md-5">
        <div class="text-center mb-4">
            <h1 class="h3 mb-2">Masuk ke SiaGa Bencana</h1>
            <p class="text-body-secondary mb-0">Gunakan akun petugas yang telah diberikan.</p>
        </div>

        <form method="POST" action="{{ route('login.proses') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" class="form-control @error('email') is-invalid @enderror" required autofocus>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input id="password" name="password" type="password" autocomplete="current-password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-check mb-4">
                <input id="remember" name="remember" type="checkbox" value="1" class="form-check-input" @checked(old('remember'))>
                <label for="remember" class="form-check-label">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-sg w-100">Masuk</button>
        </form>
    </div>
</div>
@endsection
