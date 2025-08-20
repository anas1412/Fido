<?php
    use Filament\Support\Facades\FilamentView;

    $isDisabled = $isDisabled();
    $isLive = $isLive();
    $isLiveOnBlur = $isLiveOnBlur();
    $isLiveDebounced = $isLiveDebounced();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $liveDebounce = $getLiveDebounce();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
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
<?php $component->withAttributes(['field' => $field,'inline-label-vertical-alignment' => \Filament\Support\Enums\VerticalAlignment::Center]); ?>
    <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['disabled' => $isDisabled,'inlinePrefix' => $isPrefixInline,'inlineSuffix' => $isSuffixInline,'prefix' => $prefixLabel,'prefixActions' => $prefixActions,'prefixIcon' => $prefixIcon,'prefixIconColor' => $getPrefixIconColor(),'suffix' => $suffixLabel,'suffixActions' => $suffixActions,'suffixIcon' => $suffixIcon,'suffixIconColor' => $getSuffixIconColor(),'valid' => ! $errors->has($statePath),'attributes' => 
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class('fi-fo-color-picker')
        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled),'inline-prefix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isPrefixInline),'inline-suffix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isSuffixInline),'prefix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefixLabel),'prefix-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefixActions),'prefix-icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prefixIcon),'prefix-icon-color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getPrefixIconColor()),'suffix' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($suffixLabel),'suffix-actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($suffixActions),'suffix-icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($suffixIcon),'suffix-icon-color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getSuffixIconColor()),'valid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $errors->has($statePath)),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class('fi-fo-color-picker')
        )]); ?>
        <div
            <?php if(FilamentView::hasSpaMode()): ?>
                x-load="visible || event (ax-modal-opened)"
            <?php else: ?>
                x-load
            <?php endif; ?>
            x-load-src="<?php echo e(\Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('color-picker', 'filament/forms')); ?>"
            x-data="colorPickerFormComponent({
                        isAutofocused: <?php echo \Illuminate\Support\Js::from($isAutofocused())->toHtml() ?>,
                        isDisabled: <?php echo \Illuminate\Support\Js::from($isDisabled)->toHtml() ?>,
                        isLive: <?php echo \Illuminate\Support\Js::from($isLive)->toHtml() ?>,
                        isLiveDebounced: <?php echo \Illuminate\Support\Js::from($isLiveDebounced)->toHtml() ?>,
                        isLiveOnBlur: <?php echo \Illuminate\Support\Js::from($isLiveOnBlur)->toHtml() ?>,
                        liveDebounce: <?php echo \Illuminate\Support\Js::from($liveDebounce)->toHtml() ?>,
                        state: $wire.$entangle('<?php echo e($statePath); ?>'),
                    })"
            x-on:keydown.esc="isOpen() && $event.stopPropagation()"
            <?php echo e($getExtraAlpineAttributeBag()->class(['flex'])); ?>

        >
            <?php if (isset($component)) { $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.index','data' => ['xOn:focus' => '$refs.panel.open($refs.input)','xOn:keydown.enter.stop.prevent' => 'togglePanelVisibility()','xRef' => 'input','attributes' => 
                    \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge([
                            'autocomplete' => 'off',
                            'disabled' => $isDisabled,
                            'id' => $getId(),
                            'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                            'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                            'placeholder' => $getPlaceholder(),
                            'required' => $isRequired() && (! $isConcealed()),
                            'type' => 'text',
                            'x-model' . ($isLiveDebounced ? '.debounce.' . $liveDebounce : null) => 'state',
                            'x-on:blur' => $isLiveOnBlur ? 'isOpen() ? null : commitState()' : null,
                        ], escape: false)
                ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-on:focus' => '$refs.panel.open($refs.input)','x-on:keydown.enter.stop.prevent' => 'togglePanelVisibility()','x-ref' => 'input','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                    \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge([
                            'autocomplete' => 'off',
                            'disabled' => $isDisabled,
                            'id' => $getId(),
                            'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                            'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                            'placeholder' => $getPlaceholder(),
                            'required' => $isRequired() && (! $isConcealed()),
                            'type' => 'text',
                            'x-model' . ($isLiveDebounced ? '.debounce.' . $liveDebounce : null) => 'state',
                            'x-on:blur' => $isLiveOnBlur ? 'isOpen() ? null : commitState()' : null,
                        ], escape: false)
                )]); ?>
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

            <div
                class="fi-fo-color-picker-preview my-auto me-3 h-5 w-5 shrink-0 select-none rounded-full"
                x-on:click="togglePanelVisibility()"
                x-bind:class="{
                    'ring-1 ring-inset ring-gray-200 dark:ring-white/10': ! state,
                }"
                x-bind:style="{ 'background-color': state }"
            ></div>

            <div
                wire:ignore.self
                wire:key="<?php echo e($this->getId()); ?>.<?php echo e($statePath); ?>.<?php echo e($field::class); ?>.panel"
                x-cloak
                x-float.placement.bottom-start.offset.flip.shift="{ offset: 8 }"
                x-ref="panel"
                class="fi-fo-color-picker-panel absolute z-10 hidden rounded-lg shadow-lg"
            >
                <?php
                    $tag = match ($getFormat()) {
                        'hsl' => 'hsl-string',
                        'rgb' => 'rgb-string',
                        'rgba' => 'rgba-string',
                        default => 'hex',
                    } . '-color-picker';
                ?>

                <<?php echo e($tag); ?> color="<?php echo e($getState()); ?>" />
            </div>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\color-picker.blade.php ENDPATH**/ ?>