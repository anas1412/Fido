<?php
    $columns = $getGridColumns();
?>

<div <?php echo e($attributes->merge($getExtraAttributes(), escape: false)); ?>>
    <?php if (isset($component)) { $__componentOriginal30dbd75eb120a380110a2b340cd88f46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30dbd75eb120a380110a2b340cd88f46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.grid.index','data' => ['default' => $columns['default'] ?? 1,'sm' => $columns['sm'] ?? null,'md' => $columns['md'] ?? null,'lg' => $columns['lg'] ?? null,'xl' => $columns['xl'] ?? null,'twoXl' => $columns['2xl'] ?? null,'class' => \Illuminate\Support\Arr::toCssClasses([
            (($columns['default'] ?? 1) === 1) ? 'gap-1' : 'gap-3',
            ($columns['sm'] ?? null) ? (($columns['sm'] === 1) ? 'sm:gap-1' : 'sm:gap-3') : null,
            ($columns['md'] ?? null) ? (($columns['md'] === 1) ? 'md:gap-1' : 'md:gap-3') : null,
            ($columns['lg'] ?? null) ? (($columns['lg'] === 1) ? 'lg:gap-1' : 'lg:gap-3') : null,
            ($columns['xl'] ?? null) ? (($columns['xl'] === 1) ? 'xl:gap-1' : 'xl:gap-3') : null,
            ($columns['2xl'] ?? null) ? (($columns['2xl'] === 1) ? '2xl:gap-1' : '2xl:gap-3') : null,
        ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['default' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['default'] ?? 1),'sm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['sm'] ?? null),'md' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['md'] ?? null),'lg' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['lg'] ?? null),'xl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['xl'] ?? null),'two-xl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns['2xl'] ?? null),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
            (($columns['default'] ?? 1) === 1) ? 'gap-1' : 'gap-3',
            ($columns['sm'] ?? null) ? (($columns['sm'] === 1) ? 'sm:gap-1' : 'sm:gap-3') : null,
            ($columns['md'] ?? null) ? (($columns['md'] === 1) ? 'md:gap-1' : 'md:gap-3') : null,
            ($columns['lg'] ?? null) ? (($columns['lg'] === 1) ? 'lg:gap-1' : 'lg:gap-3') : null,
            ($columns['xl'] ?? null) ? (($columns['xl'] === 1) ? 'xl:gap-1' : 'xl:gap-3') : null,
            ($columns['2xl'] ?? null) ? (($columns['2xl'] === 1) ? '2xl:gap-1' : '2xl:gap-3') : null,
        ]))]); ?>
        <?php if (isset($component)) { $__componentOriginalb4a47f3f1d204ce572aba77586ad2030 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb4a47f3f1d204ce572aba77586ad2030 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.columns.layout','data' => ['components' => $getComponents(),'grid' => true,'record' => $getRecord(),'recordKey' => $recordKey]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::columns.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['components' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getComponents()),'grid' => true,'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getRecord()),'record-key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordKey)]); ?>
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
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30dbd75eb120a380110a2b340cd88f46)): ?>
<?php $attributes = $__attributesOriginal30dbd75eb120a380110a2b340cd88f46; ?>
<?php unset($__attributesOriginal30dbd75eb120a380110a2b340cd88f46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30dbd75eb120a380110a2b340cd88f46)): ?>
<?php $component = $__componentOriginal30dbd75eb120a380110a2b340cd88f46; ?>
<?php unset($__componentOriginal30dbd75eb120a380110a2b340cd88f46); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\layout\grid.blade.php ENDPATH**/ ?>