<?php
    use Filament\Infolists\Components\IconEntry\IconEntrySize;
?>

<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $getEntryWrapperView()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entry' => $entry]); ?>
    <?php
        $arrayState = $getState();

        if ($arrayState instanceof \Illuminate\Support\Collection) {
            $arrayState = $arrayState->all();
        }

        $arrayState = \Illuminate\Support\Arr::wrap($arrayState);
    ?>

    <div
        <?php echo e($attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-in-icon flex flex-wrap gap-1.5',
                ])); ?>

    >
        <?php if(count($arrayState)): ?>
            <?php $__currentLoopData = $arrayState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($icon = $getIcon($state)): ?>
                    <?php
                        $color = $getColor($state) ?? 'gray';
                        $size = $getSize($state) ?? IconEntrySize::Large;
                    ?>

                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $icon,'class' => \Illuminate\Support\Arr::toCssClasses([
                            'fi-in-icon-item',
                            match ($size) {
                                IconEntrySize::ExtraSmall, 'xs' => 'fi-in-icon-item-size-xs h-3 w-3',
                                IconEntrySize::Small, 'sm' => 'fi-in-icon-item-size-sm h-4 w-4',
                                IconEntrySize::Medium, 'md' => 'fi-in-icon-item-size-md h-5 w-5',
                                IconEntrySize::Large, 'lg' => 'fi-in-icon-item-size-lg h-6 w-6',
                                IconEntrySize::ExtraLarge, 'xl' => 'fi-in-icon-item-size-xl h-7 w-7',
                                IconEntrySize::TwoExtraLarge, IconEntrySize::ExtraExtraLarge, '2xl' => 'fi-in-icon-item-size-2xl h-8 w-8',
                                default => $size,
                            },
                            match ($color) {
                                'gray' => 'text-gray-400 dark:text-gray-500',
                                default => 'fi-color-custom text-custom-500 dark:text-custom-400',
                            },
                            is_string($color) ? 'fi-color-' . $color : null,
                        ]),'style' => \Illuminate\Support\Arr::toCssStyles([
                            \Filament\Support\get_color_css_variables(
                                $color,
                                shades: [400, 500],
                                alias: 'infolists::components.icon-entry.item',
                            ) => $color !== 'gray',
                        ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
                            'fi-in-icon-item',
                            match ($size) {
                                IconEntrySize::ExtraSmall, 'xs' => 'fi-in-icon-item-size-xs h-3 w-3',
                                IconEntrySize::Small, 'sm' => 'fi-in-icon-item-size-sm h-4 w-4',
                                IconEntrySize::Medium, 'md' => 'fi-in-icon-item-size-md h-5 w-5',
                                IconEntrySize::Large, 'lg' => 'fi-in-icon-item-size-lg h-6 w-6',
                                IconEntrySize::ExtraLarge, 'xl' => 'fi-in-icon-item-size-xl h-7 w-7',
                                IconEntrySize::TwoExtraLarge, IconEntrySize::ExtraExtraLarge, '2xl' => 'fi-in-icon-item-size-2xl h-8 w-8',
                                default => $size,
                            },
                            match ($color) {
                                'gray' => 'text-gray-400 dark:text-gray-500',
                                default => 'fi-color-custom text-custom-500 dark:text-custom-400',
                            },
                            is_string($color) ? 'fi-color-' . $color : null,
                        ])),'style' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssStyles([
                            \Filament\Support\get_color_css_variables(
                                $color,
                                shades: [400, 500],
                                alias: 'infolists::components.icon-entry.item',
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
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php elseif(($placeholder = $getPlaceholder()) !== null): ?>
            <?php if (isset($component)) { $__componentOriginale647119d200eac5fcc6d6ed603b86297 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale647119d200eac5fcc6d6ed603b86297 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-infolists::components.entries.placeholder','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-infolists::entries.placeholder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php echo e($placeholder); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale647119d200eac5fcc6d6ed603b86297)): ?>
<?php $attributes = $__attributesOriginale647119d200eac5fcc6d6ed603b86297; ?>
<?php unset($__attributesOriginale647119d200eac5fcc6d6ed603b86297); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale647119d200eac5fcc6d6ed603b86297)): ?>
<?php $component = $__componentOriginale647119d200eac5fcc6d6ed603b86297; ?>
<?php unset($__componentOriginale647119d200eac5fcc6d6ed603b86297); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\infolists\resources\views\components\icon-entry.blade.php ENDPATH**/ ?>