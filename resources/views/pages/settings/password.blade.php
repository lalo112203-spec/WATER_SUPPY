<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Password settings')] class extends Component {
    use PasswordValidationRules;

    public string $account_type = 'admin'; // admin, reader, customer, system_lock
    public ?int $selected_customer_id = null;
    
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $registration_code = '';

    public function with(): array
    {
        return [
            'customers' => \App\Models\User::where('role', 'consumer')
                ->whereNotNull('customer_id')
                ->with('customer')
                ->get(),
        ];
    }

    public function updatePassword(): void
    {
        $user = Auth::user();
        
        // If not admin, they can only change their own password (with registration code)
        if ($user->role !== 'admin') {
            $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
                'registration_code' => [
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
                ],
            ]);

            $user->update(['password' => $this->password]);
            
            $code = \App\Models\RegistrationCode::where('code', $this->registration_code)->first();
            if ($code) {
                $code->update([
                    'is_used' => true,
                    'used_by' => $user->id,
                ]);
            }

            $this->reset('current_password', 'password', 'password_confirmation', 'registration_code');
            $this->dispatch('password-updated');
            return;
        }

        // Admin logic
        if ($this->account_type === 'admin') {
            $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
            $user->update(['password' => $this->password]);
            
        } elseif ($this->account_type === 'reader') {
            $this->validate([
                'current_password' => $this->currentPasswordRules(), // Require admin password
                'password' => $this->passwordRules(),
            ]);
            $reader = \App\Models\User::where('role', 'reader')->first();
            if ($reader) {
                $reader->update(['password' => $this->password]);
            }
            
        } elseif ($this->account_type === 'customer') {
            $this->validate([
                'selected_customer_id' => 'required|exists:users,id',
                'current_password' => $this->currentPasswordRules(), // Require admin password
                'password' => $this->passwordRules(),
            ]);
            $customerUser = \App\Models\User::find($this->selected_customer_id);
            if ($customerUser && $customerUser->role === 'consumer') {
                $customerUser->update(['password' => $this->password]);
            }
            
        } elseif ($this->account_type === 'system_lock') {
            $this->validate([
                'current_password' => $this->currentPasswordRules(), // Require admin password
                'password' => $this->passwordRules(),
            ]);
            \App\Models\SystemSetting::set('system_lock_password', \Illuminate\Support\Facades\Hash::make($this->password));
        }

        $this->reset('current_password', 'password', 'password_confirmation', 'selected_customer_id');
        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Password settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Update password')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            @if(auth()->user()->role === 'admin')
            <div class="mb-6">
                <flux:select wire:model.live="account_type" :label="__('Account to Update')">
                    <option value="admin">My Account (Admin)</option>
                    <option value="reader">Reader Account</option>
                    <option value="customer">Customer Account</option>
                    <option value="system_lock">System Lock</option>
                </flux:select>
            </div>

            @if($account_type === 'customer')
            <div class="mb-6">
                <flux:select wire:model="selected_customer_id" :label="__('Select Customer')" searchable>
                    <option value="">Choose a customer...</option>
                    @foreach($customers as $customerUser)
                        <option value="{{ $customerUser->id }}">
                            {{ $customerUser->customer?->customer_id ?? 'N/A' }} - {{ $customerUser->customer?->name ?? 'Unknown' }}
                        </option>
                    @endforeach
                </flux:select>
            </div>
            @endif
            @endif

            <div class="relative">
                <flux:input
                    wire:model="current_password"
                    :label="auth()->user()->role === 'admin' && $account_type !== 'admin' ? __('Your Admin Password') : __('Current password')"
                    id="current_password"
                    type="password"
                    required
                    autocomplete="current-password"
                    viewable
                />
            </div>
            <div class="relative">
                <flux:input
                    wire:model="password"
                    :label="__('New password')"
                    id="new_password"
                    type="password"
                    required
                    autocomplete="new-password"
                    viewable
                />
            </div>
            <div class="relative">
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    id="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    viewable
                />
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

