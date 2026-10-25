@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container py-5">
        <div class="auth-card mx-auto">
            @php($isAdminLogin = $isAdminLogin ?? false)
            <p class="eyebrow">{{ $isAdminLogin ? 'AREA ADMINISTRATOR' : 'SELAMAT DATANG KEMBALI' }}</p>
            <h1>{{ $isAdminLogin ? 'Login sebagai Admin' : 'Masuk ke Rupaint' }}</h1>
            <p class="text-muted">{{ $isAdminLogin ? 'Gunakan akun administrator untuk mengelola galeri.' : 'Masuk untuk memesan karya dan menyimpan pilihanmu.' }}</p>
            <form action="{{ $isAdminLogin ? route('admin.login.store') : route('login.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata sandi</label>
                    <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" id="remember" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-dark rounded-pill w-100 py-2">Masuk</button>
            </form>
            @unless($isAdminLogin)
                <p class="text-center mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
                <p class="text-center mt-2 mb-0"><a href="{{ route('admin.login') }}">Login sebagai Admin</a></p>
            @else
                <p class="text-center mt-4 mb-0"><a href="{{ route('login') }}">Kembali ke login pelanggan</a></p>
            @endunless
        </div>
    </div>
</section>
@endsection
