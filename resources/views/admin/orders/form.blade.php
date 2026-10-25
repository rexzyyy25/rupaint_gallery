@extends('layouts.app')

@section('content')
<main class="container py-5">
    <p class="eyebrow">DASHBOARD ADMIN</p>
    <h1>{{ $order->exists ? 'Edit Pesanan '.$order->order_code : 'Tambah Pesanan' }}</h1>
    <form class="card admin-form p-4 mt-4" method="POST" action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}">
        @csrf
        @if($order->exists)
            @method('PUT')
        @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="user_id">Akun pemesan *</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="">Pilih pengguna</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected((string) old('user_id', $order->user_id) === (string) $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="status">Status pesanan *</label>
                <select class="form-select" id="status" name="status" required>
                    @foreach(['pending','confirmed','processing','ready','completed','cancelled'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $order->status ?: 'pending') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="customer_name">Nama pelanggan *</label>
                <input class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" maxlength="100" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="customer_phone">Nomor telepon *</label>
                <input class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" maxlength="30" required>
            </div>
            <div class="col-12">
                <label class="form-label" for="customer_address">Alamat</label>
                <textarea class="form-control" id="customer_address" name="customer_address" rows="2" maxlength="1000">{{ old('customer_address', $order->customer_address) }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="canvas_size">Ukuran kanvas</label>
                <select class="form-select" id="canvas_size" name="canvas_size">
                    <option value="">Tidak ditentukan</option>
                    @foreach(['20x30 cm','30x40 cm','40x50 cm','50x60 cm','50x70 cm','60x90 cm'] as $size)
                        <option value="{{ $size }}" @selected(old('canvas_size', $customRequest->canvas_size) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="description">Detail permintaan custom</label>
                <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000">{{ old('description', $customRequest->description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label" for="notes">Catatan</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" maxlength="2000">{{ old('notes', $order->notes) }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary rounded-pill px-4">Simpan pesanan</button>
            <a class="btn btn-outline-light rounded-pill px-4" href="{{ route('admin.orders') }}">Batal</a>
        </div>
    </form>
</main>
@endsection
