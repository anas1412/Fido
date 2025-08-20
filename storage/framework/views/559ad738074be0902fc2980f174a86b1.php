<nav class="-mx-3 flex flex-1 justify-end">
    <?php if(auth()->guard()->check()): ?>
        <a
            href="<?php echo e(url('/dashboard')); ?>"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            Dashboard
        </a>
    <?php else: ?>
        <a
            href="<?php echo e(route('login')); ?>"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            Log in
        </a>

        <?php if(Route::has('register')): ?>
            <a
                href="<?php echo e(route('register')); ?>"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Register
            </a>
        <?php endif; ?>
    <?php endif; ?>
</nav><?php /**PATH D:\Devs\Fido\resources\views\livewire\welcome\navigation.blade.php ENDPATH**/ ?>