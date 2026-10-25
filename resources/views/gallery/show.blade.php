@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            @if($painting->image)
                <img class="detail-art art-image" src="{{ asset('storage/'.$painting->image) }}" alt="{{ $painting->title }}">
            @else
                <div class="detail-art" style="--art-hue: {{ ($painting->id * 41) % 360 }}">{{ strtoupper(substr($painting->title, 0, 1)) }}</div>
            @endif
        </div>
        <div class="col-lg-6">
            <p class="eyebrow">{{ $painting->category?->name }}</p>
            <h1>{{ $painting->title }}</h1>
            <p class="text-muted">Karya oleh {{ $painting->painter->name }}</p>
            <p class="mt-4">{{ $painting->description }}</p>
            <ul class="list-unstyled text-muted">
                <li>Ukuran: {{ $painting->size ?: '-' }}</li>
                <li>Material: {{ $painting->material ?: '-' }}</li>
                <li>Tahun: {{ $painting->year ?: '-' }}</li>
            </ul>
            <a class="btn btn-outline-light rounded-pill px-4 mt-2" href="{{ route('gallery.index') }}">Kembali melihat karya</a>
        </div>
    </div>
</div>
@endsection
