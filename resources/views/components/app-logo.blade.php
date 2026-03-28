@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Water System" class="[&>div.truncate]:!text-white [&>div.truncate]:!font-bold [&>div.truncate]:!tracking-tight [&>div.truncate]:!drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-xl bg-blue-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]">
            <x-app-logo-icon class="size-6 text-white stroke-white" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Water System" class="[&>div.truncate]:!text-white [&>div.truncate]:!font-bold [&>div.truncate]:!tracking-tight [&>div.truncate]:!drop-shadow-[0_0_8px_rgba(6,182,212,0.8)]" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-xl bg-blue-500 text-white shadow-[0_0_15px_rgba(59,130,246,0.5)]">
            <x-app-logo-icon class="size-6 text-white stroke-white" />
        </x-slot>
    </flux:brand>
@endif
