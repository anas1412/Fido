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
<?php $component->withAttributes(['field' => $field]); ?>
    <?php if($isDisabled()): ?>
        <div
            x-data="{
                state: $wire.<?php echo e($applyStateBindingModifiers("\$entangle('{$statePath}')")); ?>,
            }"
            x-html="state"
            class="fi-fo-rich-editor fi-disabled prose block w-full max-w-none rounded-lg bg-gray-50 px-3 py-3 text-gray-500 shadow-sm ring-1 ring-gray-950/10 dark:prose-invert dark:bg-transparent dark:text-gray-400 dark:ring-white/10 sm:text-sm"
        ></div>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['valid' => ! $errors->has($statePath),'attributes' => 
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['fi-fo-rich-editor max-w-full overflow-x-auto'])
            ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['valid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $errors->has($statePath)),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                    ->class(['fi-fo-rich-editor max-w-full overflow-x-auto'])
            )]); ?>
            <div
                <?php if(FilamentView::hasSpaMode()): ?>
                    x-load="visible || event (ax-modal-opened)"
                <?php else: ?>
                    x-load
                <?php endif; ?>
                x-load-src="<?php echo e(\Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('rich-editor', 'filament/forms')); ?>"
                x-data="richEditorFormComponent({
                            state: $wire.<?php echo e($applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false)); ?>,
                        })"
                x-on:trix-attachment-add="
                    if (! $event.attachment.file) return

                    let attachment = $event.attachment

                    $wire.upload(
                        `componentFileAttachments.<?php echo e($statePath); ?>`,
                        attachment.file,
                        () => {
                            $wire
                                .getFormComponentFileAttachmentUrl('<?php echo e($statePath); ?>')
                                .then((url) => {
                                    attachment.setAttributes({
                                        url: url,
                                        href: url,
                                    })
                                })
                        },
                    )
                "
                x-on:trix-change="
                    let value = $event.target.value

                    $nextTick(() => {
                        if (! $refs.trix) {
                            return
                        }

                        state = value
                    })
                "
                <?php if($isLiveDebounced()): ?>
                    x-on:trix-change.debounce.<?php echo e($getLiveDebounce()); ?>="
                        $nextTick(() => {
                            if (! $refs.trix) {
                                return
                            }

                            $wire.call('$refresh')
                        })
                    "
                <?php endif; ?>
                <?php if(! $hasToolbarButton('attachFiles')): ?>
                    x-on:trix-file-accept="$event.preventDefault()"
                <?php endif; ?>
                <?php echo e($getExtraAlpineAttributeBag()); ?>

            >
                <input
                    id="trix-value-<?php echo e($id); ?>"
                    x-ref="trixValue"
                    type="hidden"
                />

                <trix-toolbar
                    id="trix-toolbar-<?php echo e($id); ?>"
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'fi-fo-rich-editor-toolbar relative flex flex-col gap-x-3 border-b border-gray-100 px-2.5 py-2 dark:border-white/10',
                        'hidden' => ! count($getToolbarButtons()),
                    ]); ?>"
                >
                    <div class="flex gap-x-3 overflow-x-auto">
                        <?php if($hasToolbarButton(['bold', 'italic', 'underline', 'strike', 'link'])): ?>
                            <?php if (isset($component)) { $__componentOriginalf21079f320da8e97fd08192b02ab8475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf21079f320da8e97fd08192b02ab8475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.group','data' => ['dataTrixButtonGroup' => 'text-tools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-button-group' => 'text-tools']); ?>
                                <?php if($hasToolbarButton('bold')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'bold','dataTrixKey' => 'b','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.bold')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'bold','data-trix-key' => 'b','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.bold')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-bold','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-bold','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('italic')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'italic','dataTrixKey' => 'i','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.italic')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'italic','data-trix-key' => 'i','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.italic')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-italic','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-italic','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('underline')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'underline','dataTrixKey' => 'u','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.underline')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'underline','data-trix-key' => 'u','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.underline')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-underline','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-underline','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('strike')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'strike','dataTrixKey' => 's','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.strike')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'strike','data-trix-key' => 's','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.strike')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-strikethrough','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-strikethrough','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('link')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'href','dataTrixAction' => 'link','dataTrixKey' => 'k','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.link')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'href','data-trix-action' => 'link','data-trix-key' => 'k','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.link')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-link','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-link','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $attributes = $__attributesOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__attributesOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $component = $__componentOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__componentOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <?php if($hasToolbarButton(['h1', 'h2', 'h3'])): ?>
                            <?php if (isset($component)) { $__componentOriginalf21079f320da8e97fd08192b02ab8475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf21079f320da8e97fd08192b02ab8475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.group','data' => ['dataTrixButtonGroup' => 'heading-tools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-button-group' => 'heading-tools']); ?>
                                <?php if($hasToolbarButton('h1')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'heading1','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h1')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'heading1','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h1')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-h1','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-h1','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('h2')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'heading','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h2')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'heading','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h2')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-h2','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-h2','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('h3')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'subHeading','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h3')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'subHeading','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.h3')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-h3','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-h3','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $attributes = $__attributesOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__attributesOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $component = $__componentOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__componentOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <?php if($hasToolbarButton(['blockquote', 'codeBlock', 'bulletList', 'orderedList'])): ?>
                            <?php if (isset($component)) { $__componentOriginalf21079f320da8e97fd08192b02ab8475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf21079f320da8e97fd08192b02ab8475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.group','data' => ['dataTrixButtonGroup' => 'block-tools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-button-group' => 'block-tools']); ?>
                                <?php if($hasToolbarButton('blockquote')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'quote','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.blockquote')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'quote','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.blockquote')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-chat-bubble-bottom-center-text','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-chat-bubble-bottom-center-text','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('codeBlock')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'code','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.code_block')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'code','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.code_block')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-code-bracket','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-code-bracket','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('bulletList')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'bullet','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.bullet_list')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'bullet','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.bullet_list')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-list-bullet','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-list-bullet','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('orderedList')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAttribute' => 'number','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.ordered_list')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-attribute' => 'number','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.ordered_list')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-numbered-list','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-numbered-list','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $attributes = $__attributesOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__attributesOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $component = $__componentOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__componentOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <?php if($hasToolbarButton('attachFiles')): ?>
                            <?php if (isset($component)) { $__componentOriginalf21079f320da8e97fd08192b02ab8475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf21079f320da8e97fd08192b02ab8475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.group','data' => ['dataTrixButtonGroup' => 'file-tools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-button-group' => 'file-tools']); ?>
                                <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAction' => 'attachFiles','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.attach_files')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-action' => 'attachFiles','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.attach_files')).'','tabindex' => '-1']); ?>
                                    <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-photo','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-photo','class' => 'h-5 w-5']); ?>
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
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $attributes = $__attributesOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__attributesOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $component = $__componentOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__componentOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
                        <?php endif; ?>

                        <?php if($hasToolbarButton(['undo', 'redo'])): ?>
                            <?php if (isset($component)) { $__componentOriginalf21079f320da8e97fd08192b02ab8475 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf21079f320da8e97fd08192b02ab8475 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.group','data' => ['dataTrixButtonGroup' => 'history-tools']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-button-group' => 'history-tools']); ?>
                                <?php if($hasToolbarButton('undo')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAction' => 'undo','dataTrixKey' => 'z','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.undo')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-action' => 'undo','data-trix-key' => 'z','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.undo')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-arrow-uturn-left','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-arrow-uturn-left','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>

                                <?php if($hasToolbarButton('redo')): ?>
                                    <?php if (isset($component)) { $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-forms::components.rich-editor.toolbar.button','data' => ['dataTrixAction' => 'redo','dataTrixKey' => 'shift+z','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.redo')).'','tabindex' => '-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-forms::rich-editor.toolbar.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data-trix-action' => 'redo','data-trix-key' => 'shift+z','title' => ''.e(__('filament-forms::components.rich_editor.toolbar_buttons.redo')).'','tabindex' => '-1']); ?>
                                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-arrow-uturn-right','class' => 'h-5 w-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-arrow-uturn-right','class' => 'h-5 w-5']); ?>
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
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $attributes = $__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__attributesOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005)): ?>
<?php $component = $__componentOriginal4ffbd1ca444c7c0765c9f59c53769005; ?>
<?php unset($__componentOriginal4ffbd1ca444c7c0765c9f59c53769005); ?>
<?php endif; ?>
                                <?php endif; ?>
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $attributes = $__attributesOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__attributesOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf21079f320da8e97fd08192b02ab8475)): ?>
<?php $component = $__componentOriginalf21079f320da8e97fd08192b02ab8475; ?>
<?php unset($__componentOriginalf21079f320da8e97fd08192b02ab8475); ?>
<?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div x-cloak data-trix-dialogs class="trix-dialogs">
                        <div
                            data-trix-dialog="href"
                            data-trix-dialog-attribute="href"
                            class="trix-dialog trix-dialog--link"
                        >
                            <div class="trix-dialog__link-fields">
                                <input
                                    aria-label="<?php echo e(__('filament-forms::components.rich_editor.dialogs.link.label')); ?>"
                                    data-trix-input
                                    disabled
                                    name="href"
                                    placeholder="<?php echo e(__('filament-forms::components.rich_editor.dialogs.link.placeholder')); ?>"
                                    required
                                    type="text"
                                    inputmode="url"
                                    class="trix-input trix-input--dialog"
                                />

                                <div class="trix-button-group">
                                    <input
                                        data-trix-method="setAttribute"
                                        type="button"
                                        value="<?php echo e(__('filament-forms::components.rich_editor.dialogs.link.actions.link')); ?>"
                                        class="trix-button trix-button--dialog"
                                    />

                                    <input
                                        data-trix-method="removeAttribute"
                                        type="button"
                                        value="<?php echo e(__('filament-forms::components.rich_editor.dialogs.link.actions.unlink')); ?>"
                                        class="trix-button trix-button--dialog"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </trix-toolbar>

                <trix-editor
                    <?php if($isAutofocused()): ?>
                        autofocus
                    <?php endif; ?>
                    id="<?php echo e($id); ?>"
                    input="trix-value-<?php echo e($id); ?>"
                    placeholder="<?php echo e($getPlaceholder()); ?>"
                    toolbar="trix-toolbar-<?php echo e($id); ?>"
                    <?php if($isLiveOnBlur()): ?>
                        x-on:blur="$wire.call('$refresh')"
                    <?php endif; ?>
                    x-ref="trix"
                    wire:ignore
                    <?php if($isGrammarlyDisabled()): ?>
                        data-gramm="false"
                        data-gramm_editor="false"
                        data-enable-grammarly="false"
                    <?php endif; ?>
                    <?php echo e($getExtraInputAttributeBag()->class([
                            'fi-fo-rich-editor-editor prose min-h-[theme(spacing.48)] max-w-none !border-none px-3 py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6',
                        ])); ?>

                ></trix-editor>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\rich-editor.blade.php ENDPATH**/ ?>