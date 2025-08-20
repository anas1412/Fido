<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'actions' => false,
    'actionsPosition' => null,
    'columns',
    'extraHeadingColumn' => false,
    'groupColumn' => null,
    'groupsOnly' => false,
    'heading',
    'placeholderColumns' => true,
    'query',
    'selectionEnabled' => false,
    'selectedState',
    'recordCheckboxPosition' => null,
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
    'heading',
    'placeholderColumns' => true,
    'query',
    'selectionEnabled' => false,
    'selectedState',
    'recordCheckboxPosition' => null,
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
?>

<?php if (isset($component)) { $__componentOriginalb06932e913f01497313cb0ed448cecad = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb06932e913f01497313cb0ed448cecad = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.row','data' => ['attributes' => 
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-ta-summary-row'])
    ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::row'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-ta-summary-row'])
    )]); ?>
    <?php if($placeholderColumns && $actions && in_array($actionsPosition, [ActionsPosition::BeforeCells, ActionsPosition::BeforeColumns])): ?>
        <td></td>
    <?php endif; ?>

    <?php if($placeholderColumns && $selectionEnabled && $recordCheckboxPosition === RecordCheckboxPosition::BeforeCells): ?>
        <td></td>
    <?php endif; ?>

    <?php if($extraHeadingColumn || $groupsOnly): ?>
        <?php if (isset($component)) { $__componentOriginal0582040fe960eff09c1461f7f86a8187 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0582040fe960eff09c1461f7f86a8187 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.cell','data' => ['class' => 'text-sm font-medium text-gray-950 dark:text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-sm font-medium text-gray-950 dark:text-white']); ?>
            <span class="fi-ta-summary-row-heading px-3 py-4">
                <?php echo e($heading); ?>

            </span>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0582040fe960eff09c1461f7f86a8187)): ?>
<?php $attributes = $__attributesOriginal0582040fe960eff09c1461f7f86a8187; ?>
<?php unset($__attributesOriginal0582040fe960eff09c1461f7f86a8187); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0582040fe960eff09c1461f7f86a8187)): ?>
<?php $component = $__componentOriginal0582040fe960eff09c1461f7f86a8187; ?>
<?php unset($__componentOriginal0582040fe960eff09c1461f7f86a8187); ?>
<?php endif; ?>
    <?php else: ?>
        <?php
            $headingColumnSpan = 1;

            foreach ($columns as $index => $column) {
                if ($index === array_key_first($columns)) {
                    continue;
                }

                if ($column->hasSummary($query)) {
                    break;
                }

                $headingColumnSpan++;
            }
        ?>
    <?php endif; ?>

    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(($loop->first || $extraHeadingColumn || $groupsOnly || ($loop->iteration > $headingColumnSpan)) && ($placeholderColumns || $column->hasSummary($query))): ?>
            <?php
                $alignment = $column->getAlignment() ?? Alignment::Start;

                if (! $alignment instanceof Alignment) {
                    $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
                }
            ?>

            <?php if (isset($component)) { $__componentOriginal0582040fe960eff09c1461f7f86a8187 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0582040fe960eff09c1461f7f86a8187 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-tables::components.cell','data' => ['colspan' => ($loop->first && (! $extraHeadingColumn) && (! $groupsOnly) && ($headingColumnSpan > 1)) ? $headingColumnSpan : null,'class' => \Illuminate\Support\Arr::toCssClasses([
                    match ($alignment) {
                        Alignment::Start => 'text-start',
                        Alignment::Center => 'text-center',
                        Alignment::End => 'text-end',
                        Alignment::Left => 'text-left',
                        Alignment::Right => 'text-right',
                        Alignment::Justify, Alignment::Between => 'text-justify',
                        default => $alignment,
                    },
                ])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-tables::cell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($loop->first && (! $extraHeadingColumn) && (! $groupsOnly) && ($headingColumnSpan > 1)) ? $headingColumnSpan : null),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Arr::toCssClasses([
                    match ($alignment) {
                        Alignment::Start => 'text-start',
                        Alignment::Center => 'text-center',
                        Alignment::End => 'text-end',
                        Alignment::Left => 'text-left',
                        Alignment::Right => 'text-right',
                        Alignment::Justify, Alignment::Between => 'text-justify',
                        default => $alignment,
                    },
                ]))]); ?>
                <?php if($loop->first && (! $extraHeadingColumn) && (! $groupsOnly)): ?>
                    <span
                        class="fi-ta-summary-row-heading flex px-3 py-4 text-sm font-medium text-gray-950 dark:text-white"
                    >
                        <?php echo e($heading); ?>

                    </span>
                <?php elseif((! $placeholderColumns) || $column->hasSummary($query)): ?>
                    <?php $__currentLoopData = $column->getSummarizers($query); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $summarizer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($summarizer->query($query)->selectedState($selectedState)); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0582040fe960eff09c1461f7f86a8187)): ?>
<?php $attributes = $__attributesOriginal0582040fe960eff09c1461f7f86a8187; ?>
<?php unset($__attributesOriginal0582040fe960eff09c1461f7f86a8187); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0582040fe960eff09c1461f7f86a8187)): ?>
<?php $component = $__componentOriginal0582040fe960eff09c1461f7f86a8187; ?>
<?php unset($__componentOriginal0582040fe960eff09c1461f7f86a8187); ?>
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
<?php /**PATH D:\Devs\Fido\vendor\filament\tables\resources\views\components\summary\row.blade.php ENDPATH**/ ?>