<?php if (isset($component)) { $__componentOriginal70308eab0db7bee07ae0d7b141f6dc83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal70308eab0db7bee07ae0d7b141f6dc83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-actions::components.action','data' => ['action' => $action,'dynamicComponent' => 'filament::badge','iconPosition' => $getIconPosition(),'size' => $getSize(),'class' => 'fi-ac-badge-action']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-actions::action'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($action),'dynamic-component' => 'filament::badge','icon-position' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getIconPosition()),'size' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getSize()),'class' => 'fi-ac-badge-action']); ?>
    <?php echo e($getLabel()); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal70308eab0db7bee07ae0d7b141f6dc83)): ?>
<?php $attributes = $__attributesOriginal70308eab0db7bee07ae0d7b141f6dc83; ?>
<?php unset($__attributesOriginal70308eab0db7bee07ae0d7b141f6dc83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal70308eab0db7bee07ae0d7b141f6dc83)): ?>
<?php $component = $__componentOriginal70308eab0db7bee07ae0d7b141f6dc83; ?>
<?php unset($__componentOriginal70308eab0db7bee07ae0d7b141f6dc83); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\actions\resources\views\badge-action.blade.php ENDPATH**/ ?>