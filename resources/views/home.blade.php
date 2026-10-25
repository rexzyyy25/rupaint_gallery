@extends('layouts.app')

@section('body-class', 'home-page')

@section('content')
<main class="hero home-hero">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <p class="eyebrow">RUPAINT ART GALLERY · ART WITH A STORY</p>
                @auth
                    <p class="welcome-message">Selamat datang, {{ auth()->user()->name }} 👋</p>
                @endauth
                <h1>Warna untuk<br><em>ceritamu.</em></h1>
                <p class="lead text-muted mt-3">Temukan karya orisinal yang punya cerita, atau wujudkan lukisan yang selama ini kamu bayangkan.</p>
                <div class="d-flex gap-3 align-items-center mt-4 flex-wrap">
                    <a href="{{ route('gallery.index') }}" class="btn btn-primary rounded-pill px-4 py-2">Jelajahi Gallery <span aria-hidden="true">→</span></a>
                    <a href="{{ route('orders.create') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">Pesan lukisan custom</a>
                </div>
                <div class="home-highlights mt-5">
                    <div><strong>Karya orisinal</strong><span>Setiap lukisan punya kisah</span></div>
                    <div><strong>Dibuat sepenuh hati</strong><span>Oleh pelukis pilihan</span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-logo-card" role="img" aria-label="Logo Rupaint Art Gallery">
                    <span class="logo-orbit logo-orbit-one"></span>
                    <span class="logo-orbit logo-orbit-two"></span>
                    <span class="logo-spark logo-spark-one"></span>
                    <span class="logo-spark logo-spark-two"></span>
                    <div class="hero-logo-mark">R</div>
                    <p class="hero-logo-name">Rupaint<span>Art Gallery</span></p>
                    <p class="hero-logo-tagline">Karya yang punya cerita.</p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
