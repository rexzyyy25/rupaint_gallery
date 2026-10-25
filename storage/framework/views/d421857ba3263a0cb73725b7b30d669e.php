

<?php $__env->startSection('content'); ?>
<main class="container py-5">
    <a class="text-link" href="<?php echo e(route('gallery.index')); ?>">← Kembali ke galeri</a>
    <header class="text-center mb-5 mt-4">
        <p class="eyebrow">PERJALANAN BERKARYA</p>
        <h1>Sertifikat Pelukis</h1>
        <p class="text-muted mb-0"><?php echo e($painter->name); ?></p>
    </header>

    <section class="row g-4 align-items-center">
        <div class="col-lg-6">
            <?php if($painter->certificate_image): ?>
                <img class="certificate-image" src="<?php echo e(asset('storage/'.$painter->certificate_image)); ?>" alt="Sertifikat <?php echo e($painter->certificate_title ?: 'penghargaan pelukis'); ?>">
            <?php else: ?>
                <div class="certificate-image-placeholder" role="img" aria-label="Foto sertifikat belum diunggah">
                    <span>Foto sertifikat belum diunggah</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <p class="eyebrow">SERTIFIKAT PENGHARGAAN</p>
            <h2><?php echo e($painter->certificate_title ?: 'Informasi sertifikat belum tersedia'); ?></h2>
            <?php if($painter->certificate_description): ?>
                <div class="long-copy"><?php echo nl2br(e($painter->certificate_description)); ?></div>
            <?php else: ?>
                <p class="text-muted">Deskripsi sertifikat belum diisi. Admin dapat melengkapinya melalui menu pengelolaan sertifikat.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="exhibition-section mt-5 pt-5">
        <p class="eyebrow">PAMERAN SENI</p>
        <h2>Riwayat & Pengalaman Pameran</h2>
        <?php if($painter->exhibition_experience): ?>
            <div class="long-copy mt-3"><?php echo nl2br(e($painter->exhibition_experience)); ?></div>
        <?php else: ?>
            <p class="text-muted">Riwayat dan pengalaman pameran belum diisi. Admin dapat melengkapinya melalui menu pengelolaan sertifikat.</p>
        <?php endif; ?>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/painter/certificate.blade.php ENDPATH**/ ?>