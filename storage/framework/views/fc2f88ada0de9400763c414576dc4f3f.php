<?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
        <div class="flex items-start justify-between">
            <!-- Project Title and Version on the Left -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fido Project</h1>
                <p class="text-sm text-gray-500"><?php echo e($version); ?></p>
            </div>

            <!-- Documentation and GitHub Buttons on the Right -->
            <div class="flex flex-col gap-y-1">
                <!-- Documentation Button -->
                <a href="https://github.com/anas1412/fido" target="_blank"
                    class="flex items-center px-0 py-0 font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 20l9-5-9-5-9 5 9 5zM12 12V4m0 8l6.16 3.422A2 2 0 0118 17.09v3.82a2 2 0 01-2.84 1.836L12 20" />
                    </svg>
                    Documentation
                </a>

                <!-- GitHub Button -->
                <a href="https://github.com/anas1412/" target="_blank"
                    class="flex items-center px-0 py-0 font-semibold text-white bg-gray-800 rounded hover:bg-gray-900">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 .5A11.5 11.5 0 0 0 .5 12a11.5 11.5 0 0 0 7.86 11c.58.1.78-.25.78-.55v-2.08c-3.18.7-3.88-1.53-3.88-1.53-.52-1.33-1.28-1.69-1.28-1.69-1.05-.72.08-.71.08-.71 1.16.08 1.77 1.19 1.77 1.19 1.03 1.76 2.7 1.26 3.36.97.1-.75.4-1.26.73-1.55-2.54-.29-5.21-1.28-5.21-5.68 0-1.26.44-2.3 1.16-3.11-.12-.29-.5-1.46.11-3.04 0 0 .95-.31 3.12 1.19a10.8 10.8 0 0 1 5.68 0c2.16-1.5 3.12-1.19 3.12-1.19.61 1.58.23 2.75.11 3.04.72.81 1.16 1.85 1.16 3.11 0 4.42-2.67 5.39-5.22 5.67.4.34.76 1.01.76 2.05v3.04c0 .3.2.66.78.55A11.5 11.5 0 0 0 12 .5z" />
                    </svg>
                    GitHub
                </a>
            </div>
        </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\resources\views/filament/widgets/project-info-widget.blade.php ENDPATH**/ ?>