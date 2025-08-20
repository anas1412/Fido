<div
    x-cloak
    <?php echo e($attributes
            ->merge([
                'wire:key' => "{$this->getId()}.table.reorder.indicator",
            ], escape: false)
            ->class([
                'fi-ta-reorder-indicator flex gap-x-3 bg-gray-50 px-3 py-1.5 dark:bg-white/5 sm:px-6',
            ])); ?>

>
    <?php if (isset($component)) { $__componentOriginalbef7c2371a870b1887ec3741fe311a10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbef7c2371a870b1887ec3741fe311a10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.loading-indicator','data' => ['attributes' => 
            \Filament\Support\prepare_inherited_attributes(
                new \Illuminate\View\ComponentAttributeBag([
                    'wire:loading.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                    'wire:target' => 'reorderTable',
                ])
            )->class(['h-5 w-5 text-gray-400 dark:text-gray-500'])
        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::loading-indicator'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
            \Filament\Support\prepare_inherited_attributes(
                new \Illuminate\View\ComponentAttributeBag([
                    'wire:loading.delay.' . config('filament.livewire_loading_delay', 'default') => '',
                    'wire:target' => 'reorderTable',
                ])
            )->class(['h-5 w-5 text-gray-400 dark:text-gray-500'])
        )]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbef7c2371a870b1887ec3741fe311a10)): ?>
<?php $attributes = $__attributesOriginalbef7c2371a870b1887ec3741fe311a10; ?>
<?php unset($__attributesOriginalbef7c2371a870b1887ec3741fe311a10); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbef7c2371a870b1887ec3741fe311a10)): ?>
<?php $component = $__componentOriginalbef7c2371a870b1887ec3741fe311a10; ?>
<?php unset($__componentOriginalbef7c2371a870b1887ec3741fe311a10); ?>
<?php endif; ?>

    <span
        class="text-sm font-medium leading-6 text-gray-700 dark:text-gray-200"
    >
        <?php echo e(__('filament-tables::table.reorder_indicator')); ?>

    </span>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\components\reorder\indicator.blade.php ENDPATH**/ ?>