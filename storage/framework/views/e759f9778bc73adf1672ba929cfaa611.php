<?php
    use Filament\Forms\Components\Actions\Action;
    use Filament\Support\Enums\Alignment;

    $containers = $getChildComponentContainers();
    $blockPickerBlocks = $getBlockPickerBlocks();
    $blockPickerColumns = $getBlockPickerColumns();
    $blockPickerWidth = $getBlockPickerWidth();
    $hasBlockPreviews = $hasBlockPreviews();
    $hasInteractiveBlockPreviews = $hasInteractiveBlockPreviews();

    $addAction = $getAction($getAddActionName());
    $addBetweenAction = $getAction($getAddBetweenActionName());
    $cloneAction = $getAction($getCloneActionName());
    $collapseAllAction = $getAction($getCollapseAllActionName());
    $editAction = $getAction($getEditActionName());
    $expandAllAction = $getAction($getExpandAllActionName());
    $deleteAction = $getAction($getDeleteActionName());
    $moveDownAction = $getAction($getMoveDownActionName());
    $moveUpAction = $getAction($getMoveUpActionName());
    $reorderAction = $getAction($getReorderActionName());
    $extraItemActions = $getExtraItemActions();

    $isAddable = $isAddable();
    $isCloneable = $isCloneable();
    $isCollapsible = $isCollapsible();
    $isDeletable = $isDeletable();
    $isReorderableWithButtons = $isReorderableWithButtons();
    $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();

    $collapseAllActionIsVisible = $isCollapsible && $collapseAllAction->isVisible();
    $expandAllActionIsVisible = $isCollapsible && $expandAllAction->isVisible();

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
<?php $component->withAttributes(['field' => $field]); ?>
    <div
        x-data="{}"
        <?php echo e($attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class(['fi-fo-builder grid grid-cols-1 gap-y-4'])); ?>

    >
        <?php if($collapseAllActionIsVisible || $expandAllActionIsVisible): ?>
            <div
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'flex gap-x-3',
                    'hidden' => count($containers) < 2,
                ]); ?>"
            >
                <?php if($collapseAllActionIsVisible): ?>
                    <span
                        x-on:click="$dispatch('builder-collapse', '<?php echo e($statePath); ?>')"
                    >
                        <?php echo e($collapseAllAction); ?>

                    </span>
                <?php endif; ?>

                <?php if($expandAllActionIsVisible): ?>
                    <span
                        x-on:click="$dispatch('builder-expand', '<?php echo e($statePath); ?>')"
                    >
                        <?php echo e($expandAllAction); ?>

                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(count($containers)): ?>
            <ul
                x-sortable
                data-sortable-animation-duration="<?php echo e($getReorderAnimationDuration()); ?>"
                wire:end.stop="<?php echo e('mountFormComponentAction(\'' . $statePath . '\', \'reorder\', { items: $event.target.sortable.toArray() })'); ?>"
                class="space-y-4"
            >
                <?php
                    $hasBlockLabels = $hasBlockLabels();
                    $hasBlockIcons = $hasBlockIcons();
                    $hasBlockNumbers = $hasBlockNumbers();
                ?>

                <?php $__currentLoopData = $containers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uuid => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $visibleExtraItemActions = array_filter(
                            $extraItemActions,
                            fn (Action $action): bool => $action(['item' => $uuid])->isVisible(),
                        );
                        $cloneAction = $cloneAction(['item' => $uuid]);
                        $cloneActionIsVisible = $isCloneable && $cloneAction->isVisible();
                        $deleteAction = $deleteAction(['item' => $uuid]);
                        $deleteActionIsVisible = $isDeletable && $deleteAction->isVisible();
                        $editAction = $editAction(['item' => $uuid]);
                        $editActionIsVisible = $hasBlockPreviews && $editAction->isVisible();
                        $moveDownAction = $moveDownAction(['item' => $uuid])->disabled($loop->last);
                        $moveDownActionIsVisible = $isReorderableWithButtons && $moveDownAction->isVisible();
                        $moveUpAction = $moveUpAction(['item' => $uuid])->disabled($loop->first);
                        $moveUpActionIsVisible = $isReorderableWithButtons && $moveUpAction->isVisible();
                        $reorderActionIsVisible = $isReorderableWithDragAndDrop && $reorderAction->isVisible();
                    ?>

                    <li
                        wire:ignore.self
                        wire:key="<?php echo e($this->getId()); ?>.<?php echo e($item->getStatePath()); ?>.<?php echo e($field::class); ?>.item"
                        x-data="{
                            isCollapsed: <?php echo \Illuminate\Support\Js::from($isCollapsed($item))->toHtml() ?>,
                        }"
                        x-on:builder-expand.window="$event.detail === '<?php echo e($statePath); ?>' && (isCollapsed = false)"
                        x-on:builder-collapse.window="$event.detail === '<?php echo e($statePath); ?>' && (isCollapsed = true)"
                        x-on:expand="isCollapsed = false"
                        x-sortable-item="<?php echo e($uuid); ?>"
                        class="fi-fo-builder-item rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-white/5 dark:ring-white/10"
                        x-bind:class="{ 'fi-collapsed': isCollapsed }"
                    >
                        <?php if($reorderActionIsVisible || $moveUpActionIsVisible || $moveDownActionIsVisible || $hasBlockIcons || $hasBlockLabels || $editActionIsVisible || $cloneActionIsVisible || $deleteActionIsVisible || $isCollapsible || $visibleExtraItemActions): ?>
                            <div
                                <?php if($isCollapsible): ?>
                                    x-on:click.stop="isCollapsed = !isCollapsed"
                                <?php endif; ?>
                                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'fi-fo-builder-item-header flex items-center gap-x-3 overflow-hidden px-4 py-3',
                                    'cursor-pointer select-none' => $isCollapsible,
                                ]); ?>"
                            >
                                <?php if($reorderActionIsVisible || $moveUpActionIsVisible || $moveDownActionIsVisible): ?>
                                    <ul class="flex items-center gap-x-3">
                                        <?php if($reorderActionIsVisible): ?>
                                            <li
                                                x-sortable-handle
                                                x-on:click.stop
                                            >
                                                <?php echo e($reorderAction); ?>

                                            </li>
                                        <?php endif; ?>

                                        <?php if($moveUpActionIsVisible || $moveDownActionIsVisible): ?>
                                            <li x-on:click.stop>
                                                <?php echo e($moveUpAction); ?>

                                            </li>

                                            <li x-on:click.stop>
                                                <?php echo e($moveDownAction); ?>

                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>

                                <?php
                                    $blockIcon = $item->getParentComponent()->getIcon($item->getRawState(), $uuid);
                                ?>

                                <?php if($hasBlockIcons && filled($blockIcon)): ?>
                                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $blockIcon,'class' => 'fi-fo-builder-item-header-icon h-5 w-5 text-gray-400 dark:text-gray-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockIcon),'class' => 'fi-fo-builder-item-header-icon h-5 w-5 text-gray-400 dark:text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasBlockLabels): ?>
                                    <h4
                                        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                            'text-sm font-medium text-gray-950 dark:text-white',
                                            'truncate' => $isBlockLabelTruncated(),
                                        ]); ?>"
                                    >
                                        <?php echo e($item->getParentComponent()->getLabel($item->getRawState(), $uuid)); ?>


                                        <?php if($hasBlockNumbers): ?>
                                            <?php echo e($loop->iteration); ?>

                                        <?php endif; ?>
                                    </h4>
                                <?php endif; ?>

                                <?php if($editActionIsVisible || $cloneActionIsVisible || $deleteActionIsVisible || $isCollapsible || $visibleExtraItemActions): ?>
                                    <ul
                                        class="ms-auto flex items-center gap-x-3"
                                    >
                                        <?php $__currentLoopData = $visibleExtraItemActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraItemAction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li x-on:click.stop>
                                                <?php echo e($extraItemAction(['item' => $uuid])); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <?php if($editActionIsVisible): ?>
                                            <li x-on:click.stop>
                                                <?php echo e($editAction); ?>

                                            </li>
                                        <?php endif; ?>

                                        <?php if($cloneActionIsVisible): ?>
                                            <li x-on:click.stop>
                                                <?php echo e($cloneAction); ?>

                                            </li>
                                        <?php endif; ?>

                                        <?php if($deleteActionIsVisible): ?>
                                            <li x-on:click.stop>
                                                <?php echo e($deleteAction); ?>

                                            </li>
                                        <?php endif; ?>

                                        <?php if($isCollapsible): ?>
                                            <li
                                                class="relative transition"
                                                x-on:click.stop="isCollapsed = !isCollapsed"
                                                x-bind:class="{ '-rotate-180': isCollapsed }"
                                            >
                                                <div
                                                    class="transition"
                                                    x-bind:class="{ 'opacity-0 pointer-events-none': isCollapsed }"
                                                >
                                                    <?php echo e($getAction('collapse')); ?>

                                                </div>

                                                <div
                                                    class="absolute inset-0 rotate-180 transition"
                                                    x-bind:class="{ 'opacity-0 pointer-events-none': ! isCollapsed }"
                                                >
                                                    <?php echo e($getAction('expand')); ?>

                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div
                            x-show="! isCollapsed"
                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'fi-fo-builder-item-content relative border-t border-gray-100 dark:border-white/10',
                                'p-4' => ! ($hasBlockPreviews && $item->getParentComponent()->hasPreview()),
                            ]); ?>"
                        >
                            <?php if($hasBlockPreviews && $item->getParentComponent()->hasPreview()): ?>
                                <div
                                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'fi-fo-builder-item-preview',
                                        'pointer-events-none' => ! $hasInteractiveBlockPreviews,
                                    ]); ?>"
                                >
                                    <?php echo e($item->getParentComponent()->renderPreview($item->getRawState())); ?>

                                </div>

                                <?php if($editActionIsVisible && (! $hasInteractiveBlockPreviews)): ?>
                                    <div
                                        class="absolute inset-0 z-[1] cursor-pointer"
                                        role="button"
                                        x-on:click.stop="<?php echo e('$wire.mountFormComponentAction(\'' . $statePath . '\', \'edit\', { item: \'' . $uuid . '\' })'); ?>"
                                    ></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo e($item); ?>

                            <?php endif; ?>
                        </div>
                    </li>

                    <?php if(! $loop->last): ?>
                        <?php if($isAddable && $addBetweenAction(['afterItem' => $uuid])->isVisible()): ?>
                            <li class="relative -top-2 !mt-0 h-0">
                                <div
                                    class="flex w-full justify-center opacity-0 transition duration-75 hover:opacity-100"
                                >
                                    <div
                                        class="fi-fo-builder-block-picker-ctn rounded-lg bg-white dark:bg-gray-900"
                                    >
                                        <?php if (isset($component)) { $__componentOriginalcf011b913eed6cf65506740170d1fccb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf011b913eed6cf65506740170d1fccb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.builder.block-picker','data' => ['action' => $addBetweenAction,'afterItem' => $uuid,'columns' => $blockPickerColumns,'blocks' => $blockPickerBlocks,'statePath' => $statePath,'width' => $blockPickerWidth]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::builder.block-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($addBetweenAction),'after-item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($uuid),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerColumns),'blocks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerBlocks),'state-path' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statePath),'width' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerWidth)]); ?>
                                             <?php $__env->slot('trigger', null, []); ?> 
                                                <?php echo e($addBetweenAction(['afterItem' => $uuid])); ?>

                                             <?php $__env->endSlot(); ?>
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf011b913eed6cf65506740170d1fccb)): ?>
<?php $attributes = $__attributesOriginalcf011b913eed6cf65506740170d1fccb; ?>
<?php unset($__attributesOriginalcf011b913eed6cf65506740170d1fccb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf011b913eed6cf65506740170d1fccb)): ?>
<?php $component = $__componentOriginalcf011b913eed6cf65506740170d1fccb; ?>
<?php unset($__componentOriginalcf011b913eed6cf65506740170d1fccb); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php elseif(filled($labelBetweenItems = $getLabelBetweenItems())): ?>
                            <li
                                class="relative border-t border-gray-200 dark:border-white/10"
                            >
                                <span
                                    class="absolute -top-3 left-3 px-1 text-sm font-medium"
                                >
                                    <?php echo e($labelBetweenItems); ?>

                                </span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>

        <?php if($isAddable && $addAction->isVisible()): ?>
            <?php if (isset($component)) { $__componentOriginalcf011b913eed6cf65506740170d1fccb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcf011b913eed6cf65506740170d1fccb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.builder.block-picker','data' => ['action' => $addAction,'blocks' => $blockPickerBlocks,'columns' => $blockPickerColumns,'statePath' => $statePath,'width' => $blockPickerWidth,'class' => \Illuminate\Support\Arr::toCssClasses([
                    'flex',
                    match ($getAddActionAlignment()) {
                        Alignment::Start, Alignment::Left => 'justify-start',
                        Alignment::Center, null => 'justify-center',
                        Alignment::End, Alignment::Right => 'justify-end',
                        default => $alignment,
                    },
                ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::builder.block-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($addAction),'blocks' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerBlocks),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerColumns),'state-path' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($statePath),'width' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blockPickerWidth),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
                    'flex',
                    match ($getAddActionAlignment()) {
                        Alignment::Start, Alignment::Left => 'justify-start',
                        Alignment::Center, null => 'justify-center',
                        Alignment::End, Alignment::Right => 'justify-end',
                        default => $alignment,
                    },
                ]))]); ?>
                 <?php $__env->slot('trigger', null, []); ?> 
                    <?php echo e($addAction); ?>

                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcf011b913eed6cf65506740170d1fccb)): ?>
<?php $attributes = $__attributesOriginalcf011b913eed6cf65506740170d1fccb; ?>
<?php unset($__attributesOriginalcf011b913eed6cf65506740170d1fccb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcf011b913eed6cf65506740170d1fccb)): ?>
<?php $component = $__componentOriginalcf011b913eed6cf65506740170d1fccb; ?>
<?php unset($__componentOriginalcf011b913eed6cf65506740170d1fccb); ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\builder.blade.php ENDPATH**/ ?>