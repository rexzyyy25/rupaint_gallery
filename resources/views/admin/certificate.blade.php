@extends('layouts.app')

@section('content')
<main class="container py-5">
    <p class="eyebrow">DASHBOARD ADMIN</p>
    <h1>Kelola Sertifikat Pelukis</h1>
    <p class="text-muted">Unggah satu foto sertifikat dan isi keterangan berdasarkan informasi yang terverifikasi.</p>

    <form class="card admin-form p-4 mt-4" method="POST" action="{{ route('admin.certificate.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @if($painter->certificate_image)
            <div class="mb-4">
                <label class="form-label d-block">Foto sertifikat saat ini</label>
                <img class="certificate-admin-preview" src="{{ asset('storage/'.$painter->certificate_image) }}" alt="Foto sertifikat saat ini">
            </div>
        @endif
        <div class="mb-3">
            <label class="form-label" for="certificate_title">Nama sertifikat / penghargaan</label>
            <input class="form-control" id="certificate_title" name="certificate_title" value="{{ old('certificate_title', $painter->certificate_title) }}" maxlength="255">
        </div>
        <div class="mb-3">
            <label class="form-label" for="certificate_image">Foto sertifikat</label>
            <input class="form-control" id="certificate_image" type="file" name="certificate_image" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">JPG, PNG, atau WEBP; maksimal 5 MB. Mengunggah foto baru akan menggantikan foto lama.</div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="certificate_description">Deskripsi sertifikat</label>
            <textarea class="form-control" id="certificate_description" name="certificate_description" rows="5" maxlength="5000">{{ old('certificate_description', $painter->certificate_description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="exhibition_experience">Riwayat dan pengalaman pameran</label>
            <textarea class="form-control" id="exhibition_experience" name="exhibition_experience" rows="8" maxlength="10000" placeholder="Cantumkan nama pameran, penyelenggara, lokasi, tahun, dan peran pelukis.">{{ old('exhibition_experience', $painter->exhibition_experience) }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary rounded-pill px-4">Simpan</button>
            <a class="btn btn-outline-light rounded-pill px-4" href="{{ route('painter.certificate') }}">Lihat halaman publik</a>
        </div>
    </form>
</main>
@endsection
