<?php
    use Filament\Support\Enums\Alignment;

    $alignment = $getAlignment() ?? Alignment::Start;

    if (! $alignment instanceof Alignment) {
        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
    }
?>

<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'flex flex-col',
                match ($alignment) {
                    Alignment::Start, Alignment::Left => 'items-start',
                    Alignment::Center => 'items-center',
                    Alignment::End, Alignment::Right => 'items-end',
                    Alignment::Justify, Alignment::Between => null,
                    default => $alignment,
                },
                match ($space = $getSpace()) {
                    1 => 'space-y-1',
                    2 => 'space-y-2',
                    3 => 'space-y-3',
                    default => $space,
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
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\layout\stack.blade.php ENDPATH**/ ?>