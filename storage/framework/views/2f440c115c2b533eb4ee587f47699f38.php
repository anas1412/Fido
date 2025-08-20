<div
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-values-summary grid gap-y-1 px-3 py-4'])); ?>

>
    <?php if(filled($label = $getLabel())): ?>
        <span class="text-sm font-medium text-gray-950 dark:text-white">
            <?php echo e($label); ?>

        </span>
    <?php endif; ?>

    <?php if($state = $getState()): ?>
        <ul
            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                'list-inside list-disc' => $isBulleted(),
            ]); ?>"
        >
            <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="text-sm text-gray-500 dark:text-gray-400">
                    <?php echo e($formatState($stateItem)); ?>

                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\summaries\values.blade.php ENDPATH**/ ?>