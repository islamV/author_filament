<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="flex justify-start mt-6">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                {{ __('notifications.send_button') }}
            </x-filament::button>
        </div>
    </form>
</x-filament::page>