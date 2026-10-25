@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div><p class="eyebrow">DASHBOARD ADMIN</p><h1>Pesanan</h1></div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-light rounded-pill" href="{{ route('admin.paintings.index') }}">Manajemen Galeri</a>
            <a class="btn btn-primary rounded-pill" href="{{ route('admin.orders.create') }}">Tambah pesanan</a>
        </div>
    </div>
    <div class="table-responsive mt-4">
        <table class="table align-middle admin-table">
            <thead><tr><th>Kode</th><th>Pelanggan</th><th>Detail pesanan</th><th>Status</th><th>Update</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_code }}</strong></td>
                        <td>{{ $order->customer_name }}<br><small>{{ $order->customer_phone }}</small></td>
                        <td>
                            @foreach($order->items as $item)<div>{{ $item->painting->title }} × {{ $item->quantity }}</div>@endforeach
                            @foreach($order->customRequests as $customRequest)
                                <div><strong>Custom {{ $customRequest->canvas_size }}</strong><br>{{ $customRequest->description }}</div>
                            @endforeach
                        </td>
                        <td><span class="badge status-badge">{{ ucfirst($order->status) }}</span></td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.orders.edit', $order) }}">Detail / edit</a>
                                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" aria-label="Status pesanan {{ $order->order_code }}">
                                        @foreach(['pending','confirmed','processing','ready','completed','cancelled'] as $status)
                                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary btn-sm">Simpan status</button>
                                </form>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->order_code }} beserta rinciannya?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
