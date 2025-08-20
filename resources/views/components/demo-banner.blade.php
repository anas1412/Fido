@if(auth()->check() && auth()->user()->is_demo)
    <div style="background-color: #d4bf47ff !important;" class="bg-warning-600 text-center py-2 font-bold">
        {{ __('You are in Demo Mode. Adding, editing, and removing are disabled.') }}
    </div>
@endif
