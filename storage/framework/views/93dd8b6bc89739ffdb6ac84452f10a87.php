<?php
    $id = $getId();
    $isContained = $getContainer()->getParentComponent()->isContained();

    $activeTabClasses = \Illuminate\Support\Arr::toCssClasses([
        'fi-active',
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $inactiveTabClasses = 'invisible absolute h-0 overflow-hidden p-0';
?>

<div
    x-bind:class="tab === <?php echo \Illuminate\Support\Js::from($id)->toHtml() ?> ? <?php echo \Illuminate\Support\Js::from($activeTabClasses)->toHtml() ?> : <?php echo \Illuminate\Support\Js::from($inactiveTabClasses)->toHtml() ?>"
    <?php echo e($attributes
            ->merge([
                'aria-labelledby' => $id,
                'id' => $id,
                'role' => 'tabpanel',
                'tabindex' => '0',
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-in-tabs-tab outline-none'])); ?>

>
    <?php echo e($getChildComponentContainer()); ?>

</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\infolists\resources\views\components\tabs\tab.blade.php ENDPATH**/ ?>