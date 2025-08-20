<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-text-summary grid gap-y-1 px-3 py-4'])); ?>

>
    <?php if(filled($label = $getLabel())): ?>
        <span class="text-sm font-medium text-gray-950 dark:text-white">
            <?php echo e($label); ?>

        </span>
    <?php endif; ?>

    <span class="text-sm text-gray-500 dark:text-gray-400">
        <?php echo e($formatState($getState())); ?>

    </span>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\summaries\text.blade.php ENDPATH**/ ?>