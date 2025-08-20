<?php if(auth()->check() && auth()->user()->is_demo): ?>
    <div style="background-color: #d4bf47ff !important;" class="bg-warning-600 text-center py-2 font-bold">
        <?php echo e(__('You are in Demo Mode. Adding, editing, and removing are disabled.')); ?>

    </div>
<?php endif; ?>
<?php /**PATH D:\Devs\Fido\resources\views\components\demo-banner.blade.php ENDPATH**/ ?>