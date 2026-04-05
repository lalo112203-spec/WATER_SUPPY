<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Title('Appearance settings')] class extends Component {
    use WithFileUploads;

    public $background_image;
    public $messenger_background;
    public $text_color;

    public function mount() {
        $this->text_color = auth()->user()->text_color ?? '';
    }

    public function updatedBackgroundImage() {
        $user = auth()->user();
        if ($user->background_image && Storage::disk('public')->exists($user->background_image)) {
            Storage::disk('public')->delete($user->background_image);
        }
        $path = $this->background_image->store('backgrounds', 'public');
        $user->background_image = $path;
        $user->save();
        $this->background_image = null; // Clear from temporary component state
        session()->flash('status', 'Background image updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updatedMessengerBackground() {
        $user = auth()->user();
        if ($user->messenger_background && Storage::disk('public')->exists($user->messenger_background)) {
            Storage::disk('public')->delete($user->messenger_background);
        }
        $path = $this->messenger_background->store('messenger_backgrounds', 'public');
        $user->messenger_background = $path;
        $user->save();
        $this->messenger_background = null;
        session()->flash('status', 'Messenger background updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updatedTextColor() {
        $user = auth()->user();
        $user->text_color = $this->text_color;
        $user->save();
        session()->flash('status', 'Text color updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updateAppearance() {
        // Fallback for the manual submit button if they still use it
        $user = auth()->user();
        
        if ($this->background_image) {
            if ($user->background_image && Storage::disk('public')->exists($user->background_image)) {
                Storage::disk('public')->delete($user->background_image);
            }
            $path = $this->background_image->store('backgrounds', 'public');
            $user->background_image = $path;
        }
        
        if ($this->messenger_background) {
            if ($user->messenger_background && Storage::disk('public')->exists($user->messenger_background)) {
                Storage::disk('public')->delete($user->messenger_background);
            }
            $path2 = $this->messenger_background->store('messenger_backgrounds', 'public');
            $user->messenger_background = $path2;
        }
        
        $user->text_color = $this->text_color;
        $user->save();

        session()->flash('status', 'Appearance updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }
    
    public function removeBackground() {
        $user = auth()->user();
        if ($user->background_image && Storage::disk('public')->exists($user->background_image)) {
            Storage::disk('public')->delete($user->background_image);
        }
        $user->background_image = null;
        $user->save();
        session()->flash('status', 'Background removed successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function removeMessengerBackground() {
        $user = auth()->user();
        if ($user->messenger_background && Storage::disk('public')->exists($user->messenger_background)) {
            Storage::disk('public')->delete($user->messenger_background);
        }
        $user->messenger_background = null;
        $user->save();
        session()->flash('status', 'Messenger background removed successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }
}; ?>

<section class="w-full">
    @if(auth()->user()->background_image)
    <style>
        /* Glassmorphism for settings cards - ONLY in Custom Theme with Background */
        html.custom-theme [data-flux-main] section, 
        html.custom-theme main section,
        html.custom-theme .bg-white, 
        html.custom-theme .dark\:bg-gray-800,
        html.custom-theme .bg-slate-50,
        html.custom-theme .dark\:bg-slate-900\/50 {
            background-color: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
        }

        /* Glassmorphism for the appearance switcher */
        html.custom-theme [data-flux-radio-group][variant="segmented"] {
            background-color: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: none !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 4px !important;
            border-radius: 9999px !important;
        }

        html.custom-theme [data-flux-radio-group][variant="segmented"] [data-flux-radio] {
            color: #e2e8f0 !important;
            transition: all 0.3s ease !important;
            border-radius: 9999px !important;
        }

        html.custom-theme [data-flux-radio-group][variant="segmented"] [data-flux-radio][data-checked] {
            background-color: var(--flux-custom-text, rgba(56, 189, 248, 0.3)) !important;
            opacity: 0.5 !important; /* Make it slightly transparent so it's not too harsh if full red */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            color: #ffffff !important;
        }

        /* Make inputs more transparent */
        html.custom-theme input[type="file"],
        html.custom-theme input[type="color"] {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        html.custom-theme [data-flux-heading], 
        html.custom-theme [data-flux-subheading],
        html.custom-theme h1, 
        html.custom-theme h2, 
        html.custom-theme h3, 
        html.custom-theme p {
            color: var(--flux-custom-text, #f8fafc) !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4) !important;
        }
    </style>
    @endif
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="custom" icon="paint-brush">{{ __('Custom') }}</flux:radio>
        </flux:radio.group>

        <form wire:submit="updateAppearance" class="mt-8 space-y-6">
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-400 rounded-lg bg-green-500/10 border border-green-500/20" role="alert">
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

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:heading>{{ __('Messenger Background Image') }}</flux:heading>
                <flux:subheading>{{ __('Upload a custom background picture for your messenger.') }}</flux:subheading>
                
                @if(auth()->user()->messenger_background)
                    <div class="my-4">
                        <img src="{{ asset('storage/' . auth()->user()->messenger_background) }}" class="h-32 rounded shadow-sm border border-gray-200">
                        <button type="button" wire:click="removeMessengerBackground" class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors">{{ __('Remove Messenger Background') }}</button>
                    </div>
                @endif
                <input type="file" wire:model="messenger_background" class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" accept="image/*" />
                @error('messenger_background') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
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
