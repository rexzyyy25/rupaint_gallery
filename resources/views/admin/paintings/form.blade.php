@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <p class="eyebrow">DASHBOARD ADMIN</p>
            <h1>{{ $painting->exists ? 'Edit karya' : 'Tambah karya' }}</h1>
            <form
                action="{{ $painting->exists ? route('admin.paintings.update', $painting) : route('admin.paintings.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="card border-0 shadow-sm p-4 mt-4"
            >
                @csrf
                @if($painting->exists) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label" for="title">Judul karya *</label>
                    <input class="form-control" id="title" name="title" value="{{ old('title', $painting->title) }}" required maxlength="255">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $painting->description) }}</textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="category_id">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Tanpa kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $painting->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="price">Harga (Rp) *</label>
                        <input class="form-control" id="price" type="number" name="price" min="0" step="1000" value="{{ old('price', $painting->price ?? 100000) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="size">Ukuran</label>
                        <input class="form-control" id="size" name="size" value="{{ old('size', $painting->size) }}" maxlength="100">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="material">Material</label>
                        <input class="form-control" id="material" name="material" value="{{ old('material', $painting->material) }}" maxlength="150">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="year">Tahun</label>
                        <input class="form-control" id="year" type="number" name="year" min="1000" max="{{ now()->year + 1 }}" value="{{ old('year', $painting->year) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="status">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            @foreach(['available' => 'Tersedia', 'sold' => 'Terjual', 'unavailable' => 'Tidak tersedia'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $painting->status ?? 'available') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="image">Foto karya</label>
                        <input class="form-control" id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
                        <div class="form-text">JPG, PNG, atau WEBP, maksimal 5 MB.</div>
                    </div>
                </div>
                @if($painting->image)
                    <img class="admin-preview mb-3" src="{{ asset('storage/'.$painting->image) }}" alt="Foto saat ini: {{ $painting->title }}">
                @endif
                <div class="d-flex gap-2">
                    <button class="btn btn-dark rounded-pill px-4">Simpan</button>
                    <a class="btn btn-outline-secondary rounded-pill px-4" href="{{ route('admin.paintings.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
