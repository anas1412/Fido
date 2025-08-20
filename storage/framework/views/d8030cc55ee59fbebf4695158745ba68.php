<?php
    use Filament\Support\Facades\FilamentView;

    $id = $getId();
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
<?php $component->withAttributes(['field' => $field,'label-tag' => 'div']); ?>
    <?php if($isDisabled()): ?>
        <div
            aria-labelledby="<?php echo e($id); ?>-label"
            id="<?php echo e($id); ?>"
            role="group"
            class="fi-fo-markdown-editor fi-disabled prose block w-full max-w-none rounded-lg bg-gray-50 px-3 py-3 text-gray-500 shadow-sm ring-1 ring-gray-950/10 dark:prose-invert dark:bg-transparent dark:text-gray-400 dark:ring-white/10 sm:text-sm"
        >
            <?php echo str($getState())->markdown()->sanitizeHtml(); ?>

        </div>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['valid' => ! $errors->has($statePath),'attributes' => 
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['fi-fo-markdown-editor max-w-full overflow-hidden font-mono text-base text-gray-950 dark:text-white sm:text-sm'])
            ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['valid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $errors->has($statePath)),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['fi-fo-markdown-editor max-w-full overflow-hidden font-mono text-base text-gray-950 dark:text-white sm:text-sm'])
            )]); ?>
            <div
                aria-labelledby="<?php echo e($id); ?>-label"
                id="<?php echo e($id); ?>"
                role="group"
                x-load="visible || event (ax-modal-opened)"
                
                x-load-src="<?php echo e(\Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('markdown-editor', 'filament/forms')); ?>"
                x-data="markdownEditorFormComponent({
                            canAttachFiles: <?php echo \Illuminate\Support\Js::from($hasToolbarButton('attachFiles'))->toHtml() ?>,
                            isLiveDebounced: <?php echo \Illuminate\Support\Js::from($isLiveDebounced())->toHtml() ?>,
                            isLiveOnBlur: <?php echo \Illuminate\Support\Js::from($isLiveOnBlur())->toHtml() ?>,
                            liveDebounce: <?php echo \Illuminate\Support\Js::from($getNormalizedLiveDebounce())->toHtml() ?>,
                            maxHeight: <?php echo \Illuminate\Support\Js::from($getMaxHeight())->toHtml() ?>,
                            minHeight: <?php echo \Illuminate\Support\Js::from($getMinHeight())->toHtml() ?>,
                            placeholder: <?php echo \Illuminate\Support\Js::from($getPlaceholder())->toHtml() ?>,
                            state: $wire.<?php echo e($applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false)); ?>,
                            toolbarButtons: <?php echo \Illuminate\Support\Js::from($getToolbarButtons())->toHtml() ?>,
                            translations: <?php echo \Illuminate\Support\Js::from(__('filament-forms::components.markdown_editor'))->toHtml() ?>,
                            uploadFileAttachmentUsing: async (file, onSuccess, onError) => {
                                $wire.upload(`componentFileAttachments.<?php echo e($statePath); ?>`, file, () => {
                                    $wire
                                        .getFormComponentFileAttachmentUrl('<?php echo e($statePath); ?>')
                                        .then((url) => {
                                            if (! url) {
                                                return onError()
                                            }

                                            onSuccess(url)
                                        })
                                })
                            },
                        })"
                wire:ignore
                <?php echo e($getExtraAlpineAttributeBag()); ?>

            >
                <textarea x-ref="editor" class="hidden"></textarea>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\markdown-editor.blade.php ENDPATH**/ ?>