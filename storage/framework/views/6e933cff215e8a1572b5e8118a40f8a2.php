<?php
    $id = $getId();
    $isContained = $getContainer()->getParentComponent()->isContained();

    $activeStepClasses = \Illuminate\Support\Arr::toCssClasses([
        'fi-active',
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $inactiveStepClasses = 'invisible absolute h-0 overflow-hidden p-0';
?>

<div
    x-bind:tabindex="$el.querySelector('[autofocus]') ? '-1' : '0'"
    x-bind:class="{
        <?php echo \Illuminate\Support\Js::from($activeStepClasses)->toHtml() ?>: step === <?php echo \Illuminate\Support\Js::from($id)->toHtml() ?>,
        <?php echo \Illuminate\Support\Js::from($inactiveStepClasses)->toHtml() ?>: step !== <?php echo \Illuminate\Support\Js::from($id)->toHtml() ?>,
    }"
    x-on:expand="
        if (! isStepAccessible(<?php echo \Illuminate\Support\Js::from($id)->toHtml() ?>)) {
            return
        }

        step = <?php echo \Illuminate\Support\Js::from($id)->toHtml() ?>
    "
    x-ref="step-<?php echo e($id); ?>"
    <?php echo e($attributes
            ->merge([
                'aria-labelledby' => $id,
                'id' => $id,
                'role' => 'tabpanel',
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-fo-wizard-step outline-none'])); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\wizard\step.blade.php ENDPATH**/ ?>