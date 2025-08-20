<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => ['class' => \Illuminate\Support\Arr::toCssClasses([
        'fi-resource-manage-related-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
        'fi-resource-manage-related-records-page',
        'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    ]))]); ?>
    <?php if($this->table->getColumns()): ?>
        <div class="flex flex-col gap-y-6">
            <?php if (isset($component)) { $__componentOriginale77d85bd24d039fa58cc32119f1d9bc5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale77d85bd24d039fa58cc32119f1d9bc5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.resources.tabs','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::resources.tabs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale77d85bd24d039fa58cc32119f1d9bc5)): ?>
<?php $attributes = $__attributesOriginale77d85bd24d039fa58cc32119f1d9bc5; ?>
<?php unset($__attributesOriginale77d85bd24d039fa58cc32119f1d9bc5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale77d85bd24d039fa58cc32119f1d9bc5)): ?>
<?php $component = $__componentOriginale77d85bd24d039fa58cc32119f1d9bc5; ?>
<?php unset($__componentOriginale77d85bd24d039fa58cc32119f1d9bc5); ?>
<?php endif; ?>

            <?php echo e(\Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_MANAGE_RELATED_RECORDS_TABLE_BEFORE, scopes: $this->getRenderHookScopes())); ?>


            <?php echo e($this->table); ?>


            <?php echo e(\Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::RESOURCE_PAGES_MANAGE_RELATED_RECORDS_TABLE_AFTER, scopes: $this->getRenderHookScopes())); ?>

        </div>
    <?php endif; ?>

    <?php if(count($relationManagers = $this->getRelationManagers())): ?>
        <?php if (isset($component)) { $__componentOriginal66235374c4c55de4d5fac61c84f69826 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66235374c4c55de4d5fac61c84f69826 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.resources.relation-managers','data' => ['activeLocale' => isset($activeLocale) ? $activeLocale : null,'activeManager' => $this->activeRelationManager ?? array_key_first($relationManagers),'managers' => $relationManagers,'ownerRecord' => $record,'pageClass' => static::class]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::resources.relation-managers'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['active-locale' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(isset($activeLocale) ? $activeLocale : null),'active-manager' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->activeRelationManager ?? array_key_first($relationManagers)),'managers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($relationManagers),'owner-record' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($record),'page-class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(static::class)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66235374c4c55de4d5fac61c84f69826)): ?>
<?php $attributes = $__attributesOriginal66235374c4c55de4d5fac61c84f69826; ?>
<?php unset($__attributesOriginal66235374c4c55de4d5fac61c84f69826); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66235374c4c55de4d5fac61c84f69826)): ?>
<?php $component = $__componentOriginal66235374c4c55de4d5fac61c84f69826; ?>
<?php unset($__componentOriginal66235374c4c55de4d5fac61c84f69826); ?>
<?php endif; ?>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\filament\resources\views\resources\pages\manage-related-records.blade.php ENDPATH**/ ?>