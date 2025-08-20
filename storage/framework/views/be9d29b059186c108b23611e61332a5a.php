<?php
    $isDisabled = $isDisabled();
    $state = (bool) $getState();
?>

<div
    x-data="{
        error: undefined,

        isLoading: false,

        name: <?php echo \Illuminate\Support\Js::from($getName())->toHtml() ?>,

        recordKey: <?php echo \Illuminate\Support\Js::from($recordKey)->toHtml() ?>,

        state: <?php echo \Illuminate\Support\Js::from($state)->toHtml() ?>,
    }"
    x-on:click.stop.prevent=""
    x-init="
        () => {
            Livewire.hook('commit', ({ component, commit, succeed, fail, respond }) => {
                succeed(({ snapshot, effect }) => {
                    $nextTick(() => {
                        if (component.id !== <?php echo \Illuminate\Support\Js::from($this->getId())->toHtml() ?>) {
                            return
                        }

                        if (! $refs.newState) {
                            return
                        }

                        const newState = $refs.newState.value === '1' ? true : false

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
                'fi-ta-checkbox flex items-center',
                'px-3 py-4' => ! $isInline(),
            ])); ?>

>
    <input type="hidden" value="<?php echo e($state ? 1 : 0); ?>" x-ref="newState" />

    <?php if (isset($component)) { $__componentOriginal087b30d649cc1cfb7e9ea8930c92d47d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal087b30d649cc1cfb7e9ea8930c92d47d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.checkbox','data' => ['alpineValid' => '! error','disabled' => $isDisabled,'xBind:disabled' => $isDisabled ? null : 'isLoading','xModel' => 'state','xOn:change' => '
            isLoading = true

            const response = await $wire.updateTableColumnState(
                name,
                recordKey,
                $event.target.checked,
            )

            error = response?.error ?? undefined

            isLoading = false
        ','xTooltip' => '
            error === undefined
                ? false
                : {
                    content: error,
                    theme: $store.theme,
                }
        ','xOn:click.stop' => '','attributes' => 
            \Filament\Support\prepare_inherited_attributes($attributes)
                ->merge($getExtraInputAttributes(), escape: false)
        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['alpine-valid' => '! error','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled),'x-bind:disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled ? null : 'isLoading'),'x-model' => 'state','x-on:change' => '
            isLoading = true

            const response = await $wire.updateTableColumnState(
                name,
                recordKey,
                $event.target.checked,
            )

            error = response?.error ?? undefined

            isLoading = false
        ','x-tooltip' => '
            error === undefined
                ? false
                : {
                    content: error,
                    theme: $store.theme,
                }
        ','x-on:click.stop' => '','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
            \Filament\Support\prepare_inherited_attributes($attributes)
                ->merge($getExtraInputAttributes(), escape: false)
        )]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal087b30d649cc1cfb7e9ea8930c92d47d)): ?>
<?php $attributes = $__attributesOriginal087b30d649cc1cfb7e9ea8930c92d47d; ?>
<?php unset($__attributesOriginal087b30d649cc1cfb7e9ea8930c92d47d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal087b30d649cc1cfb7e9ea8930c92d47d)): ?>
<?php $component = $__componentOriginal087b30d649cc1cfb7e9ea8930c92d47d; ?>
<?php unset($__componentOriginal087b30d649cc1cfb7e9ea8930c92d47d); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\checkbox-column.blade.php ENDPATH**/ ?>