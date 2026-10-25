<?php if($featuredPainting): ?>
    <section class="exhibition-feature mb-5" aria-labelledby="exhibition-heading">
        <div class="text-center mb-4">
            <p class="eyebrow mb-2">PAMERAN RUPAINT</p>
            <h2 id="exhibition-heading">
                Karya yang sedang dipamerkan
                <span class="exhibition-live-dot" role="status" aria-label="Pameran sedang berlangsung"></span>
            </h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-9 col-lg-6">
                <a href="<?php echo e(route('gallery.show', $featuredPainting)); ?>" class="card art-card exhibition-art-card h-100 text-decoration-none">
                    <?php if($featuredPainting->image): ?>
                        <img class="art-placeholder art-image" src="<?php echo e(asset('storage/'.$featuredPainting->image)); ?>" alt="<?php echo e($featuredPainting->title); ?>">
                    <?php else: ?>
                        <div class="art-placeholder" style="--art-hue: <?php echo e(($featuredPainting->id * 41) % 360); ?>"><?php echo e(strtoupper(substr($featuredPainting->title, 0, 1))); ?></div>
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <small class="text-muted"><?php echo e($featuredPainting->category?->name); ?></small>
                        <h3 class="mt-1"><?php echo e($featuredPainting->title); ?></h3>
                        <p class="small text-muted"><?php echo e(Str::limit($featuredPainting->description, 120)); ?></p>
                        <strong>Karya oleh <?php echo e($featuredPainting->painter->name); ?></strong>
                    </div>
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if($paintings->isNotEmpty()): ?>
<div class="row g-4">
    <?php $__currentLoopData = $paintings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $painting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-lg-4">
            <a href="<?php echo e(route('gallery.show', $painting)); ?>" class="card art-card h-100 text-decoration-none">
                <?php if($painting->image): ?>
                    <img class="art-placeholder art-image" src="<?php echo e(asset('storage/'.$painting->image)); ?>" alt="<?php echo e($painting->title); ?>">
                <?php else: ?>
                    <div class="art-placeholder" style="--art-hue: <?php echo e(($painting->id * 41) % 360); ?>"><?php echo e(strtoupper(substr($painting->title, 0, 1))); ?></div>
                <?php endif; ?>
                <div class="card-body">
                    <small class="text-muted"><?php echo e($painting->category?->name); ?></small>
                    <h4><?php echo e($painting->title); ?></h4>
                    <p class="small text-muted"><?php echo e(Str::limit($painting->description, 90)); ?></p>
                    <strong>Karya oleh <?php echo e($painting->painter->name); ?></strong>
                </div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php elseif(!$featuredPainting): ?>
    <div class="alert alert-light">Tidak ada karya yang cocok dengan pencarianmu.</div>
<?php endif; ?>
<?php /**PATH C:\Laravel\rupaint-gallery\resources\views/gallery/_results.blade.php ENDPATH**/ ?>