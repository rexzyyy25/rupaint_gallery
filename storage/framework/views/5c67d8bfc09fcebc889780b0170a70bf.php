<!doctype html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="<?php echo e(asset('images/rupaint.jpg')); ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo e($title ?? 'Rupaint Art Gallery'); ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="<?php echo e(asset('css/style.css')); ?>"
        rel="stylesheet"
    >
</head>

<body class="<?php echo $__env->yieldContent('body-class'); ?>">

    
    <nav class="navbar navbar-expand-lg border-bottom sticky-top gallery-nav">
        <div class="container">

            <a
                class="navbar-brand fw-bold brand"
                href="<?php echo e(auth()->check() ? route('home') : route('login')); ?>"
            >
                Rupaint<span>Art Gallery</span>
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#nav"
            >
                ☰
            </button>

            <div
                class="collapse navbar-collapse"
                id="nav"
            >
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('gallery.index')); ?>">Gallery</a></li>
                        <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="<?php echo e(route('orders.create')); ?>">Pesan Sekarang</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('painter.certificate')); ?>">Sertifikat Pelukis</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('orders.index')); ?>">Riwayat Pesanan</a></li>
                        <?php if(auth()->user()->is_admin): ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">Dashboard Admin</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('admin.certificate.edit')); ?>">Kelola Sertifikat</a></li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-link nav-link" type="submit">Keluar</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Masuk</a></li>
                        <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="<?php echo e(route('register')); ?>">Daftar</a></li>
                    <?php endif; ?>

                </ul>
            </div>

        </div>
    </nav>


    
    <?php if(session('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>


    
    <?php if(session('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="container mt-3">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($message); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>


    
    <?php echo $__env->yieldContent('content'); ?>


    
    <footer class="py-5 mt-5">
        <div class="container d-flex justify-content-between flex-wrap gap-3">

            <div>
                <strong>Rupaint Art Gallery</strong>

                <div class="text-muted">
                    Karya yang punya cerita.
                </div>
            </div>
        </div>
    </footer>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

</body>

</html><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/layouts/app.blade.php ENDPATH**/ ?>