<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-icon-count-summary grid gap-y-1.5 px-3 py-4'])); ?>

>
    <?php if(filled($label = $getLabel())): ?>
        <p class="text-sm font-medium text-gray-950 dark:text-white">
            <?php echo e($label); ?>

        </p>
    <?php endif; ?>

    <?php if($state = $getState()): ?>
        <div class="grid gap-y-1.5">
            <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color => $icons): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $color = json_decode($color);
                ?>

                <?php $__currentLoopData = $icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(filled($icon)): ?>
                        <div class="flex items-center justify-end gap-x-1.5">
                            <span
                                class="text-sm text-gray-500 dark:text-gray-400"
                            >
                                <?php echo e($count); ?>

                            </span>

                            <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $icon,'class' => \Illuminate\Support\Arr::toCssClasses([
                                    'fi-ta-icon-count-summary-icon h-6 w-6',
                                    match ($color) {
                                        'gray' => 'text-gray-400 dark:text-gray-500',
                                        default => 'fi-color-custom text-custom-500 dark:text-custom-400',
                                    },
                                    is_string($color) ? 'fi-color-' . $color : null,
                                ]),'style' => \Illuminate\Support\Arr::toCssStyles([
                                    \Filament\Support\get_color_css_variables(
                                        $color,
                                        shades: [400, 500],
                                        alias: 'tables::columns.summaries.icon-count.icon',
                                    ) => $color !== 'gray',
                                ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
                                    'fi-ta-icon-count-summary-icon h-6 w-6',
                                    match ($color) {
                                        'gray' => 'text-gray-400 dark:text-gray-500',
                                        default => 'fi-color-custom text-custom-500 dark:text-custom-400',
                                    },
                                    is_string($color) ? 'fi-color-' . $color : null,
                                ])),'style' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssStyles([
                                    \Filament\Support\get_color_css_variables(
                                        $color,
                                        shades: [400, 500],
                                        alias: 'tables::columns.summaries.icon-count.icon',
                                    ) => $color !== 'gray',
                                ]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\summaries\icon-count.blade.php ENDPATH**/ ?>