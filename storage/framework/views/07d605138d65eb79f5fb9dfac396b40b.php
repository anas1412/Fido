<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-panel rounded-lg bg-gray-50 p-4 ring-1 ring-inset ring-gray-950/5 dark:bg-white/5 dark:ring-white/10'])); ?>

>
    <?php if (isset($component)) { $__componentOriginalb4a47f3f1d204ce572aba77586ad2030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4a47f3f1d204ce572aba77586ad2030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.columns.layout','data' => ['components' => $getComponents(),'record' => $getRecord(),'recordKey' => $recordKey]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::columns.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['components' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getComponents()),'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getRecord()),'record-key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordKey)]); ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\layout\panel.blade.php ENDPATH**/ ?>