<?php
    use Filament\Support\Enums\VerticalAlignment;

    $verticalAlignment = $getVerticalAlignment();

    if (! $verticalAlignment instanceof VerticalAlignment) {
        $verticalAlignment = filled($verticalAlignment) ? (VerticalAlignment::tryFrom($verticalAlignment) ?? $verticalAlignment) : null;
    }
?>

<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-fo-split flex gap-6',
                match ($getFromBreakpoint()) {
                    'sm' => 'flex-col sm:flex-row ' . match ($verticalAlignment) {
                        VerticalAlignment::Center => 'sm:items-center',
                        VerticalAlignment::End => 'sm:items-end',
                        default => 'sm:items-start',
                    },
                    'md' => 'flex-col md:flex-row ' . match ($verticalAlignment) {
                        VerticalAlignment::Center => 'md:items-center',
                        VerticalAlignment::End => 'md:items-end',
                        default => 'md:items-start',
                    },
                    'lg' => 'flex-col lg:flex-row ' . match ($verticalAlignment) {
                        VerticalAlignment::Center => 'lg:items-center',
                        VerticalAlignment::End => 'lg:items-end',
                        default => 'lg:items-start',
                    },
                    'xl' => 'flex-col xl:flex-row ' . match ($verticalAlignment) {
                        VerticalAlignment::Center => 'xl:items-center',
                        VerticalAlignment::End => 'xl:items-end',
                        default => 'xl:items-start',
                    },
                    '2xl' => 'flex-col 2xl:flex-row ' . match ($verticalAlignment) {
                        VerticalAlignment::Center => '2xl:items-center',
                        VerticalAlignment::End => '2xl:items-end',
                        default => '2xl:items-start',
                    },
                    default => match ($verticalAlignment) {
                        VerticalAlignment::Center => 'items-center',
                        VerticalAlignment::End => 'items-end',
                        default => 'items-start',
                    },
                },
            ])); ?>

>
    <?php $__currentLoopData = $getChildComponentContainers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $container): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $container->getComponents(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'w-full flex-1' => $component->canGrow(),
                ]); ?>"
            >
                <?php echo e($component); ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\split.blade.php ENDPATH**/ ?>