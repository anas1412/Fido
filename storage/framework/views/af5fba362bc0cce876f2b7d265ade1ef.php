<?php
    $gridDirection = $getGridDirection() ?? 'column';
    $id = $getId();
    $isDisabled = $isDisabled();
    $isInline = $isInline();
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
    <?php if (isset($component)) { $__componentOriginal30dbd75eb120a380110a2b340cd88f46 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30dbd75eb120a380110a2b340cd88f46 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.grid.index','data' => ['default' => $getColumns('default'),'sm' => $getColumns('sm'),'md' => $getColumns('md'),'lg' => $getColumns('lg'),'xl' => $getColumns('xl'),'twoXl' => $getColumns('2xl'),'isGrid' => ! $isInline,'direction' => $gridDirection,'attributes' => 
            \Filament\Support\prepare_inherited_attributes($attributes)
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-fo-radio gap-4',
                    '-mt-4' => (! $isInline) && ($gridDirection === 'column'),
                    'flex flex-wrap' => $isInline,
                ])
        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['default' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('default')),'sm' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('sm')),'md' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('md')),'lg' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('lg')),'xl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('xl')),'two-xl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getColumns('2xl')),'is-grid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $isInline),'direction' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($gridDirection),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
            \Filament\Support\prepare_inherited_attributes($attributes)
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'fi-fo-radio gap-4',
                    '-mt-4' => (! $isInline) && ($gridDirection === 'column'),
                    'flex flex-wrap' => $isInline,
                ])
        )]); ?>
        <?php $__currentLoopData = $getOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div
                class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                    'break-inside-avoid pt-4' => (! $isInline) && ($gridDirection === 'column'),
                ]); ?>"
            >
                <label class="flex gap-x-3">
                    <?php if (isset($component)) { $__componentOriginal6d895012f0eef49c5c500cec08beeef6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6d895012f0eef49c5c500cec08beeef6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.input.radio','data' => ['valid' => ! $errors->has($statePath),'attributes' => 
                            \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                                ->merge([
                                    'disabled' => $isDisabled || $isOptionDisabled($value, $label),
                                    'id' => $id . '-' . $value,
                                    'name' => $id,
                                    'value' => $value,
                                    'wire:loading.attr' => 'disabled',
                                    $applyStateBindingModifiers('wire:model') => $statePath,
                                ], escape: false)
                                ->class(['mt-1'])
                        ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::input.radio'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['valid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(! $errors->has($statePath)),'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                            \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                                ->merge([
                                    'disabled' => $isDisabled || $isOptionDisabled($value, $label),
                                    'id' => $id . '-' . $value,
                                    'name' => $id,
                                    'value' => $value,
                                    'wire:loading.attr' => 'disabled',
                                    $applyStateBindingModifiers('wire:model') => $statePath,
                                ], escape: false)
                                ->class(['mt-1'])
                        )]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6d895012f0eef49c5c500cec08beeef6)): ?>
<?php $attributes = $__attributesOriginal6d895012f0eef49c5c500cec08beeef6; ?>
<?php unset($__attributesOriginal6d895012f0eef49c5c500cec08beeef6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6d895012f0eef49c5c500cec08beeef6)): ?>
<?php $component = $__componentOriginal6d895012f0eef49c5c500cec08beeef6; ?>
<?php unset($__componentOriginal6d895012f0eef49c5c500cec08beeef6); ?>
<?php endif; ?>

                    <div class="grid text-sm leading-6">
                        <span class="font-medium text-gray-950 dark:text-white">
                            <?php echo e($label); ?>

                        </span>

                        <?php if($hasDescription($value)): ?>
                            <p class="text-gray-500 dark:text-gray-400">
                                <?php echo e($getDescription($value)); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                </label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30dbd75eb120a380110a2b340cd88f46)): ?>
<?php $attributes = $__attributesOriginal30dbd75eb120a380110a2b340cd88f46; ?>
<?php unset($__attributesOriginal30dbd75eb120a380110a2b340cd88f46); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30dbd75eb120a380110a2b340cd88f46)): ?>
<?php $component = $__componentOriginal30dbd75eb120a380110a2b340cd88f46; ?>
<?php unset($__componentOriginal30dbd75eb120a380110a2b340cd88f46); ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\radio.blade.php ENDPATH**/ ?>