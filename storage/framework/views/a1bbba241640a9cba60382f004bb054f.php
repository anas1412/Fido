<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'components',
    'record',
    'recordKey' => null,
    'rowLoop' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'components',
    'record',
    'recordKey' => null,
    'rowLoop' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $getHiddenClasses = function (Filament\Tables\Columns\Column | Filament\Tables\Columns\Layout\Component $layoutComponent): ?string {
        if ($breakpoint = $layoutComponent->getHiddenFrom()) {
            return match ($breakpoint) {
                'sm' => 'sm:hidden',
                'md' => 'md:hidden',
                'lg' => 'lg:hidden',
                'xl' => 'xl:hidden',
                '2xl' => '2xl:hidden',
            };
        }

        if ($breakpoint = $layoutComponent->getVisibleFrom()) {
            return match ($breakpoint) {
                'sm' => 'hidden sm:block',
                'md' => 'hidden md:block',
                'lg' => 'hidden lg:block',
                'xl' => 'hidden xl:block',
                '2xl' => 'hidden 2xl:block',
            };
        }

        return null;
    };
?>

<?php $__currentLoopData = $components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layoutComponent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $layoutComponent->record($record);
        $layoutComponent->rowLoop($rowLoop);

        $isColumn = $layoutComponent instanceof \Filament\Tables\Columns\Column;
    ?>

    <?php if($layoutComponent->isVisible()): ?>
        <?php if (isset($component)) { $__componentOriginal6f9d0ad23f77111c926012ad6ce09333 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f9d0ad23f77111c926012ad6ce09333 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.grid.column','data' => ['default' => $layoutComponent->getColumnSpan('default'),'sm' => $layoutComponent->getColumnSpan('sm'),'md' => $layoutComponent->getColumnSpan('md'),'lg' => $layoutComponent->getColumnSpan('lg'),'xl' => $layoutComponent->getColumnSpan('xl'),'twoXl' => $layoutComponent->getColumnSpan('2xl'),'defaultStart' => $layoutComponent->getColumnStart('default'),'smStart' => $layoutComponent->getColumnStart('sm'),'mdStart' => $layoutComponent->getColumnStart('md'),'lgStart' => $layoutComponent->getColumnStart('lg'),'xlStart' => $layoutComponent->getColumnStart('xl'),'twoXlStart' => $layoutComponent->getColumnStart('2xl'),'class' => \Illuminate\Support\Arr::toCssClasses([
                'flex-1 w-full' => $layoutComponent->canGrow(),
                $getHiddenClasses($layoutComponent),
            ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::grid.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['default' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('default')),'sm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('sm')),'md' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('md')),'lg' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('lg')),'xl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('xl')),'twoXl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnSpan('2xl')),'defaultStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('default')),'smStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('sm')),'mdStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('md')),'lgStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('lg')),'xlStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('xl')),'twoXlStart' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->getColumnStart('2xl')),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
                'flex-1 w-full' => $layoutComponent->canGrow(),
                $getHiddenClasses($layoutComponent),
            ]))]); ?>
            <?php if($isColumn): ?>
                <?php if (isset($component)) { $__componentOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.columns.column','data' => ['column' => $layoutComponent->inline(),'record' => $record,'recordKey' => $recordKey]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::columns.column'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['column' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($layoutComponent->inline()),'record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record),'record-key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordKey)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8)): ?>
<?php $attributes = $__attributesOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8; ?>
<?php unset($__attributesOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8)): ?>
<?php $component = $__componentOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8; ?>
<?php unset($__componentOriginal9e6c25ad176a3fd7bc1fa75b239c0fc8); ?>
<?php endif; ?>
            <?php else: ?>
                <?php echo e($layoutComponent->viewData(['recordKey' => $recordKey])); ?>

            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6f9d0ad23f77111c926012ad6ce09333)): ?>
<?php $attributes = $__attributesOriginal6f9d0ad23f77111c926012ad6ce09333; ?>
<?php unset($__attributesOriginal6f9d0ad23f77111c926012ad6ce09333); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6f9d0ad23f77111c926012ad6ce09333)): ?>
<?php $component = $__componentOriginal6f9d0ad23f77111c926012ad6ce09333; ?>
<?php unset($__componentOriginal6f9d0ad23f77111c926012ad6ce09333); ?>
<?php endif; ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\components\columns\layout.blade.php ENDPATH**/ ?>