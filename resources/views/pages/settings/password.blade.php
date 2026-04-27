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
    public string $registration_code = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $rules = [
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ];

            if (auth()->user()->role !== 'admin') {
                $rules['registration_code'] = [
                    'required',
                    'string',
                    'size:8',
                    function ($attribute, $value, $fail) {
                        $code = \App\Models\RegistrationCode::where('code', $value)
                            ->where('is_used', false)
                            ->first();
                        if (!$code) {
                            $fail('The registration code is invalid or has already been used.');
                        }
                    },
                ];
            }

            $validated = $this->validate($rules);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation', 'registration_code');

            throw $e;
        }

        $user = Auth::user();
        $user->update([
            'password' => $validated['password'],
        ]);

        // Mark code as used if applicable
        if (auth()->user()->role !== 'admin') {
            $code = \App\Models\RegistrationCode::where('code', $validated['registration_code'])->first();
            if ($code) {
                $code->update([
                    'is_used' => true,
                    'used_by' => $user->id,
                ]);
            }
        }

        $this->reset('current_password', 'password', 'password_confirmation', 'registration_code');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Password settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Update password')">
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
                <button type="button" onclick="togglePassword('current_password')" class="absolute top-8 right-3 text-gray-400 hover:text-gray-200" id="toggle_current_password">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
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
                <button type="button" onclick="togglePassword('new_password')" class="absolute top-8 right-3 text-gray-400 hover:text-gray-200" id="toggle_new_password">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
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
                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute top-8 right-3 text-gray-400 hover:text-gray-200" id="toggle_password_confirmation">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>

            @if(auth()->user()->role !== 'admin')
            <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl">
                <flux:input 
                    wire:model="registration_code" 
                    :label="__('Registration Code')" 
                    type="text" 
                    required 
                    placeholder="Enter 8-digit code to change password"
                    maxlength="8"
                />
            </div>
            @endif

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
        const button = document.getElementById('toggle_' + id);
        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
            `;
        } else {
            input.type = 'password';
            button.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            `;
        }
    }
</script>

