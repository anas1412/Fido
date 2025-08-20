<?php
    use Filament\Support\Enums\Alignment;
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
        $alignment = $getAlignment();
        $state = $getState();

        if ($state instanceof \Illuminate\Support\Collection) {
            $state = $state->all();
        }

        $state = \Illuminate\Support\Arr::wrap($state);

        $limit = $getLimit();
        $limitedState = array_slice($state, 0, $limit);
        $isCircular = $isCircular();
        $isSquare = $isSquare();
        $isStacked = $isStacked();
        $overlap = $isStacked ? ($getOverlap() ?? 2) : null;
        $ring = $isStacked ? ($getRing() ?? 2) : null;
        $height = $getHeight() ?? ($isStacked ? '2.5rem' : '8rem');
        $width = $getWidth() ?? (($isCircular || $isSquare) ? $height : null);

        $stateCount = count($state);
        $limitedStateCount = count($limitedState);

        $defaultImageUrl = $getDefaultImageUrl();

        if (! $alignment instanceof Alignment) {
            $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
        }

        if ((! $limitedStateCount) && filled($defaultImageUrl)) {
            $limitedState = [null];

            $limitedStateCount = 1;
        }

        $ringClasses = \Illuminate\Support\Arr::toCssClasses([
            'ring-white dark:ring-gray-900',
            match ($ring) {
                0 => null,
                1 => 'ring-1',
                2 => 'ring-2',
                3 => 'ring',
                4 => 'ring-4',
                default => $ring,
            },
        ]);

        $hasLimitedRemainingText = $hasLimitedRemainingText() && ($limitedStateCount < $stateCount);
        $isLimitedRemainingTextSeparate = $isLimitedRemainingTextSeparate();

        $limitedRemainingTextSizeClasses = match ($getLimitedRemainingTextSize()) {
            'xs' => 'text-xs',
            'sm', null => 'text-sm',
            'base', 'md' => 'text-base',
            'lg' => 'text-lg',
            default => $size,
        };
    ?>

    <div
        <?php echo e($attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-in-image flex items-center gap-x-2.5',
                    match ($alignment) {
                        Alignment::Start, Alignment::Left => 'justify-start',
                        Alignment::Center => 'justify-center',
                        Alignment::End, Alignment::Right => 'justify-end',
                        Alignment::Between, Alignment::Justify => 'justify-between',
                        default => $alignment,
                    },
                ])); ?>

    >
        <?php if($limitedStateCount): ?>
            <div
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'flex flex-wrap',
                    match ($overlap) {
                        0 => null,
                        1 => '-space-x-1',
                        2 => '-space-x-2',
                        3 => '-space-x-3',
                        4 => '-space-x-4',
                        5 => '-space-x-5',
                        6 => '-space-x-6',
                        7 => '-space-x-7',
                        8 => '-space-x-8',
                        default => 'gap-1.5',
                    },
                ]); ?>"
            >
                <?php $__currentLoopData = $limitedState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img
                        src="<?php echo e(filled($stateItem) ? ($getImageUrl($stateItem) ?? $defaultImageUrl) : $defaultImageUrl); ?>"
                        <?php echo e($getExtraImgAttributeBag()
                                ->class([
                                    'max-w-none object-cover object-center',
                                    'rounded-full' => $isCircular,
                                    $ringClasses,
                                ])
                                ->style([
                                    "height: {$height}" => $height,
                                    "width: {$width}" => $width,
                                ])); ?>

                    />
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($hasLimitedRemainingText && (! $isLimitedRemainingTextSeparate) && $isCircular): ?>
                    <div
                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                            'flex items-center justify-center bg-gray-100 font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400',
                            'rounded-full' => $isCircular,
                            $limitedRemainingTextSizeClasses,
                            $ringClasses,
                        ]); ?>"
                        style="<?php echo \Illuminate\Support\Arr::toCssStyles([
                            "height: {$height}" => $height,
                            "width: {$width}" => $width,
                        ]) ?>"
                    >
                        <span class="-ms-0.5">
                            +<?php echo e($stateCount - $limitedStateCount); ?>

                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if($hasLimitedRemainingText && ($isLimitedRemainingTextSeparate || (! $isCircular))): ?>
                <div
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'font-medium text-gray-500 dark:text-gray-400',
                        $limitedRemainingTextSizeClasses,
                    ]); ?>"
                >
                    +<?php echo e($stateCount - $limitedStateCount); ?>

                </div>
            <?php endif; ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\infolists\resources\views\components\image-entry.blade.php ENDPATH**/ ?>