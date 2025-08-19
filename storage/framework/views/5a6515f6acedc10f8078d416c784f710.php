<?php if (isset($component)) { $__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.demo-banner','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('demo-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c)): ?>
<?php $attributes = $__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c; ?>
<?php unset($__attributesOriginal6dc4d714d22675e23fa4e9295e23ab4c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c)): ?>
<?php $component = $__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c; ?>
<?php unset($__componentOriginal6dc4d714d22675e23fa4e9295e23ab4c); ?>
<?php endif; ?><?php /**PATH D:\Devs\Fido\storage\framework\views/a36d7b46996cb48a74d7e753a950a93a.blade.php ENDPATH**/ ?>