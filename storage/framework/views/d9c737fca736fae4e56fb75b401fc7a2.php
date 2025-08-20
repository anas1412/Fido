<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-ta-split flex',
                match ($getFromBreakpoint()) {
                    'sm' => 'flex-col gap-2 sm:flex-row sm:items-center sm:gap-3',
                    'md' => 'flex-col gap-2 md:flex-row md:items-center md:gap-3',
                    'lg' => 'flex-col gap-2 lg:flex-row lg:items-center lg:gap-3',
                    'xl' => 'flex-col gap-2 xl:flex-row xl:items-center xl:gap-3',
                    '2xl' => 'flex-col gap-2 2xl:flex-row 2xl:items-center 2xl:gap-3',
                    default => 'items-center gap-3',
                },
            ])); ?>

>
    <?php if (isset($component)) { $__componentOriginalb4a47f3f1d204ce572aba77586ad2030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4a47f3f1d204ce572aba77586ad2030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.columns.layout','data' => ['components' => $getComponents(),'record' => $getRecord(),'recordKey' => $recordKey,'rowLoop' => $getRowLoop()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::columns.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['components' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getComponents()),'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getRecord()),'record-key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordKey),'row-loop' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getRowLoop())]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb4a47f3f1d204ce572aba77586ad2030)): ?>
<?php $attributes = $__attributesOriginalb4a47f3f1d204ce572aba77586ad2030; ?>
<?php unset($__attributesOriginalb4a47f3f1d204ce572aba77586ad2030); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb4a47f3f1d204ce572aba77586ad2030)): ?>
<?php $component = $__componentOriginalb4a47f3f1d204ce572aba77586ad2030; ?>
<?php unset($__componentOriginalb4a47f3f1d204ce572aba77586ad2030); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\layout\split.blade.php ENDPATH**/ ?>