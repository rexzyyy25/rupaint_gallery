@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <p class="eyebrow">DASHBOARD ADMIN</p>
            <h1>Manajemen Galeri</h1>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-dark rounded-pill" href="{{ route('admin.orders') }}">Pesanan</a>
            <a class="btn btn-outline-light rounded-pill" href="{{ route('admin.certificate.edit') }}">Sertifikat Pelukis</a>
            <a class="btn btn-dark rounded-pill" href="{{ route('admin.paintings.create') }}">Tambah karya</a>
        </div>
    </div>

    <div class="table-responsive mt-4">
        <table class="table align-middle admin-table">
            <thead><tr><th>Karya</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($paintings as $painting)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($painting->image)
                                    <img class="admin-thumbnail" src="{{ asset('storage/'.$painting->image) }}" alt="">
                                @else
                                    <div class="admin-thumbnail art-placeholder" style="--art-hue: {{ ($painting->id * 41) % 360 }}">{{ strtoupper(substr($painting->title, 0, 1)) }}</div>
                                @endif
                                <strong>{{ $painting->title }}</strong>
                            </div>
                        </td>
                        <td>{{ $painting->category?->name ?? '-' }}</td>
                        <td>Rp {{ number_format($painting->price, 0, ',', '.') }}</td>
                        <td><span class="badge text-bg-light">{{ $painting->status }}</span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.paintings.edit', $painting) }}">Edit</a>
                                <form action="{{ route('admin.paintings.destroy', $painting) }}" method="POST" onsubmit="return confirm('Hapus karya ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Belum ada karya di galeri.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $paintings->links() }}
</div>
@endsection
