<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'results',
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
    'label',
    'results',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<li
    <?php echo e($attributes->class(['fi-global-search-result-group'])); ?>

>
    <div
        class="sticky top-0 z-10 border-b border-gray-200 bg-gray-50 dark:border-white/10 dark:bg-gray-900"
    >
        <h3
            class="px-4 py-2 text-sm font-semibold capitalize text-gray-950 dark:bg-white/5 dark:text-white"
        >
            <?php echo e($label); ?>

        </h3>
    </div>

    <ul class="divide-y divide-gray-200 dark:divide-white/10">
        <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal778cd2be112571395dc0ac2afc159ec1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal778cd2be112571395dc0ac2afc159ec1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.global-search.result','data' => ['actions' => $result->actions,'details' => $result->details,'title' => $result->title,'url' => $result->url]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::global-search.result'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result->actions),'details' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result->details),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result->title),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($result->url)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal778cd2be112571395dc0ac2afc159ec1)): ?>
<?php $attributes = $__attributesOriginal778cd2be112571395dc0ac2afc159ec1; ?>
<?php unset($__attributesOriginal778cd2be112571395dc0ac2afc159ec1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal778cd2be112571395dc0ac2afc159ec1)): ?>
<?php $component = $__componentOriginal778cd2be112571395dc0ac2afc159ec1; ?>
<?php unset($__componentOriginal778cd2be112571395dc0ac2afc159ec1); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</li>
<?php /**PATH D:\Devs\Fido\vendor\filament\filament\resources\views\components\global-search\result-group.blade.php ENDPATH**/ ?>