<?php if (isset($component)) { $__componentOriginalbdee036326cbc931a2e3bf686403ecb7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbdee036326cbc931a2e3bf686403ecb7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-actions::components.group','data' => ['badge' => $getBadge(),'badgeColor' => $getBadgeColor(),'dynamicComponent' => 'filament::dropdown.list.item','group' => $group,'icon' => $getGroupedIcon(),'class' => 'fi-ac-grouped-group']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-actions::group'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['badge' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getBadge()),'badge-color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getBadgeColor()),'dynamic-component' => 'filament::dropdown.list.item','group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($getGroupedIcon()),'class' => 'fi-ac-grouped-group']); ?>
    <?php echo e($getLabel()); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbdee036326cbc931a2e3bf686403ecb7)): ?>
<?php $attributes = $__attributesOriginalbdee036326cbc931a2e3bf686403ecb7; ?>
<?php unset($__attributesOriginalbdee036326cbc931a2e3bf686403ecb7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbdee036326cbc931a2e3bf686403ecb7)): ?>
<?php $component = $__componentOriginalbdee036326cbc931a2e3bf686403ecb7; ?>
<?php unset($__componentOriginalbdee036326cbc931a2e3bf686403ecb7); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\actions\resources\views\grouped-group.blade.php ENDPATH**/ ?>