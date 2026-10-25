

<?php $__env->startSection('content'); ?>
<main class="container py-5">
    <a class="text-link" href="<?php echo e(route('orders.index')); ?>">← Kembali ke riwayat pesanan</a>
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mt-4">
        <div>
            <p class="eyebrow mb-1"><?php echo e($order->order_code); ?></p>
            <h1>Detail Pesanan</h1>
            <p class="text-muted">Dibuat <?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
        </div>
        <span class="badge status-badge fs-6"><?php echo e(ucfirst($order->status)); ?></span>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            <section class="card order-card h-100">
                <div class="card-body p-4">
                    <h2 class="h5">Item pesanan</h2>
                    <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="order-line">
                            <strong><?php echo e($item->painting?->title ?? 'Karya tidak tersedia'); ?></strong>
                            <span>Jumlah: <?php echo e($item->quantity); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php if($order->customRequests->isEmpty()): ?>
                            <p class="text-muted">Tidak ada rincian item.</p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php $__currentLoopData = $order->customRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="order-line align-items-start">
                            <div>
                                <strong>Permintaan lukisan custom</strong>
                                <p class="text-muted mb-1"><?php echo e($customRequest->description); ?></p>
                                <?php if($customRequest->reference_image): ?>
                                    <a class="text-link" href="<?php echo e(asset('storage/'.$customRequest->reference_image)); ?>" target="_blank" rel="noopener">Lihat gambar referensi</a>
                                <?php endif; ?>
                            </div>
                            <span><?php echo e($customRequest->canvas_size ?: 'Ukuran belum ditentukan'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($order->notes): ?>
                        <div class="mt-4"><strong>Catatan</strong><p class="text-muted mb-0"><?php echo e($order->notes); ?></p></div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <div class="col-lg-5">
            <section class="card order-card h-100">
                <div class="card-body p-4">
                    <h2 class="h5">Informasi pelanggan</h2>
                    <dl class="order-details">
                        <dt>Nama</dt><dd><?php echo e($order->customer_name); ?></dd>
                        <dt>Telepon</dt><dd><?php echo e($order->customer_phone); ?></dd>
                        <dt>Alamat</dt><dd><?php echo e($order->customer_address ?: 'Belum diisi'); ?></dd>
                        <dt>Status pembayaran</dt><dd><?php echo e(ucfirst($order->payment?->payment_status ?? 'Belum tersedia')); ?></dd>
                    </dl>
                </div>
            </section>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/orders/show.blade.php ENDPATH**/ ?>