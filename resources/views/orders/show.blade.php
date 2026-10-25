@extends('layouts.app')

@section('content')
<main class="container py-5">
    <a class="text-link" href="{{ route('orders.index') }}">← Kembali ke riwayat pesanan</a>
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mt-4">
        <div>
            <p class="eyebrow mb-1">{{ $order->order_code }}</p>
            <h1>Detail Pesanan</h1>
            <p class="text-muted">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <span class="badge status-badge fs-6">{{ ucfirst($order->status) }}</span>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            <section class="card order-card h-100">
                <div class="card-body p-4">
                    <h2 class="h5">Item pesanan</h2>
                    @forelse($order->items as $item)
                        <div class="order-line">
                            <strong>{{ $item->painting?->title ?? 'Karya tidak tersedia' }}</strong>
                            <span>Jumlah: {{ $item->quantity }}</span>
                        </div>
                    @empty
                        @if($order->customRequests->isEmpty())
                            <p class="text-muted">Tidak ada rincian item.</p>
                        @endif
                    @endforelse

                    @foreach($order->customRequests as $customRequest)
                        <div class="order-line align-items-start">
                            <div>
                                <strong>Permintaan lukisan custom</strong>
                                <p class="text-muted mb-1">{{ $customRequest->description }}</p>
                                @if($customRequest->reference_image)
                                    <a class="text-link" href="{{ asset('storage/'.$customRequest->reference_image) }}" target="_blank" rel="noopener">Lihat gambar referensi</a>
                                @endif
                            </div>
                            <span>{{ $customRequest->canvas_size ?: 'Ukuran belum ditentukan' }}</span>
                        </div>
                    @endforeach
                    @if($order->notes)
                        <div class="mt-4"><strong>Catatan</strong><p class="text-muted mb-0">{{ $order->notes }}</p></div>
                    @endif
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="card order-card h-100">
                <div class="card-body p-4">
                    <h2 class="h5">Informasi pelanggan</h2>
                    <dl class="order-details">
                        <dt>Nama</dt><dd>{{ $order->customer_name }}</dd>
                        <dt>Telepon</dt><dd>{{ $order->customer_phone }}</dd>
                        <dt>Alamat</dt><dd>{{ $order->customer_address ?: 'Belum diisi' }}</dd>
                        <dt>Status pembayaran</dt><dd>{{ ucfirst($order->payment?->payment_status ?? 'Belum tersedia') }}</dd>
                    </dl>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
