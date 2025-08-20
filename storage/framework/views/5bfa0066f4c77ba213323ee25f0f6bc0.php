<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
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
    'results',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div
    x-data="{
        isOpen: false,

        open: function (event) {
            this.isOpen = true
        },

        close: function (event) {
            this.isOpen = false
        },
    }"
    x-init="$nextTick(() => open())"
    x-on:click.away="close()"
    x-on:keydown.escape.window="close()"
    x-on:keydown.up.prevent="$focus.wrap().previous()"
    x-on:keydown.down.prevent="$focus.wrap().next()"
    x-on:open-global-search-results.window="$nextTick(() => open())"
    x-show="isOpen"
    x-transition:enter-start="opacity-0"
    x-transition:leave-end="opacity-0"
    <?php echo e($attributes->class([
            'fi-global-search-results-ctn absolute inset-x-4 z-10 mt-2 max-h-96 overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:bg-gray-900 dark:ring-white/10 sm:inset-x-auto sm:end-0 sm:w-screen sm:max-w-sm',
            // This zero translation along the z-axis fixes a Safari bug
            // where the results container is incorrectly placed in the stacking context
            // due to the overflow-x value of clip on the topbar element.
            //
            // https://github.com/filamentphp/filament/issues/8215
            '[transform:translateZ(0)]',
        ])); ?>

>
    <?php if($results->getCategories()->isEmpty()): ?>
        <?php if (isset($component)) { $__componentOriginal5f91e8e55a301fec75d7ec79ec637319 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f91e8e55a301fec75d7ec79ec637319 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.global-search.no-results-message','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::global-search.no-results-message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f91e8e55a301fec75d7ec79ec637319)): ?>
<?php $attributes = $__attributesOriginal5f91e8e55a301fec75d7ec79ec637319; ?>
<?php unset($__attributesOriginal5f91e8e55a301fec75d7ec79ec637319); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f91e8e55a301fec75d7ec79ec637319)): ?>
<?php $component = $__componentOriginal5f91e8e55a301fec75d7ec79ec637319; ?>
<?php unset($__componentOriginal5f91e8e55a301fec75d7ec79ec637319); ?>
<?php endif; ?>
    <?php else: ?>
        <ul class="divide-y divide-gray-200 dark:divide-white/10">
            <?php $__currentLoopData = $results->getCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupedResults): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal931e4381c43960a92f84023b5c6c375a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal931e4381c43960a92f84023b5c6c375a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.global-search.result-group','data' => ['label' => $group,'results' => $groupedResults]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::global-search.result-group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group),'results' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($groupedResults)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal931e4381c43960a92f84023b5c6c375a)): ?>
<?php $attributes = $__attributesOriginal931e4381c43960a92f84023b5c6c375a; ?>
<?php unset($__attributesOriginal931e4381c43960a92f84023b5c6c375a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal931e4381c43960a92f84023b5c6c375a)): ?>
<?php $component = $__componentOriginal931e4381c43960a92f84023b5c6c375a; ?>
<?php unset($__componentOriginal931e4381c43960a92f84023b5c6c375a); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\filament\resources\views\components\global-search\results-container.blade.php ENDPATH**/ ?>