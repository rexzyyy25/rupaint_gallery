@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-5 flex-wrap">
        <div>
            <p class="eyebrow">KOLEKSI RUPAINT</p>
            <h1>Gallery</h1>
            <p class="text-muted mb-0">Jelajahi karya-karya orisinal para pelukis.</p>
        </div>
        <form class="gallery-search" role="search" action="{{ route('gallery.index') }}" method="GET">
            <label for="gallery-search-input" class="visually-hidden">Cari karya</label>
            <input id="gallery-search-input" name="q" value="{{ request('q') }}" class="form-control rounded-pill" placeholder="Ketik untuk mencari karya..." aria-label="Cari karya">
            <span id="gallery-search-status" class="visually-hidden" aria-live="polite"></span>
        </form>
    </div>

    <div id="gallery-results" aria-live="polite">
        @include('gallery._results')
    </div>
</div>
@endsection
