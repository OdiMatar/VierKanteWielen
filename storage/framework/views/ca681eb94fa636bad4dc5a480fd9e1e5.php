<?php if (isset($component)) { $__componentOriginal23a33f287873b564aaf305a1526eada4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal23a33f287873b564aaf305a1526eada4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layout','data' => ['title' => 'Accounts overzicht']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Accounts overzicht']); ?>
    
    <section class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Accounts</h1>
            <p class="text-muted mb-0">Overzicht van alle geregistreerde accounts.</p>
        </div>
        <a class="btn btn-primary" href="<?php echo e(route('accounts.create')); ?>">Account aanmaken</a>
    </section>

    
    <?php if($accounts->count() === 1 && $accounts->first()->role === 'administrator'): ?>
        <div class="alert alert-info mb-3">Er zijn geen accounts behalve de adminaccount.</div>
    <?php endif; ?>

    
    <div class="data-table-wrap">
        <table class="table data-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>E-mailadres</th>
                    <th>Rol</th>
                    <th>Geregistreerd op</th>
                </tr>
            </thead>
            <tbody>
                
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="fw-bold text-dark"><?php echo e($account->name); ?></td>
                        <td><?php echo e($account->email); ?></td>
                        <td><span class="data-pill"><?php echo e(ucfirst($account->role)); ?></span></td>
                        <td><?php echo e(\Illuminate\Support\Carbon::parse($account->created_at)->format('d-m-Y H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $attributes = $__attributesOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__attributesOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal23a33f287873b564aaf305a1526eada4)): ?>
<?php $component = $__componentOriginal23a33f287873b564aaf305a1526eada4; ?>
<?php unset($__componentOriginal23a33f287873b564aaf305a1526eada4); ?>
<?php endif; ?>
<?php /**PATH C:\projects2025\Autorijschool\resources\views/accounts/index.blade.php ENDPATH**/ ?>