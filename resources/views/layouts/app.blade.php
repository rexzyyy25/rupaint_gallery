<!doctype html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="{{ asset('images/rupaint.jpg') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Rupaint Art Gallery' }}</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="{{ asset('css/style.css') }}"
        rel="stylesheet"
    >
</head>

<body class="@yield('body-class')">

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg border-bottom sticky-top gallery-nav">
        <div class="container">

            <a
                class="navbar-brand fw-bold brand"
                href="{{ auth()->check() ? route('home') : route('login') }}"
            >
                Rupaint<span>Art Gallery</span>
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#nav"
            >
                ☰
            </button>

            <div
                class="collapse navbar-collapse"
                id="nav"
            >
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('gallery.index') }}">Gallery</a></li>
                        <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="{{ route('orders.create') }}">Pesan Sekarang</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('painter.certificate') }}">Sertifikat Pelukis</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
                        @if (auth()->user()->is_admin)
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.certificate.edit') }}">Kelola Sertifikat</a></li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">Keluar</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="{{ route('register') }}">Daftar</a></li>
                    @endauth

                </ul>
            </div>

        </div>
    </nav>


    {{-- Success Message --}}
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif


    {{-- Error Message --}}
    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- Halaman --}}
    @yield('content')


    {{-- Footer --}}
    <footer class="py-5 mt-5">
        <div class="container d-flex justify-content-between flex-wrap gap-3">

            <div>
                <strong>Rupaint Art Gallery</strong>

                <div class="text-muted">
                    Karya yang punya cerita.
                </div>
            </div>
        </div>
    </footer>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>