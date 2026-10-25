@extends('layouts.app')

@section('content')
<main class="container py-5">
    <a class="text-link" href="{{ route('gallery.index') }}">← Kembali ke galeri</a>
    <header class="text-center mb-5 mt-4">
        <p class="eyebrow">PERJALANAN BERKARYA</p>
        <h1>Sertifikat Pelukis</h1>
        <p class="text-muted mb-0">{{ $painter->name }}</p>
    </header>

    <section class="row g-4 align-items-center">
        <div class="col-lg-6">
            @if($painter->certificate_image)
                <img class="certificate-image" src="{{ asset('storage/'.$painter->certificate_image) }}" alt="Sertifikat {{ $painter->certificate_title ?: 'penghargaan pelukis' }}">
            @else
                <div class="certificate-image-placeholder" role="img" aria-label="Foto sertifikat belum diunggah">
                    <span>Foto sertifikat belum diunggah</span>
                </div>
            @endif
        </div>
        <div class="col-lg-6">
            <p class="eyebrow">SERTIFIKAT PENGHARGAAN</p>
            <h2>{{ $painter->certificate_title ?: 'Informasi sertifikat belum tersedia' }}</h2>
            @if($painter->certificate_description)
                <div class="long-copy">{!! nl2br(e($painter->certificate_description)) !!}</div>
            @else
                <p class="text-muted">Deskripsi sertifikat belum diisi. Admin dapat melengkapinya melalui menu pengelolaan sertifikat.</p>
            @endif
        </div>
    </section>

    <section class="exhibition-section mt-5 pt-5">
        <p class="eyebrow">PAMERAN SENI</p>
        <h2>Riwayat & Pengalaman Pameran</h2>
        @if($painter->exhibition_experience)
            <div class="long-copy mt-3">{!! nl2br(e($painter->exhibition_experience)) !!}</div>
        @else
            <p class="text-muted">Riwayat dan pengalaman pameran belum diisi. Admin dapat melengkapinya melalui menu pengelolaan sertifikat.</p>
        @endif
    </section>
</main>
@endsection
