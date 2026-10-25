@if($featuredPainting)
    <section class="exhibition-feature mb-5" aria-labelledby="exhibition-heading">
        <div class="text-center mb-4">
            <p class="eyebrow mb-2">PAMERAN RUPAINT</p>
            <h2 id="exhibition-heading">
                Karya yang sedang dipamerkan
                <span class="exhibition-live-dot" role="status" aria-label="Pameran sedang berlangsung"></span>
            </h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-9 col-lg-6">
                <a href="{{ route('gallery.show', $featuredPainting) }}" class="card art-card exhibition-art-card h-100 text-decoration-none">
                    @if($featuredPainting->image)
                        <img class="art-placeholder art-image" src="{{ asset('storage/'.$featuredPainting->image) }}" alt="{{ $featuredPainting->title }}">
                    @else
                        <div class="art-placeholder" style="--art-hue: {{ ($featuredPainting->id * 41) % 360 }}">{{ strtoupper(substr($featuredPainting->title, 0, 1)) }}</div>
                    @endif
                    <div class="card-body text-center">
                        <small class="text-muted">{{ $featuredPainting->category?->name }}</small>
                        <h3 class="mt-1">{{ $featuredPainting->title }}</h3>
                        <p class="small text-muted">{{ Str::limit($featuredPainting->description, 120) }}</p>
                        <strong>Karya oleh {{ $featuredPainting->painter->name }}</strong>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endif

@if($paintings->isNotEmpty())
<div class="row g-4">
    @foreach($paintings as $painting)
        <div class="col-sm-6 col-lg-4">
            <a href="{{ route('gallery.show', $painting) }}" class="card art-card h-100 text-decoration-none">
                @if($painting->image)
                    <img class="art-placeholder art-image" src="{{ asset('storage/'.$painting->image) }}" alt="{{ $painting->title }}">
                @else
                    <div class="art-placeholder" style="--art-hue: {{ ($painting->id * 41) % 360 }}">{{ strtoupper(substr($painting->title, 0, 1)) }}</div>
                @endif
                <div class="card-body">
                    <small class="text-muted">{{ $painting->category?->name }}</small>
                    <h4>{{ $painting->title }}</h4>
                    <p class="small text-muted">{{ Str::limit($painting->description, 90) }}</p>
                    <strong>Karya oleh {{ $painting->painter->name }}</strong>
                </div>
            </a>
        </div>
    @endforeach
</div>
@elseif(!$featuredPainting)
    <div class="alert alert-light">Tidak ada karya yang cocok dengan pencarianmu.</div>
@endif
