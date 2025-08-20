<?php
    $canSelectPlaceholder = $canSelectPlaceholder();
    $isDisabled = $isDisabled();

    $state = $getState();
    if ($state instanceof \BackedEnum) {
        $state = $state->value;
    }
    $state = strval($state);
?>

<div
    x-data="{
        error: undefined,

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
                'fi-ta-select w-full min-w-48',
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
        ','xOn:click.stop.prevent' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        ','x-on:click.stop.prevent' => '']); ?>
        <?php if (isset($component)) { $__componentOriginal97dc683fe4ff7acce9e296503563dd85 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal97dc683fe4ff7acce9e296503563dd85 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.select','data' => ['disabled' => $isDisabled,'xBind:disabled' => $isDisabled ? null : 'isLoading','xModel' => 'state','xOn:change' => '
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
            ','attributes' => \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled),'x-bind:disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($isDisabled ? null : 'isLoading'),'x-model' => 'state','x-on:change' => '
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
            ','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag()))]); ?>
            <?php if($canSelectPlaceholder): ?>
                <option value=""><?php echo e($getPlaceholder()); ?></option>
            <?php endif; ?>

            <?php $__currentLoopData = $getOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    <?php if($isOptionDisabled($value, $label)): echo 'disabled'; endif; ?>
                    value="<?php echo e($value); ?>"
                >
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal97dc683fe4ff7acce9e296503563dd85)): ?>
<?php $attributes = $__attributesOriginal97dc683fe4ff7acce9e296503563dd85; ?>
<?php unset($__attributesOriginal97dc683fe4ff7acce9e296503563dd85); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal97dc683fe4ff7acce9e296503563dd85)): ?>
<?php $component = $__componentOriginal97dc683fe4ff7acce9e296503563dd85; ?>
<?php unset($__componentOriginal97dc683fe4ff7acce9e296503563dd85); ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\columns\select-column.blade.php ENDPATH**/ ?>