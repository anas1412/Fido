<?php
    $languageSwitch = \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $languageSwitch->getOutsidePanelPlacement()->value;
    $placement = match(true){
        $outsidePanelsPlacement === 'top-center' && $isFlagsOnly => 'bottom',
        $outsidePanelsPlacement === 'bottom-center' && $isFlagsOnly => 'top',
        ! $isVisibleOutsidePanels && $isFlagsOnly=> 'bottom',
        default => 'bottom-end',
    };
    $maxHeight = $languageSwitch->getMaxHeight();
?>
<div>
    <style>
        .flags-only {
            max-width: 3rem !important;
        }

        .fls-dropdown-width {
            max-width: fit-content !important;
        }
    </style>
    <!--[if BLOCK]><![endif]--><?php if($isVisibleOutsidePanels): ?>
        <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fls-display-on fixed w-fit flex p-4 z-50',
            'top-0' => str_contains($outsidePanelsPlacement, 'top'),
            'bottom-0' => str_contains($outsidePanelsPlacement, 'bottom'),
            'justify-start' => str_contains($outsidePanelsPlacement, 'left'),
            'justify-end' => str_contains($outsidePanelsPlacement, 'right'),
            'justify-center' => str_contains($outsidePanelsPlacement, 'center'),
        ]); ?>">
            <div class="rounded-lg bg-gray-50 dark:bg-gray-950">
                <?php echo $__env->make('filament-language-switch::switch', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('filament-language-switch::switch', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH D:\Devs\Fido\vendor\bezhansalleh\filament-language-switch\resources\views/language-switch.blade.php ENDPATH**/ ?>