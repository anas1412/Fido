@if(auth()->check() && auth()->user()->is_demo)
    <div class="text-center py-2 font-bold" style="background-color: #d4bf47ff !important; color: #000;">
        {{ __('You are in Demo Mode. Adding, editing and removing are disabled.') }}
    </div>
@endif