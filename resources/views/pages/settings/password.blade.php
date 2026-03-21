<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Password settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Password settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <div class="relative">
                <flux:input
                    wire:model="current_password"
                    :label="__('Current password')"
                    id="current_password"
                    type="password"
                    required
                    autocomplete="current-password"
                />
                <button type="button" onclick="togglePassword('current_password')" class="absolute top-8 right-2 text-sm text-gray-600">
                    👁️
                </button>
            </div>
            <div class="relative">
                <flux:input
                    wire:model="password"
                    :label="__('New password')"
                    id="new_password"
                    type="password"
                    required
                    autocomplete="new-password"
                />
                <button type="button" onclick="togglePassword('new_password')" class="absolute top-8 right-2 text-sm text-gray-600">
                    👁️
                </button>
            </div>
            <div class="relative">
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    id="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                />
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute top-8 right-2 text-sm text-gray-600">
                    👁️
                </button>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-password-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-pages::settings.layout>
</section>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>
