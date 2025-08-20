<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'actions' => [],
    'details' => [],
    'title',
    'url',
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
    'actions' => [],
    'details' => [],
    'title',
    'url',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<li
    <?php echo e($attributes->class(['fi-global-search-result scroll-mt-9 transition duration-75 focus-within:bg-gray-50 hover:bg-gray-50 dark:focus-within:bg-white/5 dark:hover:bg-white/5'])); ?>

>
    <a
        <?php echo e(\Filament\Support\generate_href_html($url)); ?>

        x-on:click="close()"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fi-global-search-result-link block outline-none',
            'pe-4 ps-4 pt-4' => $actions,
            'p-4' => ! $actions,
        ]); ?>"
    >
        <h4 class="text-sm font-medium text-gray-950 dark:text-white">
            <?php echo e($title); ?>

        </h4>

        <?php if($details): ?>
            <dl class="mt-1">
                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <?php if($isAssoc ??= \Illuminate\Support\Arr::isAssoc($details)): ?>
                            <dt class="inline font-medium"><?php echo e($label); ?>:</dt>
                        <?php endif; ?>

                        <dd class="inline"><?php echo e($value); ?></dd>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </dl>
        <?php endif; ?>
    </a>

    <?php if($actions): ?>
        <?php if (isset($component)) { $__componentOriginal06869628e057700d7c7d1210beefbe23 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06869628e057700d7c7d1210beefbe23 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.global-search.actions','data' => ['actions' => $actions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::global-search.actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actions)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06869628e057700d7c7d1210beefbe23)): ?>
<?php $attributes = $__attributesOriginal06869628e057700d7c7d1210beefbe23; ?>
<?php unset($__attributesOriginal06869628e057700d7c7d1210beefbe23); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06869628e057700d7c7d1210beefbe23)): ?>
<?php $component = $__componentOriginal06869628e057700d7c7d1210beefbe23; ?>
<?php unset($__componentOriginal06869628e057700d7c7d1210beefbe23); ?>
<?php endif; ?>
    <?php endif; ?>
</li>
<?php /**PATH D:\Devs\Fido\vendor\filament\filament\resources\views\components\global-search\result.blade.php ENDPATH**/ ?>