<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-range-summary grid gap-y-1 px-3 py-4'])); ?>

>
    <?php
        $state = $formatState($getState());
        $from = $state[0] ?? null;
        $to = $state[1] ?? null;
    ?>

    <?php if(filled($label = $getLabel())): ?>
        <span class="text-sm font-medium text-gray-950 dark:text-white">
            <?php echo e($label); ?>

        </span>
    <?php endif; ?>

    <?php if(filled($from) || filled($to)): ?>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            <?php echo e($from); ?>


            <?php if(filled($from) && filled($to)): ?>
                -
            <?php endif; ?>

            <?php echo e($to); ?>

        </span>
    <?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\summaries\range.blade.php ENDPATH**/ ?>