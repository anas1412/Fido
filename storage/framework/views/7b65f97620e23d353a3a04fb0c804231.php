<?php
    $canWrap = $canWrap();

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
                'fi-ta-color flex gap-1.5',
                'flex-wrap' => $canWrap,
                'px-3 py-4' => ! $isInline(),
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
                    x-on:click="
                        window.navigator.clipboard.writeText(<?php echo \Illuminate\Support\Js::from($copyableState)->toHtml() ?>)
                        $tooltip(<?php echo \Illuminate\Support\Js::from($copyMessage)->toHtml() ?>, {
                            theme: $store.theme,
                            timeout: <?php echo \Illuminate\Support\Js::from($copyMessageDuration)->toHtml() ?>,
                        })
                    "
                <?php endif; ?>
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'fi-ta-color-item h-6 w-6 rounded-md',
                    'cursor-pointer' => $itemIsCopyable,
                ]); ?>"
                style="<?php echo \Illuminate\Support\Arr::toCssStyles([
                    'background-color: ' . e($state) => $state,
                ]) ?>"
            ></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php elseif(($placeholder = $getPlaceholder()) !== null): ?>
        <?php if (isset($component)) { $__componentOriginal2078c004f342b84f8f2b0f2ab3478754 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2078c004f342b84f8f2b0f2ab3478754 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.columns.placeholder','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::columns.placeholder'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php echo e($placeholder); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2078c004f342b84f8f2b0f2ab3478754)): ?>
<?php $attributes = $__attributesOriginal2078c004f342b84f8f2b0f2ab3478754; ?>
<?php unset($__attributesOriginal2078c004f342b84f8f2b0f2ab3478754); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2078c004f342b84f8f2b0f2ab3478754)): ?>
<?php $component = $__componentOriginal2078c004f342b84f8f2b0f2ab3478754; ?>
<?php unset($__componentOriginal2078c004f342b84f8f2b0f2ab3478754); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\color-column.blade.php ENDPATH**/ ?>