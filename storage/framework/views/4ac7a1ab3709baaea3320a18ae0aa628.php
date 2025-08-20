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
    <div
        <?php echo e($attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class(['fi-in-key-value w-full rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10'])); ?>

    >
        <table
            class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5"
        >
            <thead>
                <tr>
                    <th
                        scope="col"
                        class="px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                        <?php echo e($getKeyLabel()); ?>

                    </th>

                    <th
                        scope="col"
                        class="px-3 py-2 text-start text-sm font-medium text-gray-700 dark:text-gray-200"
                    >
                        <?php echo e($getValueLabel()); ?>

                    </th>
                </tr>
            </thead>

            <tbody
                class="divide-y divide-gray-200 font-mono text-base dark:divide-white/5 sm:text-sm sm:leading-6"
            >
                <?php $__empty_1 = true; $__currentLoopData = ($getState() ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr
                        class="divide-x divide-gray-200 dark:divide-white/5 rtl:divide-x-reverse"
                    >
                        <td class="w-1/2 px-3 py-1.5">
                            <?php echo e($key); ?>

                        </td>

                        <td class="w-1/2 px-3 py-1.5">
                            <?php echo e($value); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td
                            colspan="2"
                            class="px-3 py-2 text-center font-sans text-sm text-gray-400 dark:text-gray-500"
                        >
                            <?php echo e($getPlaceholder()); ?>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\infolists\resources\views\components\key-value-entry.blade.php ENDPATH**/ ?>