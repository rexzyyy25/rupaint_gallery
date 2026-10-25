

<?php $__env->startSection('content'); ?>
<section class="auth-shell">
    <div class="container py-5">
        <div class="auth-card mx-auto">
            <?php ($isAdminLogin = $isAdminLogin ?? false); ?>
            <p class="eyebrow"><?php echo e($isAdminLogin ? 'AREA ADMINISTRATOR' : 'SELAMAT DATANG KEMBALI'); ?></p>
            <h1><?php echo e($isAdminLogin ? 'Login sebagai Admin' : 'Masuk ke Rupaint'); ?></h1>
            <p class="text-muted"><?php echo e($isAdminLogin ? 'Gunakan akun administrator untuk mengelola galeri.' : 'Masuk untuk memesan karya dan menyimpan pilihanmu.'); ?></p>
            <form action="<?php echo e($isAdminLogin ? route('admin.login.store') : route('login.store')); ?>" method="POST" class="mt-4">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input class="form-control" id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="email">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">Kata sandi</label>
                    <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" id="remember" type="checkbox" name="remember" value="1" <?php if(old('remember')): echo 'checked'; endif; ?>>
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-dark rounded-pill w-100 py-2">Masuk</button>
            </form>
            <?php if (! ($isAdminLogin)): ?>
                <p class="text-center mt-4 mb-0">Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar di sini</a></p>
                <p class="text-center mt-2 mb-0"><a href="<?php echo e(route('admin.login')); ?>">Login sebagai Admin</a></p>
            <?php else: ?>
                <p class="text-center mt-4 mb-0"><a href="<?php echo e(route('login')); ?>">Kembali ke login pelanggan</a></p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\rupaint-gallery\resources\views/auth/login.blade.php ENDPATH**/ ?>