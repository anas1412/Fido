<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased">
        <?php if (isset($component)) { $__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.demo-banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('demo-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c)): ?>
<?php $attributes = $__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c; ?>
<?php unset($__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c)): ?>
<?php $component = $__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c; ?>
<?php unset($__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c); ?>
<?php endif; ?>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layout.navigation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2021877031-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
</html>
<?php /**PATH D:\Devs\Fido\resources\views\layouts\app.blade.php ENDPATH**/ ?>