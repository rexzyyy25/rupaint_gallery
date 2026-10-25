@extends('layouts.app')

@section('content')
<main class="container py-5">
    <p class="eyebrow">AKUN SAYA</p>
    <h1>Riwayat Pesanan</h1>
    <p class="text-muted">Daftar pesanan yang tercatat pada akun {{ auth()->user()->email }}.</p>

    <div class="row g-3 mt-3">
        @forelse($orders as $order)
            <div class="col-12">
                <article class="card order-card">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <p class="eyebrow mb-1">{{ $order->order_code }}</p>
                            <h2 class="h5 mb-1">{{ $order->customer_name }}</h2>
                            <p class="text-muted small mb-2">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
                            <span class="badge status-badge">{{ ucfirst($order->status) }}</span>
                            @foreach($order->customRequests as $customRequest)
                                <span class="small text-muted ms-2">{{ $customRequest->canvas_size }}</span>
                            @endforeach
                        </div>
                        <a class="btn btn-outline-light rounded-pill px-4" href="{{ route('orders.show', $order) }}">Lihat detail</a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <h2 class="h5">Belum ada pesanan</h2>
                    <p class="text-muted mb-3">Pesanan yang dibuat melalui formulir akan muncul di sini.</p>
                    <a class="btn btn-primary rounded-pill px-4" href="{{ route('orders.create') }}">Buat pesanan custom</a>
                </div>
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</main>
@endsection
