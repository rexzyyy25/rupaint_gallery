@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container py-5">
        <div class="auth-card mx-auto">
            <p class="eyebrow">BERGABUNG DENGAN RUPAINT</p>
            <h1>Buat akun baru</h1>
            <p class="text-muted">Simpan detail pemesanan karya seni favoritmu.</p>
            <form action="{{ route('register.store') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input class="form-control" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata sandi (minimal 8 karakter)</label>
                    <input class="form-control" id="password" type="password" name="password" required autocomplete="new-password">
                    @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password_confirmation">Ulangi kata sandi</label>
                    <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>
                <button class="btn btn-dark rounded-pill w-100 py-2">Daftar</button>
            </form>
            <p class="text-center mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            <p class="text-center mt-2 mb-0"><a href="{{ route('admin.login') }}">Login sebagai Admin</a></p>
        </div>
    </div>
</section>
@endsection
