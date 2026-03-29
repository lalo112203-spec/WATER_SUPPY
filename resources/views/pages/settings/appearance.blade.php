<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

new #[Title('Appearance settings')] class extends Component {
    use WithFileUploads;

    public $background_image;
    public $text_color;

    public function mount() {
        $this->text_color = auth()->user()->text_color ?? '';
    }

    public function updateAppearance() {
        $user = auth()->user();
        
        if ($this->background_image) {
            $path = $this->background_image->store('backgrounds', 'public');
            $user->background_image = $path;
        }
        
        $user->text_color = $this->text_color;
        $user->save();

        session()->flash('status', 'Appearance updated successfully.');
        $this->redirectIntended(route('settings.appearance'));
    }
    
    public function removeBackground() {
        $user = auth()->user();
        $user->background_image = null;
        $user->save();
        session()->flash('status', 'Background removed successfully.');
        $this->redirectIntended(route('settings.appearance'));
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>

        <form wire:submit="updateAppearance" class="mt-8 space-y-6">
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-2">
                <flux:heading>{{ __('Custom Background Image') }}</flux:heading>
                <flux:subheading>{{ __('Upload a custom background picture for your account dashboard.') }}</flux:subheading>
                
                @if(auth()->user()->background_image)
                    <div class="my-4">
                        <img src="{{ asset('storage/' . auth()->user()->background_image) }}" class="h-32 rounded shadow-sm border border-gray-200">
                        <button type="button" wire:click="removeBackground" class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors">{{ __('Remove Background') }}</button>
                    </div>
                @endif
                <input type="file" wire:model="background_image" class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" accept="image/*" />
                @error('background_image') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 mt-6">
                <flux:heading>{{ __('Custom Text Color') }}</flux:heading>
                <flux:subheading>{{ __('Choose a default text color for your dashboard (leave empty for default).') }}</flux:subheading>
                <div class="flex items-center gap-4">
                    <input type="color" wire:model="text_color" class="h-10 w-20 cursor-pointer rounded border border-gray-300">
                    @if($text_color)
                        <button type="button" wire:click="$set('text_color', '')" class="text-sm text-gray-500">{{ __('Reset to default') }}</button>
                    @endif
                </div>
                @error('text_color') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <flux:button type="submit" variant="primary">{{ __('Save Appearance') }}</flux:button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
