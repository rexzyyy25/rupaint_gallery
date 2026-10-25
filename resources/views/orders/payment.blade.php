@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="payment-card mx-auto">
        <p class="eyebrow">PEMBAYARAN MIDTRANS</p>
        <h1>Pesanan {{ $order->order_code }}</h1>
        <p class="text-muted">Total pembayaran <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>

        @if($order->payment?->payment_status === 'paid')
            <div class="alert alert-success">Pembayaran berhasil diterima. Pesananmu sudah dikonfirmasi.</div>
        @elseif($order->payment?->payment_status === 'failed')
            <div class="alert alert-danger">Pembayaran gagal atau kedaluwarsa. Siapkan ulang detail pesanan untuk membahas opsi pembayaran.</div>
        @else
            <div class="alert alert-info">Status pembayaran: {{ ucfirst($order->payment?->payment_status ?? 'pending') }}. Status akan diperbarui otomatis setelah pembayaran dikonfirmasi Midtrans.</div>
            @if($order->payment?->checkout_url && $order->payment?->payment_status === 'pending')
                <a class="btn btn-dark rounded-pill px-4" href="{{ $order->payment->checkout_url }}" target="_blank" rel="noopener">Bayar dengan {{ $order->payment->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }} →</a>
            @else
                <form action="{{ route('orders.payment.retry', $order) }}" method="POST">
                    @csrf
                    <button class="btn btn-dark rounded-pill px-4">Coba siapkan pembayaran lagi</button>
                </form>
            @endif
        @endif

        <div class="mt-4">
            <a href="{{ route('orders.whatsapp', $order) }}" class="btn btn-outline-dark rounded-pill px-4">Buka draf pesanan di WhatsApp</a>
        </div>
        <a href="{{ route('gallery.index') }}" class="d-inline-block mt-4 text-dark">Kembali ke Gallery</a>
    </div>
</div>
@endsection
