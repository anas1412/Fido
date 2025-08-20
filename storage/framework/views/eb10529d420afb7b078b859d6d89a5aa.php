<?php
    $isContained = $isContained();
    $statePath = $getStatePath();
    $previousAction = $getAction('previous');
    $nextAction = $getAction('next');
?>

<div
    wire:ignore.self
    x-cloak
    x-data="{
        step: null,

        nextStep: function () {
            let nextStepIndex = this.getStepIndex(this.step) + 1

            if (nextStepIndex >= this.getSteps().length) {
                return
            }

            this.step = this.getSteps()[nextStepIndex]

            this.autofocusFields()
            this.scroll()
        },

        previousStep: function () {
            let previousStepIndex = this.getStepIndex(this.step) - 1

            if (previousStepIndex < 0) {
                return
            }

            this.step = this.getSteps()[previousStepIndex]

            this.autofocusFields()
            this.scroll()
        },

        scroll: function () {
            this.$nextTick(() => {
                this.$refs.header.children[
                    this.getStepIndex(this.step)
                ].scrollIntoView({ behavior: 'smooth', block: 'start' })
            })
        },

        autofocusFields: function () {
            $nextTick(() =>
                this.$refs[`step-${this.step}`]
                    .querySelector('[autofocus]')
                    ?.focus(),
            )
        },

        getStepIndex: function (step) {
            let index = this.getSteps().findIndex(
                (indexedStep) => indexedStep === step,
            )

            if (index === -1) {
                return 0
            }

            return index
        },

        getSteps: function () {
            return JSON.parse(this.$refs.stepsData.value)
        },

        isFirstStep: function () {
            return this.getStepIndex(this.step) <= 0
        },

        isLastStep: function () {
            return this.getStepIndex(this.step) + 1 >= this.getSteps().length
        },

        isStepAccessible: function (stepId) {
            return (
                <?php echo \Illuminate\Support\Js::from($isSkippable())->toHtml() ?> || this.getStepIndex(this.step) > this.getStepIndex(stepId)
            )
        },

        updateQueryString: function () {
            if (! <?php echo \Illuminate\Support\Js::from($isStepPersistedInQueryString())->toHtml() ?>) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(<?php echo \Illuminate\Support\Js::from($getStepQueryStringKey())->toHtml() ?>, this.step)

            history.pushState(null, document.title, url.toString())
        },
    }"
    x-init="
        $watch('step', () => updateQueryString())

        step = getSteps().at(<?php echo e($getStartStep() - 1); ?>)

        autofocusFields()
    "
    x-on:next-wizard-step.window="if ($event.detail.statePath === '<?php echo e($statePath); ?>') nextStep()"
    <?php echo e($attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'fi-fo-wizard',
                'fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => $isContained,
            ])); ?>

>
    <input
        type="hidden"
        value="<?php echo e(collect($getChildComponentContainer()->getComponents())
                ->filter(static fn (\Filament\Forms\Components\Wizard\Step $step): bool => $step->isVisible())
                ->map(static fn (\Filament\Forms\Components\Wizard\Step $step) => $step->getId())
                ->values()
                ->toJson()); ?>"
        x-ref="stepsData"
    />

    <ol
        <?php if(filled($label = $getLabel())): ?>
            aria-label="<?php echo e($label); ?>"
        <?php endif; ?>
        role="list"
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'fi-fo-wizard-header grid divide-y divide-gray-200 dark:divide-white/5 md:grid-flow-col md:divide-y-0 md:overflow-x-auto',
            'border-b border-gray-200 dark:border-white/10' => $isContained,
            'rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10' => ! $isContained,
        ]); ?>"
        x-ref="header"
    >
        <?php $__currentLoopData = $getChildComponentContainer()->getComponents(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li
                class="fi-fo-wizard-header-step relative flex"
                x-bind:class="{
                    'fi-active': getStepIndex(step) === <?php echo e($loop->index); ?>,
                    'fi-completed': getStepIndex(step) > <?php echo e($loop->index); ?>,
                }"
            >
                <button
                    type="button"
                    x-bind:aria-current="getStepIndex(step) === <?php echo e($loop->index); ?> ? 'step' : null"
                    x-on:click="step = <?php echo \Illuminate\Support\Js::from($step->getId())->toHtml() ?>"
                    x-bind:disabled="! isStepAccessible(<?php echo \Illuminate\Support\Js::from($step->getId())->toHtml() ?>) || <?php echo \Illuminate\Support\Js::from($previousAction->isDisabled())->toHtml() ?>"
                    role="step"
                    class="fi-fo-wizard-header-step-button flex h-full items-center gap-x-4 px-6 py-4 text-start"
                >
                    <div
                        class="fi-fo-wizard-header-step-icon-ctn flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                        x-bind:class="{
                            'bg-primary-600 dark:bg-primary-500':
                                getStepIndex(step) > <?php echo e($loop->index); ?>,
                            'border-2': getStepIndex(step) <= <?php echo e($loop->index); ?>,
                            'border-primary-600 dark:border-primary-500':
                                getStepIndex(step) === <?php echo e($loop->index); ?>,
                            'border-gray-300 dark:border-gray-600':
                                getStepIndex(step) < <?php echo e($loop->index); ?>,
                        }"
                    >
                        <?php
                            $completedIcon = $step->getCompletedIcon();
                        ?>

                        <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['alias' => filled($completedIcon) ? null : 'forms::components.wizard.completed-step','icon' => $completedIcon ?? 'heroicon-o-check','xCloak' => 'x-cloak','xShow' => 'getStepIndex(step) > '.e($loop->index).'','class' => 'fi-fo-wizard-header-step-icon h-6 w-6 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['alias' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(filled($completedIcon) ? null : 'forms::components.wizard.completed-step'),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($completedIcon ?? 'heroicon-o-check'),'x-cloak' => 'x-cloak','x-show' => 'getStepIndex(step) > '.e($loop->index).'','class' => 'fi-fo-wizard-header-step-icon h-6 w-6 text-white']); ?>
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

                        <?php if(filled($icon = $step->getIcon())): ?>
                            <?php if (isset($component)) { $__componentOriginalbfc641e0710ce04e5fe02876ffc6f950 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbfc641e0710ce04e5fe02876ffc6f950 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.icon','data' => ['icon' => $icon,'xCloak' => 'x-cloak','xShow' => 'getStepIndex(step) <= '.e($loop->index).'','class' => 'fi-fo-wizard-header-step-icon h-6 w-6','xBind:class' => '{
                                    \'text-gray-500 dark:text-gray-400\': getStepIndex(step) !== '.e($loop->index).',
                                    \'text-primary-600 dark:text-primary-500\': getStepIndex(step) === '.e($loop->index).',
                                }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon),'x-cloak' => 'x-cloak','x-show' => 'getStepIndex(step) <= '.e($loop->index).'','class' => 'fi-fo-wizard-header-step-icon h-6 w-6','x-bind:class' => '{
                                    \'text-gray-500 dark:text-gray-400\': getStepIndex(step) !== '.e($loop->index).',
                                    \'text-primary-600 dark:text-primary-500\': getStepIndex(step) === '.e($loop->index).',
                                }']); ?>
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
                        <?php else: ?>
                            <span
                                x-show="getStepIndex(step) <= <?php echo e($loop->index); ?>"
                                class="fi-fo-wizard-header-step-indicator text-sm font-medium"
                                x-bind:class="{
                                    'text-gray-500 dark:text-gray-400':
                                        getStepIndex(step) !== <?php echo e($loop->index); ?>,
                                    'text-primary-600 dark:text-primary-500':
                                        getStepIndex(step) === <?php echo e($loop->index); ?>,
                                }"
                            >
                                <?php echo e(str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT)); ?>

                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="grid justify-items-start md:w-max md:max-w-60">
                        <?php if(! $step->isLabelHidden()): ?>
                            <span
                                class="fi-fo-wizard-header-step-label text-sm font-medium"
                                x-bind:class="{
                                    'text-gray-500 dark:text-gray-400':
                                        getStepIndex(step) < <?php echo e($loop->index); ?>,
                                    'text-primary-600 dark:text-primary-400':
                                        getStepIndex(step) === <?php echo e($loop->index); ?>,
                                    'text-gray-950 dark:text-white': getStepIndex(step) > <?php echo e($loop->index); ?>,
                                }"
                            >
                                <?php echo e($step->getLabel()); ?>

                            </span>
                        <?php endif; ?>

                        <?php if(filled($description = $step->getDescription())): ?>
                            <span
                                class="fi-fo-wizard-header-step-description text-start text-sm text-gray-500 dark:text-gray-400"
                            >
                                <?php echo e($description); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </button>

                <?php if(! $loop->last): ?>
                    <div
                        aria-hidden="true"
                        class="fi-fo-wizard-header-step-separator absolute end-0 hidden h-full w-5 md:block"
                    >
                        <svg
                            fill="none"
                            preserveAspectRatio="none"
                            viewBox="0 0 22 80"
                            class="h-full w-full text-gray-200 dark:text-white/5 rtl:rotate-180"
                        >
                            <path
                                d="M0 -2L20 40L0 82"
                                stroke-linejoin="round"
                                stroke="currentcolor"
                                vector-effect="non-scaling-stroke"
                            ></path>
                        </svg>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>

    <?php $__currentLoopData = $getChildComponentContainer()->getComponents(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e($step); ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div
        class="<?php echo \Illuminate\Support\Arr::toCssClasses([
            'flex items-center justify-between gap-x-3',
            'px-6 pb-6' => $isContained,
            'mt-6' => ! $isContained,
        ]); ?>"
    >
        <span
            x-cloak
            <?php if(! $previousAction->isDisabled()): ?>
                x-on:click="previousStep"
            <?php endif; ?>
            x-show="! isFirstStep()"
        >
            <?php echo e($previousAction); ?>

        </span>

        <span x-show="isFirstStep()">
            <?php echo e($getCancelAction()); ?>

        </span>

        <span
            x-cloak
            <?php if(! $nextAction->isDisabled()): ?>
                x-on:click="
                    $wire.dispatchFormEvent(
                        'wizard::nextStep',
                        '<?php echo e($statePath); ?>',
                        getStepIndex(step),
                    )
                "
            <?php endif; ?>
            x-bind:class="{ 'hidden': isLastStep(), 'block': ! isLastStep() }"
            wire:loading.class="pointer-events-none opacity-70"
        >
            <?php echo e($nextAction); ?>

        </span>

        <span
            x-bind:class="{ 'hidden': ! isLastStep(), 'block': isLastStep() }"
        >
            <?php echo e($getSubmitAction()); ?>

        </span>
    </div>
</div>
<?php /**PATH D:\Devs\Fido\vendor\filament\forms\resources\views\components\wizard.blade.php ENDPATH**/ ?>