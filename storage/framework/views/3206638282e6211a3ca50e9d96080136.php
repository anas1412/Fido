<?php
    $currentTenant = filament()->getTenant();
    $currentTenantName = filament()->getTenantName($currentTenant);
    $items = filament()->getTenantMenuItems();

    $billingItem = $items['billing'] ?? null;
    $billingItemUrl = $billingItem?->getUrl();
    $isBillingItemVisible = $billingItem?->isVisible() ?? true;
    $hasBillingItem = (filament()->hasTenantBilling() || filled($billingItemUrl)) && $isBillingItemVisible;

    $registrationItem = $items['register'] ?? null;
    $registrationItemUrl = $registrationItem?->getUrl();
    $isRegistrationItemVisible = $registrationItem?->isVisible() ?? true;
    $hasRegistrationItem = ((filament()->hasTenantRegistration() && filament()->getTenantRegistrationPage()::canView()) || filled($registrationItemUrl)) && $isRegistrationItemVisible;

    $profileItem = $items['profile'] ?? null;
    $profileItemUrl = $profileItem?->getUrl();
    $isProfileItemVisible = $profileItem?->isVisible() ?? true;
    $hasProfileItem = ((filament()->hasTenantProfile() && filament()->getTenantProfilePage()::canView($currentTenant)) || filled($profileItemUrl)) && $isProfileItemVisible;

    $canSwitchTenants = count($tenants = array_filter(
        filament()->getUserTenants(filament()->auth()->user()),
        fn (\Illuminate\Database\Eloquent\Model $tenant): bool => ! $tenant->is($currentTenant),
    ));

    $items = \Illuminate\Support\Arr::except($items, ['billing', 'profile', 'register']);
?>

<?php echo e(\Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TENANT_MENU_BEFORE)); ?>


<?php if (isset($component)) { $__componentOriginal22ab0dbc2c6619d5954111bba06f01db = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal22ab0dbc2c6619d5954111bba06f01db = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.index','data' => ['placement' => 'bottom-start','size' => true,'teleport' => true,'attributes' => 
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-tenant-menu'])
    ]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['placement' => 'bottom-start','size' => true,'teleport' => true,'attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-tenant-menu'])
    )]); ?>
     <?php $__env->slot('trigger', null, []); ?> 
        <button
            <?php if(filament()->isSidebarCollapsibleOnDesktop()): ?>
                x-data="{ tooltip: false }"
                x-effect="
                    tooltip = $store.sidebar.isOpen
                        ? false
                        : {
                              content: <?php echo \Illuminate\Support\Js::from($currentTenantName)->toHtml() ?>,
                              placement: document.dir === 'rtl' ? 'left' : 'right',
                              theme: $store.theme,
                          }
                "
                x-tooltip.html="tooltip"
            <?php endif; ?>
            type="button"
            class="fi-tenant-menu-trigger group flex w-full items-center justify-center gap-x-3 rounded-lg p-2 text-sm font-medium outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
        >
            <?php if (isset($component)) { $__componentOriginale6928a7cc4d74387315a46a9adb2eaaa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale6928a7cc4d74387315a46a9adb2eaaa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.avatar.tenant','data' => ['tenant' => $currentTenant,'class' => 'shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::avatar.tenant'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tenant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($currentTenant),'class' => 'shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale6928a7cc4d74387315a46a9adb2eaaa)): ?>
<?php $attributes = $__attributesOriginale6928a7cc4d74387315a46a9adb2eaaa; ?>
<?php unset($__attributesOriginale6928a7cc4d74387315a46a9adb2eaaa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale6928a7cc4d74387315a46a9adb2eaaa)): ?>
<?php $component = $__componentOriginale6928a7cc4d74387315a46a9adb2eaaa; ?>
<?php unset($__componentOriginale6928a7cc4d74387315a46a9adb2eaaa); ?>
<?php endif; ?>

            <span
                <?php if(filament()->isSidebarCollapsibleOnDesktop()): ?>
                    x-show="$store.sidebar.isOpen"
                <?php endif; ?>
                class="grid justify-items-start text-start"
            >
                <?php if($currentTenant instanceof \Filament\Models\Contracts\HasCurrentTenantLabel): ?>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        <?php echo e($currentTenant->getCurrentTenantLabel()); ?>

                    </span>
                <?php endif; ?>

                <span class="text-gray-950 dark:text-white">
                    <?php echo e($currentTenantName); ?>

                </span>
            </span>

            <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => 'heroicon-m-chevron-down','alias' => 'panels::tenant-menu.toggle-button','xShow' => filament()->isSidebarCollapsibleOnDesktop() ? '$store.sidebar.isOpen' : null,'class' => 'ms-auto h-5 w-5 shrink-0 text-gray-400 transition duration-75 group-hover:text-gray-500 group-focus-visible:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400 dark:group-focus-visible:text-gray-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'heroicon-m-chevron-down','alias' => 'panels::tenant-menu.toggle-button','x-show' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filament()->isSidebarCollapsibleOnDesktop() ? '$store.sidebar.isOpen' : null),'class' => 'ms-auto h-5 w-5 shrink-0 text-gray-400 transition duration-75 group-hover:text-gray-500 group-focus-visible:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400 dark:group-focus-visible:text-gray-400']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $attributes = $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950)): ?>
<?php $component = $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950; ?>
<?php unset($__componentOriginalbfc641e0710ce04e5fe02876ffc6f950); ?>
<?php endif; ?>
        </button>
     <?php $__env->endSlot(); ?>

    <?php if($hasProfileItem || $hasBillingItem): ?>
        <?php if (isset($component)) { $__componentOriginal66687bf0670b9e16f61e667468dc8983 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66687bf0670b9e16f61e667468dc8983 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if($hasProfileItem): ?>
                <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['color' => $profileItem?->getColor(),'href' => $profileItemUrl ?? filament()->getTenantProfileUrl(),'icon' => $profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.profile-button') ?? 'heroicon-m-cog-6-tooth','tag' => 'a','target' => ($profileItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profileItem?->getColor()),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profileItemUrl ?? filament()->getTenantProfileUrl()),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($profileItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.profile-button') ?? 'heroicon-m-cog-6-tooth'),'tag' => 'a','target' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($profileItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null)]); ?>
                    <?php echo e($profileItem?->getLabel() ?? filament()->getTenantProfilePage()::getLabel()); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
            <?php endif; ?>

            <?php if($hasBillingItem): ?>
                <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['color' => $billingItem?->getColor() ?? 'gray','href' => $billingItemUrl ?? filament()->getTenantBillingUrl(),'icon' => $billingItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.billing-button') ?? 'heroicon-m-credit-card','tag' => 'a','target' => ($billingItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($billingItem?->getColor() ?? 'gray'),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($billingItemUrl ?? filament()->getTenantBillingUrl()),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($billingItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.billing-button') ?? 'heroicon-m-credit-card'),'tag' => 'a','target' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($billingItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null)]); ?>
                    <?php echo e($billingItem?->getLabel() ?? __('filament-panels::layout.actions.billing.label')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $attributes = $__attributesOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__attributesOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $component = $__componentOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__componentOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
    <?php endif; ?>

    <?php if(count($items)): ?>
        <?php if (isset($component)) { $__componentOriginal66687bf0670b9e16f61e667468dc8983 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66687bf0670b9e16f61e667468dc8983 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $itemPostAction = $item->getPostAction();
                ?>

                <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['action' => $itemPostAction,'color' => $item->getColor(),'href' => $item->getUrl(),'icon' => $item->getIcon(),'method' => filled($itemPostAction) ? 'post' : null,'tag' => filled($itemPostAction) ? 'form' : 'a','target' => $item->shouldOpenUrlInNewTab() ? '_blank' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($itemPostAction),'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->getColor()),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->getUrl()),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->getIcon()),'method' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filled($itemPostAction) ? 'post' : null),'tag' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filled($itemPostAction) ? 'form' : 'a'),'target' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->shouldOpenUrlInNewTab() ? '_blank' : null)]); ?>
                    <?php echo e($item->getLabel()); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $attributes = $__attributesOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__attributesOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $component = $__componentOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__componentOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
    <?php endif; ?>

    <?php if($canSwitchTenants): ?>
        <?php if (isset($component)) { $__componentOriginal66687bf0670b9e16f61e667468dc8983 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66687bf0670b9e16f61e667468dc8983 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['href' => filament()->getUrl($tenant),'image' => filament()->getTenantAvatarUrl($tenant),'tag' => 'a']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filament()->getUrl($tenant)),'image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filament()->getTenantAvatarUrl($tenant)),'tag' => 'a']); ?>
                    <?php echo e(filament()->getTenantName($tenant)); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $attributes = $__attributesOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__attributesOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $component = $__componentOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__componentOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
    <?php endif; ?>

    <?php if($hasRegistrationItem): ?>
        <?php if (isset($component)) { $__componentOriginal66687bf0670b9e16f61e667468dc8983 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal66687bf0670b9e16f61e667468dc8983 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <?php if (isset($component)) { $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.dropdown.list.item','data' => ['color' => $registrationItem?->getColor(),'href' => $registrationItemUrl ?? filament()->getTenantRegistrationUrl(),'icon' => $registrationItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.registration-button') ?? 'heroicon-m-plus','tag' => 'a','target' => ($registrationItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::dropdown.list.item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($registrationItem?->getColor()),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($registrationItemUrl ?? filament()->getTenantRegistrationUrl()),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($registrationItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::tenant-menu.registration-button') ?? 'heroicon-m-plus'),'tag' => 'a','target' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(($registrationItem?->shouldOpenUrlInNewTab() ?? false) ? '_blank' : null)]); ?>
                <?php echo e($registrationItem?->getLabel() ?? filament()->getTenantRegistrationPage()::getLabel()); ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $attributes = $__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__attributesOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78)): ?>
<?php $component = $__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78; ?>
<?php unset($__componentOriginal1bd4d8e254cc40cdb05bd99df3e63f78); ?>
<?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $attributes = $__attributesOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__attributesOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal66687bf0670b9e16f61e667468dc8983)): ?>
<?php $component = $__componentOriginal66687bf0670b9e16f61e667468dc8983; ?>
<?php unset($__componentOriginal66687bf0670b9e16f61e667468dc8983); ?>
<?php endif; ?>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal22ab0dbc2c6619d5954111bba06f01db)): ?>
<?php $attributes = $__attributesOriginal22ab0dbc2c6619d5954111bba06f01db; ?>
<?php unset($__attributesOriginal22ab0dbc2c6619d5954111bba06f01db); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal22ab0dbc2c6619d5954111bba06f01db)): ?>
<?php $component = $__componentOriginal22ab0dbc2c6619d5954111bba06f01db; ?>
<?php unset($__componentOriginal22ab0dbc2c6619d5954111bba06f01db); ?>
<?php endif; ?>

<?php echo e(\Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TENANT_MENU_AFTER)); ?>

<?php /**PATH D:\Devs\Fido\vendor\filament\filament\resources\views\components\tenant-menu.blade.php ENDPATH**/ ?>