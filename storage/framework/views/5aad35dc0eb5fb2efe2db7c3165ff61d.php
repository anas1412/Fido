<?php
    use Filament\Support\Facades\FilamentView;

    $debounce = $getLiveDebounce();
    $hasInlineLabel = $hasInlineLabel();
    $isAddable = $isAddable();
    $isDeletable = $isDeletable();
    $isDisabled = $isDisabled();
    $isReorderable = $isReorderable();
    $statePath = $getStatePath();
?>

<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $getFieldWrapperView()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['field' => $field,'has-inline-label' => $hasInlineLabel]); ?>
     <?php $__env->slot('label', null, ['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
            'sm:pt-1.5' => $hasInlineLabel,
        ]))]); ?> 
        <?php echo e($getLabel()); ?>

     <?php $__env->endSlot(); ?>

    <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['disabled' => $isDisabled,'valid' => ! $errors->has($statePath),'attributes' => 
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-key-value'])
        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled),'valid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $errors->has($statePath)),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-key-value'])
        )]); ?>
        <div
            <?php if(FilamentView::hasSpaMode()): ?>
                x-load="visible || event (ax-modal-opened)"
            <?php else: ?>
                x-load
            <?php endif; ?>
            x-load-src="<?php echo e(\Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('key-value', 'filament/forms')); ?>"
            wire:ignore
            x-data="keyValueFormComponent({
                        state: $wire.<?php echo e($applyStateBindingModifiers("\$entangle('{$statePath}')")); ?>,
                    })"
            <?php echo e($attributes
                    ->merge($getExtraAlpineAttributes(), escape: false)
                    ->class(['divide-y divide-gray-200 dark:divide-white/10'])); ?>

        >
            <table
                class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5"
            >
                <thead>
                    <tr>
                        <?php if($isReorderable && (! $isDisabled)): ?>
                            <th
                                scope="col"
                                x-show="rows.length"
                                class="w-9"
                            ></th>
                        <?php endif; ?>

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

                        <?php if($isDeletable && (! $isDisabled)): ?>
                            <th
                                scope="col"
                                x-show="rows.length"
                                class="w-9"
                            ></th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody
                    <?php if($isReorderable): ?>
                        x-on:end.stop="reorderRows($event)"
                        x-sortable
                        data-sortable-animation-duration="<?php echo e($getReorderAnimationDuration()); ?>"
                    <?php endif; ?>
                    class="divide-y divide-gray-200 dark:divide-white/5"
                >
                    <template
                        x-bind:key="index"
                        x-for="(row, index) in rows"
                    >
                        <tr
                            <?php if($isReorderable): ?>
                                x-bind:x-sortable-item="row.key"
                            <?php endif; ?>
                            class="divide-x divide-gray-200 dark:divide-white/5 rtl:divide-x-reverse"
                        >
                            <?php if($isReorderable && (! $isDisabled)): ?>
                                <td class="p-0.5">
                                    <div x-sortable-handle class="flex">
                                        <?php echo e($getAction('reorder')); ?>

                                    </div>
                                </td>
                            <?php endif; ?>

                            <td class="w-1/2 p-0">
                                <?php if (isset($component)) { $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.index','data' => ['disabled' => (! $canEditKeys()) || $isDisabled,'placeholder' => filled($placeholder = $getKeyPlaceholder()) ? $placeholder : null,'type' => 'text','xModel' => 'row.key','attributes' => 
                                        \Filament\Support\prepare_inherited_attributes(
                                            new \Illuminate\View\ComponentAttributeBag([
                                                'x-on:input.debounce.' . ($debounce ?? '500ms') => 'updateState',
                                            ])
                                        )
                                    ,'class' => 'font-mono']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((! $canEditKeys()) || $isDisabled),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filled($placeholder = $getKeyPlaceholder()) ? $placeholder : null),'type' => 'text','x-model' => 'row.key','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                                        \Filament\Support\prepare_inherited_attributes(
                                            new \Illuminate\View\ComponentAttributeBag([
                                                'x-on:input.debounce.' . ($debounce ?? '500ms') => 'updateState',
                                            ])
                                        )
                                    ),'class' => 'font-mono']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e)): ?>
<?php $attributes = $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e; ?>
<?php unset($__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e)): ?>
<?php $component = $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e; ?>
<?php unset($__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e); ?>
<?php endif; ?>
                            </td>

                            <td class="w-1/2 p-0">
                                <?php if (isset($component)) { $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.index','data' => ['disabled' => (! $canEditValues()) || $isDisabled,'placeholder' => filled($placeholder = $getValuePlaceholder()) ? $placeholder : null,'type' => 'text','xModel' => 'row.value','attributes' => 
                                        \Filament\Support\prepare_inherited_attributes(
                                            new \Illuminate\View\ComponentAttributeBag([
                                                'x-on:input.debounce.' . ($debounce ?? '500ms') => 'updateState',
                                            ])
                                        )
                                    ,'class' => 'font-mono']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute((! $canEditValues()) || $isDisabled),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filled($placeholder = $getValuePlaceholder()) ? $placeholder : null),'type' => 'text','x-model' => 'row.value','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                                        \Filament\Support\prepare_inherited_attributes(
                                            new \Illuminate\View\ComponentAttributeBag([
                                                'x-on:input.debounce.' . ($debounce ?? '500ms') => 'updateState',
                                            ])
                                        )
                                    ),'class' => 'font-mono']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e)): ?>
<?php $attributes = $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e; ?>
<?php unset($__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e)): ?>
<?php $component = $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e; ?>
<?php unset($__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e); ?>
<?php endif; ?>
                            </td>

                            <?php if($isDeletable && (! $isDisabled)): ?>
                                <td class="p-0.5">
                                    <div x-on:click="deleteRow(index)">
                                        <?php echo e($getAction('delete')); ?>

                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    </template>
                </tbody>
            </table>

            <?php if($isAddable && (! $isDisabled)): ?>
                <div class="flex justify-center px-3 py-2">
                    <span x-on:click="addRow" class="flex">
                        <?php echo e($getAction('add')); ?>

                    </span>
                </div>
            <?php endif; ?>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal505efd9768415fdb4543e8c564dad437)): ?>
<?php $attributes = $__attributesOriginal505efd9768415fdb4543e8c564dad437; ?>
<?php unset($__attributesOriginal505efd9768415fdb4543e8c564dad437); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal505efd9768415fdb4543e8c564dad437)): ?>
<?php $component = $__componentOriginal505efd9768415fdb4543e8c564dad437; ?>
<?php unset($__componentOriginal505efd9768415fdb4543e8c564dad437); ?>
<?php endif; ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\key-value.blade.php ENDPATH**/ ?>