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
    public $text_stroke_color;
    public $text_stroke_width;
    public $font_family;
    public $background_color;
    public $appearance;
    public $background_url;
    public $messenger_background_url;

    public function mount() {
        $this->text_color = auth()->user()->text_color ?? '';
        $this->text_stroke_color = auth()->user()->text_stroke_color ?? '';
        $this->text_stroke_width = auth()->user()->text_stroke_width ?? '';
        $this->font_family = auth()->user()->font_family ?? '';
        $this->background_color = auth()->user()->background_color ?? '';
        $this->appearance = auth()->user()->appearance ?? 'light';
        $this->background_url = auth()->user()->background_url ?? '';
        $this->messenger_background_url = auth()->user()->messenger_background_url ?? '';
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

    public function updatedTextStrokeColor() {
        $user = auth()->user();
        $user->text_stroke_color = $this->text_stroke_color;
        $user->save();
        session()->flash('status', 'Text stroke color updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updatedTextStrokeWidth() {
        $user = auth()->user();
        $user->text_stroke_width = $this->text_stroke_width;
        $user->save();
        session()->flash('status', 'Text stroke width updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updatedFontFamily() {
        $user = auth()->user();
        $user->font_family = $this->font_family;
        $user->save();
        session()->flash('status', 'Font family updated successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function updatedBackgroundColor() {
        $user = auth()->user();
        $user->background_color = $this->background_color;
        $user->save();
        session()->flash('status', 'Background color updated successfully.');
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
        $user->text_stroke_color = $this->text_stroke_color;
        $user->text_stroke_width = $this->text_stroke_width;
        $user->font_family = $this->font_family;
        $user->background_color = $this->background_color;
        $user->appearance = $this->appearance;
        $user->background_url = $this->background_url;
        $user->messenger_background_url = $this->messenger_background_url;
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
        $user->background_url = null;
        $user->save();
        $this->background_url = '';
        session()->flash('status', 'Background removed successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }

    public function removeMessengerBackground() {
        $user = auth()->user();
        if ($user->messenger_background && Storage::disk('public')->exists($user->messenger_background)) {
            Storage::disk('public')->delete($user->messenger_background);
        }
        $user->messenger_background = null;
        $user->messenger_background_url = null;
        $user->save();
        $this->messenger_background_url = '';
        session()->flash('status', 'Messenger background removed successfully.');
        $this->redirectIntended(route('appearance.edit'));
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Appearance')">
        <flux:radio.group x-data variant="segmented" wire:model.live="appearance" x-init="$watch('$flux.appearance', value => $wire.set('appearance', value))">
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
                
                @if(auth()->user()->background_image || auth()->user()->background_url)
                    <div class="my-4">
                        <img src="{{ auth()->user()->background_url ?: asset('storage/' . auth()->user()->background_image) }}" class="h-32 rounded shadow-sm border border-gray-200">
                        <button type="button" wire:click="removeBackground" class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors">{{ __('Remove Background') }}</button>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">{{ __('Upload File') }}</label>
                        <input type="file" wire:model="background_image" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" accept="image/*" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">{{ __('Or Paste URL') }}</label>
                        <flux:input wire:model.blur="background_url" placeholder="https://example.com/image.jpg" />
                    </div>
                </div>
                @error('background_image') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:heading>{{ __('Messenger Background Image') }}</flux:heading>
                
                @if(auth()->user()->messenger_background || auth()->user()->messenger_background_url)
                    <div class="my-4">
                        <img src="{{ auth()->user()->messenger_background_url ?: asset('storage/' . auth()->user()->messenger_background) }}" class="h-32 rounded shadow-sm border border-gray-200">
                        <button type="button" wire:click="removeMessengerBackground" class="mt-2 text-sm text-red-600 hover:text-red-800 transition-colors">{{ __('Remove Messenger Background') }}</button>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">{{ __('Upload File') }}</label>
                        <input type="file" wire:model="messenger_background" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" accept="image/*" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">{{ __('Or Paste URL') }}</label>
                        <flux:input wire:model.blur="messenger_background_url" placeholder="https://example.com/chat-bg.jpg" />
                    </div>
                </div>
                @error('messenger_background') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:heading>{{ __('Custom Background Color') }}</flux:heading>
                <div class="flex items-center gap-4">
                    <input type="color" wire:model.blur="background_color" class="h-10 w-20 cursor-pointer rounded border border-gray-300">
                    @if($background_color)
                        <button type="button" wire:click="$set('background_color', '')" class="text-sm text-gray-500">{{ __('Reset to default') }}</button>
                    @endif
                </div>
                @error('background_color') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:heading>{{ __('Custom Text Color') }}</flux:heading>
                <div class="flex items-center gap-4">
                    <input type="color" wire:model.blur="text_color" class="h-10 w-20 cursor-pointer rounded border border-gray-300">
                    @if($text_color)
                        <button type="button" wire:click="$set('text_color', '')" class="text-sm text-gray-500">{{ __('Reset to default') }}</button>
                    @endif
                </div>
                @error('text_color') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="space-y-2">
                    <flux:heading>{{ __('Text Stroke Color') }}</flux:heading>
                    <div class="flex items-center gap-4">
                        <input type="color" wire:model.blur="text_stroke_color" class="h-10 w-20 cursor-pointer rounded border border-gray-300">
                        @if($text_stroke_color)
                            <button type="button" wire:click="$set('text_stroke_color', '')" class="text-sm text-gray-500">{{ __('Reset') }}</button>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <flux:heading>{{ __('Text Stroke Width') }}</flux:heading>
                    <flux:select wire:model.blur="text_stroke_width" placeholder="Select width...">
                        <flux:select.option value="">None</flux:select.option>
                        <flux:select.option value="0.5px">0.5px</flux:select.option>
                        <flux:select.option value="1px">1px</flux:select.option>
                        <flux:select.option value="1.5px">1.5px</flux:select.option>
                        <flux:select.option value="2px">2px</flux:select.option>
                        <flux:select.option value="3px">3px</flux:select.option>
                    </flux:select>
                </div>
            </div>

            <div class="space-y-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:heading>{{ __('Font Family') }}</flux:heading>
                <flux:select wire:model.blur="font_family" placeholder="Select font...">
                    <flux:select.option value="">Default (Inter)</flux:select.option>
                    <flux:select.option value="Arial, sans-serif">Arial</flux:select.option>
                    <flux:select.option value="'Times New Roman', serif">Times New Roman</flux:select.option>
                    <flux:select.option value="'Courier New', monospace">Courier New</flux:select.option>
                    <flux:select.option value="Georgia, serif">Georgia</flux:select.option>
                    <flux:select.option value="Verdana, sans-serif">Verdana</flux:select.option>
                    <flux:select.option value="'Comic Sans MS', cursive">Comic Sans MS</flux:select.option>
                    <flux:select.option value="'Oswald', sans-serif">Oswald</flux:select.option>
                    <flux:select.option value="'Roboto', sans-serif">Roboto</flux:select.option>
                    <flux:select.option value="'Montserrat', sans-serif">Montserrat</flux:select.option>
                </flux:select>
                @error('font_family') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4">
                <flux:button type="submit" variant="primary">{{ __('Save Appearance') }}</flux:button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
