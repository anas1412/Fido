<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'actions' => false,
    'actionsPosition' => null,
    'columns',
    'extraHeadingColumn' => false,
    'groupColumn' => null,
    'groupsOnly' => false,
    'placeholderColumns' => true,
    'pluralModelLabel',
    'recordCheckboxPosition' => null,
    'records',
    'selectionEnabled' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'actions' => false,
    'actionsPosition' => null,
    'columns',
    'extraHeadingColumn' => false,
    'groupColumn' => null,
    'groupsOnly' => false,
    'placeholderColumns' => true,
    'pluralModelLabel',
    'recordCheckboxPosition' => null,
    'records',
    'selectionEnabled' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    use Filament\Support\Enums\Alignment;
    use Filament\Tables\Columns\Column;
    use Filament\Tables\Enums\ActionsPosition;
    use Filament\Tables\Enums\RecordCheckboxPosition;

    if ($groupsOnly && $groupColumn) {
        $columns = collect($columns)
            ->reject(fn (Column $column): bool => $column->getName() === $groupColumn)
            ->all();
    }

    $hasPageSummary = (! $groupsOnly) && $records instanceof \Illuminate\Contracts\Pagination\Paginator && $records->hasPages();

    $pageTableSummaryQuery = $hasPageSummary ? $this->getPageTableSummaryQuery() : null;
    $allTableSummaryQuery = $this->getAllTableSummaryQuery();
?>

<?php if($hasPageSummary): ?>
    <?php if (isset($component)) { $__componentOriginalb06932e913f01497313cb0ed448cecad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb06932e913f01497313cb0ed448cecad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.row','data' => ['class' => 'fi-ta-summary-header-row bg-gray-50 dark:bg-white/5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'fi-ta-summary-header-row bg-gray-50 dark:bg-white/5']); ?>
        <?php if($placeholderColumns && $actions && in_array($actionsPosition, [ActionsPosition::BeforeCells, ActionsPosition::BeforeColumns])): ?>
            <td></td>
        <?php endif; ?>

        <?php if($placeholderColumns && $selectionEnabled && $recordCheckboxPosition === RecordCheckboxPosition::BeforeCells): ?>
            <td></td>
        <?php endif; ?>

        <?php if($extraHeadingColumn): ?>
            <?php if (isset($component)) { $__componentOriginal8238907c07bba916740783b1ce58f509 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8238907c07bba916740783b1ce58f509 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.summary.header-cell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::summary.header-cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <?php echo e(__('filament-tables::table.summary.heading', ['label' => $pluralModelLabel])); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8238907c07bba916740783b1ce58f509)): ?>
<?php $attributes = $__attributesOriginal8238907c07bba916740783b1ce58f509; ?>
<?php unset($__attributesOriginal8238907c07bba916740783b1ce58f509); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8238907c07bba916740783b1ce58f509)): ?>
<?php $component = $__componentOriginal8238907c07bba916740783b1ce58f509; ?>
<?php unset($__componentOriginal8238907c07bba916740783b1ce58f509); ?>
<?php endif; ?>
        <?php endif; ?>

        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $columnHasSummary = ($pageTableSummaryQuery && $column->hasSummary($pageTableSummaryQuery)) || $column->hasSummary($allTableSummaryQuery);
            ?>

            <?php if($placeholderColumns || $columnHasSummary): ?>
                <?php
                    $alignment = $column->getAlignment() ?? Alignment::Start;

                    if (! $alignment instanceof Alignment) {
                        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
                    }

                    $hasColumnHeaderLabel = (! $placeholderColumns) || $columnHasSummary;
                ?>

                <?php if (isset($component)) { $__componentOriginal8238907c07bba916740783b1ce58f509 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8238907c07bba916740783b1ce58f509 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.summary.header-cell','data' => ['attributes' => 
                        \Filament\Support\prepare_inherited_attributes($column->getExtraHeaderAttributeBag())
                            ->class([
                                'whitespace-nowrap' => ! $column->isHeaderWrapped(),
                                'whitespace-normal' => $column->isHeaderWrapped(),
                                match ($alignment) {
                                    Alignment::Start => 'text-start',
                                    Alignment::Center => 'text-center',
                                    Alignment::End => 'text-end',
                                    Alignment::Left => 'text-left',
                                    Alignment::Right => 'text-right',
                                    Alignment::Justify, Alignment::Between => 'text-justify',
                                    default => $alignment,
                                } => (! ($loop->first && (! $extraHeadingColumn))) && $hasColumnHeaderLabel,
                            ])
                    ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::summary.header-cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
                        \Filament\Support\prepare_inherited_attributes($column->getExtraHeaderAttributeBag())
                            ->class([
                                'whitespace-nowrap' => ! $column->isHeaderWrapped(),
                                'whitespace-normal' => $column->isHeaderWrapped(),
                                match ($alignment) {
                                    Alignment::Start => 'text-start',
                                    Alignment::Center => 'text-center',
                                    Alignment::End => 'text-end',
                                    Alignment::Left => 'text-left',
                                    Alignment::Right => 'text-right',
                                    Alignment::Justify, Alignment::Between => 'text-justify',
                                    default => $alignment,
                                } => (! ($loop->first && (! $extraHeadingColumn))) && $hasColumnHeaderLabel,
                            ])
                    )]); ?>
                    <?php if($loop->first && (! $extraHeadingColumn)): ?>
                        <?php echo e(__('filament-tables::table.summary.heading', ['label' => $pluralModelLabel])); ?>

                    <?php elseif($hasColumnHeaderLabel): ?>
                        <?php echo e($column->getLabel()); ?>

                    <?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8238907c07bba916740783b1ce58f509)): ?>
<?php $attributes = $__attributesOriginal8238907c07bba916740783b1ce58f509; ?>
<?php unset($__attributesOriginal8238907c07bba916740783b1ce58f509); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8238907c07bba916740783b1ce58f509)): ?>
<?php $component = $__componentOriginal8238907c07bba916740783b1ce58f509; ?>
<?php unset($__componentOriginal8238907c07bba916740783b1ce58f509); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($placeholderColumns && $actions && in_array($actionsPosition, [ActionsPosition::AfterColumns, ActionsPosition::AfterCells])): ?>
            <td></td>
        <?php endif; ?>

        <?php if($placeholderColumns && $selectionEnabled && $recordCheckboxPosition === RecordCheckboxPosition::AfterCells): ?>
            <td></td>
        <?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb06932e913f01497313cb0ed448cecad)): ?>
<?php $attributes = $__attributesOriginalb06932e913f01497313cb0ed448cecad; ?>
<?php unset($__attributesOriginalb06932e913f01497313cb0ed448cecad); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb06932e913f01497313cb0ed448cecad)): ?>
<?php $component = $__componentOriginalb06932e913f01497313cb0ed448cecad; ?>
<?php unset($__componentOriginalb06932e913f01497313cb0ed448cecad); ?>
<?php endif; ?>

    <?php
        $selectedState = $this->getTableSummarySelectedState($pageTableSummaryQuery)[0] ?? [];
    ?>

    <?php if (isset($component)) { $__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.summary.row','data' => ['actions' => $actions,'actionsPosition' => $actionsPosition,'columns' => $columns,'extraHeadingColumn' => $extraHeadingColumn,'heading' => __('filament-tables::table.summary.subheadings.page', ['label' => $pluralModelLabel]),'placeholderColumns' => $placeholderColumns,'query' => $pageTableSummaryQuery,'recordCheckboxPosition' => $recordCheckboxPosition,'selectedState' => $selectedState,'selectionEnabled' => $selectionEnabled]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::summary.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actions),'actions-position' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actionsPosition),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns),'extra-heading-column' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($extraHeadingColumn),'heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('filament-tables::table.summary.subheadings.page', ['label' => $pluralModelLabel])),'placeholder-columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholderColumns),'query' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pageTableSummaryQuery),'record-checkbox-position' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordCheckboxPosition),'selected-state' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedState),'selection-enabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectionEnabled)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb)): ?>
<?php $attributes = $__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb; ?>
<?php unset($__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb)): ?>
<?php $component = $__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb; ?>
<?php unset($__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb); ?>
<?php endif; ?>
<?php endif; ?>

<?php
    $selectedState = $this->getTableSummarySelectedState($allTableSummaryQuery)[0] ?? [];
?>

<?php if (isset($component)) { $__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.summary.row','data' => ['actions' => $actions,'actionsPosition' => $actionsPosition,'columns' => $columns,'extraHeadingColumn' => $extraHeadingColumn,'groupsOnly' => $groupsOnly,'heading' => __(($hasPageSummary ? 'filament-tables::table.summary.subheadings.all' : 'filament-tables::table.summary.heading'), ['label' => $pluralModelLabel]),'placeholderColumns' => $placeholderColumns,'query' => $allTableSummaryQuery,'recordCheckboxPosition' => $recordCheckboxPosition,'selectedState' => $selectedState,'selectionEnabled' => $selectionEnabled,'class' => \Illuminate\Support\Arr::toCssClasses([
        'bg-gray-50 dark:bg-white/5' => ! $hasPageSummary,
    ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::summary.row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actions),'actions-position' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actionsPosition),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($columns),'extra-heading-column' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($extraHeadingColumn),'groups-only' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($groupsOnly),'heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__(($hasPageSummary ? 'filament-tables::table.summary.subheadings.all' : 'filament-tables::table.summary.heading'), ['label' => $pluralModelLabel])),'placeholder-columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($placeholderColumns),'query' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($allTableSummaryQuery),'record-checkbox-position' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($recordCheckboxPosition),'selected-state' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedState),'selection-enabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectionEnabled),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
        'bg-gray-50 dark:bg-white/5' => ! $hasPageSummary,
    ]))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb)): ?>
<?php $attributes = $__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb; ?>
<?php unset($__attributesOriginala3ad14087ab6b316cf1e1d1a634acbeb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb)): ?>
<?php $component = $__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb; ?>
<?php unset($__componentOriginala3ad14087ab6b316cf1e1d1a634acbeb); ?>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\components\summary\index.blade.php ENDPATH**/ ?>