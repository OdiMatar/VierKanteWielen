<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'Rijschool Vierkante Wielen'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>?v=<?php echo e(filemtime(public_path('css/app.css'))); ?>">
</head>
<body class="bg-light app-shell">
    <nav class="navbar navbar-expand-lg sticky-top app-navbar">
        <div class="container">
            <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="<?php echo e(auth()->check() ? route('home') : route('landing')); ?>">
                <span class="app-brand-mark">VW</span>
                <span>Rijschool Vierkante Wielen</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Menu openen">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('home')); ?>">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('lesrijpakketten.index')); ?>">Lespakketten Overzicht</a></li>
                        <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('instructeurs.index')); ?>">Instructeurs</a></li>
                        <?php if(auth()->user()->canManagePayments()): ?>
                            <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('betalingen.index')); ?>">Betalingen</a></li>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdministrator()): ?>
                            <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('accounts.index')); ?>">Accounts</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link app-nav-link" href="<?php echo e(route('landing')); ?>">Home</a></li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <?php if(auth()->guard()->check()): ?>
                        <span class="badge app-user-badge"><?php echo e(auth()->user()->name); ?></span>
                        <form method="post" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-secondary btn-sm app-logout-btn">Uitloggen</button>
                        </form>
                    <?php else: ?>
                        <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('login')); ?>">Inloggen</a>
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route('register')); ?>">Registreren</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <?php if($errors->has('Betaling') || $errors->has('Voertuig')): ?>
            <div class="alert alert-danger"><?php echo e($errors->first('Betaling') ?: $errors->first('Voertuig')); ?></div>
        <?php endif; ?>

        <?php echo e($slot); ?>

    </main>

    <footer class="app-footer">
        <div class="container app-footer-inner">
            <div>
                <strong>Rijschool Vierkante Wielen</strong>
                <p class="mb-0">Veilig, duidelijk en met plezier op weg naar je rijbewijs.</p>
            </div>
            <div class="app-footer-meta">
                <span>KVK: 12345678</span>
                <span>info@vierkantewielen.nl</span>
                <span>+31 6 1234 5678</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
<?php /**PATH C:\projects2025\Autorijschool\resources\views/components/layout.blade.php ENDPATH**/ ?>