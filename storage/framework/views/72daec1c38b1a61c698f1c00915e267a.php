

<?php $__env->startSection('content'); ?>
<main class="container py-5">
    <p class="eyebrow">AKUN SAYA</p>
    <h1>Riwayat Pesanan</h1>
    <p class="text-muted">Daftar pesanan yang tercatat pada akun <?php echo e(auth()->user()->email); ?>.</p>

    <div class="row g-3 mt-3">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12">
                <article class="card order-card">
                    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <p class="eyebrow mb-1"><?php echo e($order->order_code); ?></p>
                            <h2 class="h5 mb-1"><?php echo e($order->customer_name); ?></h2>
                            <p class="text-muted small mb-2">Dibuat <?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                            <span class="badge status-badge"><?php echo e(ucfirst($order->status)); ?></span>
                            <?php $__currentLoopData = $order->customRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="small text-muted ms-2"><?php echo e($customRequest->canvas_size); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <a class="btn btn-outline-light rounded-pill px-4" href="<?php echo e(route('orders.show', $order)); ?>">Lihat detail</a>
                    </div>
                </article>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="empty-state">
                    <h2 class="h5">Belum ada pesanan</h2>
                    <p class="text-muted mb-3">Pesanan yang dibuat melalui formulir akan muncul di sini.</p>
                    <a class="btn btn-primary rounded-pill px-4" href="<?php echo e(route('orders.create')); ?>">Buat pesanan custom</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4"><?php echo e($orders->links()); ?></div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/orders/index.blade.php ENDPATH**/ ?>