<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $registration_code = '';
    public $profile_photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = $this->profileRules($user->id);
        
        // Require registration code ONLY if name is being changed
        if ($this->name !== $user->name) {
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

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->profile_photo) {
            $path = $this->profile_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        // Mark code as used if it was provided
        if (isset($validated['registration_code'])) {
            $code = \App\Models\RegistrationCode::where('code', $validated['registration_code'])->first();
            if ($code) {
                $code->update([
                    'is_used' => true,
                    'used_by' => $user->id,
                ]);
            }
        }

        $this->registration_code = '';
        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="mb-6 flex items-center gap-8 p-6 bg-white/5 rounded-3xl border border-white/10">
                <div class="relative group flex-shrink-0">
                    @if (Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="h-16 w-16 rounded-2xl object-cover border-2 border-cyan-500 shadow-xl shadow-cyan-500/20 transition-transform group-hover:scale-105">
                    @else
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-600 to-blue-700 flex items-center justify-center text-white text-xl font-bold shadow-xl shadow-cyan-500/10">
                            {{ Auth::user()->initials() }}
                        </div>
                    @endif
                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                <div class="flex-1">
                    <flux:heading size="lg">{{ __('Profile Picture') }}</flux:heading>
                    <flux:subheading>{{ __('Upload a new avatar to personalize your account.') }}</flux:subheading>
                    <input type="file" wire:model="profile_photo" class="mt-2 block w-full text-sm text-slate-400
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-xl file:border-0
                        file:text-xs file:font-bold
                        file:bg-cyan-500/10 file:text-cyan-400
                        hover:file:bg-cyan-500/20 transition-all" accept="image/*" />
                </div>
            </div>

            <flux:input wire:model.live="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div x-data="{ nameChanged: false }" x-init="$watch('$wire.name', value => nameChanged = value !== '{{ Auth::user()->name }}')">
                <template x-if="nameChanged">
                    <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-2xl">
                        <flux:input 
                            wire:model="registration_code" 
                            :label="__('Registration Code')" 
                            type="text" 
                            required 
                            placeholder="Enter 8-digit code to change Name"
                            maxlength="8"
                        />
                        <p class="mt-2 text-[11px] text-yellow-500/70 italic">
                            Changing your account name requires a valid registration code. You can obtain this at the **D.W.S.S. Office** by providing a valid ID to confirm your identity.
                        </p>
                    </div>
                </template>
            </div>

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>


    </x-pages::settings.layout>
</section>
