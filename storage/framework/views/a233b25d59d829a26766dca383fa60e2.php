<?php
    use Filament\Support\Enums\Alignment;

    $isDisabled = $isDisabled();
    $state = $getState();
    $mask = $getMask();

    $alignment = $getAlignment() ?? Alignment::Start;

    if (! $alignment instanceof Alignment) {
        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
    }

    if (filled($mask)) {
        $type = 'text';
    } else {
        $type = $getType();
    }
?>

<div
    x-data="{
        error: undefined,

        isEditing: false,

        isLoading: false,

        name: <?php echo \Illuminate\Support\Js::from($getName())->toHtml() ?>,

        recordKey: <?php echo \Illuminate\Support\Js::from($recordKey)->toHtml() ?>,

        state: <?php echo \Illuminate\Support\Js::from($state)->toHtml() ?>,
    }"
    x-init="
        () => {
            Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
                succeed(({ snapshot, effect }) => {
                    $nextTick(() => {
                        if (component.id !== <?php echo \Illuminate\Support\Js::from($this->getId())->toHtml() ?>) {
                            return
                        }

                        if (isEditing) {
                            return
                        }

                        if (! $refs.newState) {
                            return
                        }

                        let newState = $refs.newState.value.replaceAll('\\'+String.fromCharCode(34), String.fromCharCode(34))

                        if (state === newState) {
                            return
                        }

                        state = newState
                    })
                })
            })
        }
    "
    <?php echo e($attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-ta-text-input w-full min-w-48',
                'px-3 py-4' => ! $isInline(),
            ])); ?>

>
    <input
        type="hidden"
        value="<?php echo e(str($state)->replace('"', '\\"')); ?>"
        x-ref="newState"
    />

    <?php if (isset($component)) { $__componentOriginal505efd9768415fdb4543e8c564dad437 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal505efd9768415fdb4543e8c564dad437 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.wrapper','data' => ['alpineDisabled' => 'isLoading || ' . \Illuminate\Support\Js::from($isDisabled),'alpineValid' => 'error === undefined','xTooltip' => '
            error === undefined
                ? false
                : {
                    content: error,
                    theme: $store.theme,
                }
        ','xOn:click.stop' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.wrapper'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['alpine-disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('isLoading || ' . \Illuminate\Support\Js::from($isDisabled)),'alpine-valid' => 'error === undefined','x-tooltip' => '
            error === undefined
                ? false
                : {
                    content: error,
                    theme: $store.theme,
                }
        ','x-on:click.stop' => '']); ?>
        
        <?php if (isset($component)) { $__componentOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ad6b66c56a2379ee0ba04e1e358c61e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.index','data' => ['disabled' => $isDisabled,'inputMode' => $getInputMode(),'placeholder' => $getPlaceholder(),'step' => $getStep(),'type' => $type,'xBind:disabled' => $isDisabled ? null : 'isLoading','xModel' => 'state','xOn:blur' => 'isEditing = false','xOn:focus' => 'isEditing = true','attributes' => 
                \Filament\Support\prepare_inherited_attributes(
                    $getExtraInputAttributeBag()
                        ->merge([
                            'x-on:change' . ($type === 'number' ? '.debounce.1s' : null) => '
                                isLoading = true

                                const response = await $wire.updateTableColumnState(
                                    name,
                                    recordKey,
                                    $event.target.value,
                                )

                                error = response?.error ?? undefined

                                if (! error) {
                                    state = response
                                }

                                isLoading = false
                            ',
                            'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
                        ])
                        ->class([
                            match ($alignment) {
                                Alignment::Start => 'text-start',
                                Alignment::Center => 'text-center',
                                Alignment::End => 'text-end',
                                Alignment::Left => 'text-left',
                                Alignment::Right => 'text-right',
                                Alignment::Justify, Alignment::Between => 'text-justify',
                                default => $alignment,
                            },
                        ])
                )
            ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled),'input-mode' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getInputMode()),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getPlaceholder()),'step' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getStep()),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($type),'x-bind:disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled ? null : 'isLoading'),'x-model' => 'state','x-on:blur' => 'isEditing = false','x-on:focus' => 'isEditing = true','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                \Filament\Support\prepare_inherited_attributes(
                    $getExtraInputAttributeBag()
                        ->merge([
                            'x-on:change' . ($type === 'number' ? '.debounce.1s' : null) => '
                                isLoading = true

                                const response = await $wire.updateTableColumnState(
                                    name,
                                    recordKey,
                                    $event.target.value,
                                )

                                error = response?.error ?? undefined

                                if (! error) {
                                    state = response
                                }

                                isLoading = false
                            ',
                            'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
                        ])
                        ->class([
                            match ($alignment) {
                                Alignment::Start => 'text-start',
                                Alignment::Center => 'text-center',
                                Alignment::End => 'text-end',
                                Alignment::Left => 'text-left',
                                Alignment::Right => 'text-right',
                                Alignment::Justify, Alignment::Between => 'text-justify',
                                default => $alignment,
                            },
                        ])
                )
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
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\text-input-column.blade.php ENDPATH**/ ?>