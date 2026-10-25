

<?php $__env->startSection('content'); ?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <p class="eyebrow">PESANAN LUKISAN CUSTOM</p>
            <h1>Pesan Disini</h1>
            <p class="text-muted">Ceritakan lukisan yang kamu inginkan, pilih ukuran kanvas, lalu lanjutkan melalui WhatsApp.</p>

            <form action="<?php echo e(route('orders.store')); ?>" method="POST" enctype="multipart/form-data" class="card checkout-card border-0 shadow-sm p-3 p-md-4 mt-4">
                <?php echo csrf_field(); ?>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h2 class="h5 mb-3">Ukuran kanvas</h2>
                        <div class="row row-cols-2 row-cols-md-3 g-3">
                            <?php $__currentLoopData = $canvasSizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col">
                                    <label class="canvas-option w-100" for="canvas-size-<?php echo e($index); ?>">
                                        <input
                                            class="form-check-input"
                                            id="canvas-size-<?php echo e($index); ?>"
                                            type="radio"
                                            name="canvas_size"
                                            value="<?php echo e($size['value']); ?>"
                                            <?php if(old('canvas_size', '40x50 cm') === $size['value']): echo 'checked'; endif; ?>
                                            required
                                        >
                                        <svg class="canvas-preview" viewBox="0 0 <?php echo e($size['width']); ?> <?php echo e($size['height']); ?>" role="img" aria-label="Pratinjau kanvas <?php echo e($size['value']); ?>">
                                            <rect x="1" y="1" width="<?php echo e($size['width'] - 2); ?>" height="<?php echo e($size['height'] - 2); ?>" rx="1.5" fill="hsl(<?php echo e(24 + $index * 43); ?> 52% 92%)" stroke="#5a3c35" stroke-width="1.5"/>
                                            <circle cx="<?php echo e($size['width'] * .68); ?>" cy="<?php echo e($size['height'] * .32); ?>" r="<?php echo e(min($size['width'], $size['height']) * .15); ?>" fill="hsl(<?php echo e(24 + $index * 43); ?> 83% 67%)"/>
                                            <path d="M2 <?php echo e($size['height'] * .78); ?> Q<?php echo e($size['width'] * .25); ?> <?php echo e($size['height'] * .43); ?> <?php echo e($size['width'] * .48); ?> <?php echo e($size['height'] * .72); ?> T<?php echo e($size['width'] - 2); ?> <?php echo e($size['height'] * .6); ?> V<?php echo e($size['height'] - 2); ?> H2Z" fill="hsl(<?php echo e(165 + $index * 23); ?> 48% 48%)"/>
                                        </svg>
                                        <strong><?php echo e($size['value']); ?></strong>
                                        <small>Preview ukuran</small>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <div class="col">
                                <label class="canvas-option w-100" for="canvas-size-custom">
                                    <input
                                        class="form-check-input"
                                        id="canvas-size-custom"
                                        type="radio"
                                        name="canvas_size"
                                        value="custom"
                                        <?php if(old('canvas_size') === 'custom'): echo 'checked'; endif; ?>
                                    >
                                    <svg class="canvas-preview" viewBox="0 0 90 90" role="img" aria-label="Ukuran custom">
                                        <rect x="4" y="4" width="82" height="82" rx="3" fill="#eef5ff" stroke="#5a3c35" stroke-width="1.5"/>
                                        <path d="M11 64 C24 34, 38 31, 53 46 S81 71, 84 48" fill="none" stroke="#4d7edc" stroke-width="5" stroke-linecap="round"/>
                                        <circle cx="61" cy="32" r="8" fill="#f7c27d"/>
                                    </svg>
                                    <strong>Custom</strong>
                                    <small>Ukuran sesuai kebutuhan</small>
                                </label>
                            </div>
                        </div>

                        <div id="custom-canvas-fields" class="mt-3 <?php echo e(old('canvas_size') === 'custom' ? '' : 'd-none'); ?>">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <label class="form-label" for="custom_canvas_width">Lebar (cm)</label>
                                    <input
                                        id="custom_canvas_width"
                                        name="custom_canvas_width"
                                        type="number"
                                        min="1"
                                        max="500"
                                        value="<?php echo e(old('custom_canvas_width')); ?>"
                                        class="form-control"
                                        placeholder="mis. 120"
                                    >
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="custom_canvas_height">Tinggi (cm)</label>
                                    <input
                                        id="custom_canvas_height"
                                        name="custom_canvas_height"
                                        type="number"
                                        min="1"
                                        max="500"
                                        value="<?php echo e(old('custom_canvas_height')); ?>"
                                        class="form-control"
                                        placeholder="mis. 150"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <h2 class="h5 mb-3">Detail pesanan</h2>
                        <div class="mb-3">
                            <label class="form-label" for="customer_name">Nama lengkap *</label>
                            <input id="customer_name" name="customer_name" value="<?php echo e(old('customer_name', auth()->user()->name)); ?>" class="form-control" autocomplete="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="customer_phone">Nomor WhatsApp kamu *</label>
                            <input id="customer_phone" name="customer_phone" value="<?php echo e(old('customer_phone')); ?>" class="form-control" autocomplete="tel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="customer_address">Alamat pengiriman</label>
                            <textarea id="customer_address" name="customer_address" class="form-control" rows="2" autocomplete="street-address"><?php echo e(old('customer_address')); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Lukisan seperti apa yang kamu inginkan? *</label>
                            <textarea id="description" name="description" class="form-control" rows="4" maxlength="2000" placeholder="Tema, warna, gaya, atau detail lain..." required><?php echo e(old('description')); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="reference_image">Foto referensi (opsional)</label>
                            <input id="reference_image" type="file" name="reference_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG, PNG, atau WEBP, maksimal 5 MB.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="notes">Catatan tambahan</label>
                            <textarea id="notes" name="notes" class="form-control" rows="2" maxlength="2000"><?php echo e(old('notes')); ?></textarea>
                        </div>
                        <button class="btn btn-dark rounded-pill px-4 py-2 w-100">Buat pesanan & lanjut ke WhatsApp</button>
                        <p class="small text-muted text-center mt-3 mb-0">WhatsApp akan terbuka dengan draf pesanan. Kamu perlu meninjau dan mengirimkannya sendiri.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customRadio = document.getElementById('canvas-size-custom');
        const customFields = document.getElementById('custom-canvas-fields');
        const widthInput = document.getElementById('custom_canvas_width');
        const heightInput = document.getElementById('custom_canvas_height');

        function syncCustomCanvasState() {
            const isCustom = customRadio && customRadio.checked;
            if (!customFields) return;

            customFields.classList.toggle('d-none', !isCustom);

            if (widthInput) {
                widthInput.toggleAttribute('required', isCustom);
            }

            if (heightInput) {
                heightInput.toggleAttribute('required', isCustom);
            }
        }

        document.querySelectorAll('input[name="canvas_size"]').forEach(function (input) {
            input.addEventListener('change', syncCustomCanvasState);
        });

        syncCustomCanvasState();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/orders/create.blade.php ENDPATH**/ ?>