<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'src',
    'alt' => '',
    'circular' => false,
    'switch' => false,
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
    'src',
    'alt' => '',
    'circular' => false,
    'switch' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<img
    src="<?php echo e($src); ?>"
    <?php echo e($attributes
        ->class([
            'object-cover object-center',
            'rounded-full' => $circular,
            'rounded-lg' => ! $circular && ! $switch,
            'rounded-md' => ! $circular && $switch,
        ])
        ->merge(['alt' => $alt])); ?>

/>
<?php /**PATH D:\Devs\Fido\vendor\bezhansalleh\filament-language-switch\resources\views\components\flag.blade.php ENDPATH**/ ?>