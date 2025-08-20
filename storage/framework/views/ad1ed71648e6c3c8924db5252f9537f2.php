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
                    'fi-in-color flex flex-wrap gap-1.5',
                ])); ?>

    >
        <?php if(count($arrayState)): ?>
            <?php $__currentLoopData = $arrayState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $itemIsCopyable = $isCopyable($state);
                    $copyableState = $getCopyableState($state) ?? $state;
                    $copyMessage = $getCopyMessage($state);
                    $copyMessageDuration = $getCopyMessageDuration($state);
                ?>

                <div
                    <?php if($itemIsCopyable): ?>
                        x-data="{}"
                        x-on:click="
                            window.navigator.clipboard.writeText(<?php echo \Illuminate\Support\Js::from($copyableState)->toHtml() ?>)
                            $tooltip(<?php echo \Illuminate\Support\Js::from($copyMessage)->toHtml() ?>, {
                                theme: $store.theme,
                                timeout: <?php echo \Illuminate\Support\Js::from($copyMessageDuration)->toHtml() ?>,
                            })
                        "
                    <?php endif; ?>
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'fi-in-color-item h-6 w-6 rounded-md',
                        'cursor-pointer' => $itemIsCopyable,
                    ]); ?>"
                    style="<?php echo \Illuminate\Support\Arr::toCssStyles([
                        'background-color: ' . e($state) => $state,
                    ]) ?>"
                ></div>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\infolists\resources\views\components\color-entry.blade.php ENDPATH**/ ?>